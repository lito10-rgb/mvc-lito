<?php
// ONE-TIME fix for CP850 mojibake on cafeperu_26. REMOVE from repo after use.
header('Content-Type: text/plain; charset=UTF-8');
error_reporting(E_ALL);
ini_set('display_errors', '1');
set_time_limit(600);

$TOKEN = 'k7D3x9mQ2fL8vZ5t';
$apply = isset($_GET['apply']) && (($_GET['token'] ?? '') === $TOKEN);

$host = 'localhost';
$db   = 'cafeperu_26';
$user = 'cafeperu_cafeperuano';
$pass = 'pelota10*';

$c = new mysqli($host, $user, $pass, $db);
if ($c->connect_error) {
    echo "CONNECT ERROR: " . $c->connect_error . "\n";
    exit;
}
$c->set_charset('utf8mb4');
echo "DB: $db @ $host | mode: " . ($apply ? 'APPLY' : 'DRY-RUN') . "\n\n";

function fixEncoding($v) {
    for ($i = 0; $i < 3; $i++) {
        $r = @iconv('UTF-8', 'CP850', $v);
        if ($r === false) break;
        if (!mb_check_encoding($r, 'UTF-8')) break;
        if ($r === $v) break;
        $v = $r;
    }
    if (preg_match('/[\x{2500}-\x{257F}]/u', $v)) {
        $r = reverseBoxPairs($v);
        if (mb_check_encoding($r, 'UTF-8')) {
            $v = $r;
            for ($i = 0; $i < 2; $i++) {
                $r2 = @iconv('UTF-8', 'CP850', $v);
                if ($r2 === false) break;
                if (!mb_check_encoding($r2, 'UTF-8')) break;
                if ($r2 === $v) break;
                $v = $r2;
            }
        }
    }
    return $v;
}

function reverseBoxPairs($s) {
    $chars = preg_split('//u', $s, -1, PREG_SPLIT_NO_EMPTY);
    $out = '';
    $n = count($chars);
    for ($i = 0; $i < $n; $i++) {
        $cp = mb_ord($chars[$i], 'UTF-8');
        if ($cp >= 0x2500 && $cp <= 0x257F) {
            $b1 = @iconv('UTF-8', 'CP850', $chars[$i]);
            if ($b1 === false || strlen($b1) !== 1) { $out .= $chars[$i]; continue; }
            $out .= $b1;
            if ($i + 1 < $n) {
                $b2 = @iconv('UTF-8', 'CP850', $chars[$i + 1]);
                if ($b2 !== false && strlen($b2) === 1) { $out .= $b2; $i++; }
            }
        } else {
            $out .= $chars[$i];
        }
    }
    return $out;
}

// marker patterns (HEX of stored bytes):
//   single-mangle: ├=E2949C, ┬=E294AC
//   double-mangle: Ôö=C394C3B6 (prefix produced when ├/┬ bytes are re-read as CP850)
$MARKER = "HEX(`%s`) LIKE '%%E2949C%%' OR HEX(`%s`) LIKE '%%E294AC%%' OR HEX(`%s`) LIKE '%%C394C3B6%%'";

function primaryKey($c, $t) {
    $pk = [];
    $r = $c->query("SHOW KEYS FROM `$t`");
    if ($r) {
        while ($k = $r->fetch_assoc()) {
            if ($k['Key_name'] === 'PRIMARY') $pk[] = $k['Column_name'];
        }
    }
    return $pk;
}

$tables = [];
$r = $c->query("SHOW TABLES");
while ($row = $r->fetch_row()) {
    $tables[] = $row[0];
}

$scanned = 0;
$affected = [];
foreach ($tables as $t) {
    $r = $c->query("SHOW FULL COLUMNS FROM `$t`");
    if (!$r) continue;
    while ($col = $r->fetch_assoc()) {
        $type = strtolower($col['Type']);
        $isText = strpos($type, 'varchar') === 0 || strpos($type, 'text') === 0 || strpos($type, 'char') === 0;
        if (!$isText) continue;
        $f = $col['Field'];
        $where = sprintf($MARKER, $f, $f, $f);
        $q = $c->query("SELECT COUNT(*) c FROM `$t` WHERE $where");
        if (!$q) continue;
        $n = (int)$q->fetch_assoc()['c'];
        $scanned++;
        if ($n > 0) {
            $affected[] = ['t' => $t, 'f' => $f, 'n' => $n];
        }
    }
}

echo "text columns scanned: $scanned | affected: " . count($affected) . "\n\n";

$totalFixed = 0;
foreach ($affected as $i => $a) {
    $t = $a['t']; $f = $a['f'];
    $where = sprintf($MARKER, $f, $f, $f);
    $pk = primaryKey($c, $t);

    if ($pk) {
        $sel = $c->query("SELECT `" . implode('`,`', $pk) . "`, `$f` FROM `$t` WHERE $where");
        $stmt = $c->prepare("UPDATE `$t` SET `$f` = ? WHERE `" . implode('` = ? AND `', $pk) . "` = ?");
    } else {
        $sel = $c->query("SELECT `$f` FROM `$t` WHERE $where");
        $stmt = $c->prepare("UPDATE `$t` SET `$f` = ? WHERE BINARY `$f` = BINARY ?");
    }
    if (!$sel || !$stmt) {
        echo "[$t.$f] FAILED: " . $c->error . "\n";
        continue;
    }

    $changed = 0;
    $firstSample = null;
    while ($row = $sel->fetch_row()) {
        if ($pk) {
            $vals = array_slice($row, 0, count($pk));
            $v = $row[count($pk)];
        } else {
            $v = $row[0];
        }
        $fixed = fixEncoding($v);
        if ($fixed === $v) continue;
        if ($firstSample === null) {
            $firstSample = [$v, $fixed];
        }
        if ($apply) {
            $types = str_repeat('s', count($vals) + 1);
            $params = array_merge([$fixed], $vals);
            $stmt->bind_param($types, ...$params);
            if (!$stmt->execute()) {
                echo "[$t.$f] UPDATE ERROR: " . $stmt->error . "\n";
                break;
            }
        }
        $changed++;
    }
    $totalFixed += $changed;
    $after = null;
    $q = $c->query("SELECT COUNT(*) c FROM `$t` WHERE $where");
    if ($q) $after = (int)$q->fetch_assoc()['c'];

    echo sprintf("%-28s %-24s rows=%d changed=%s after=%s\n", $t, $f, $a['n'], $apply ? $changed : 'dry', $after === null ? '?' : $after);
    if ($firstSample) {
        echo "    before: " . mb_substr($firstSample[0], 0, 100) . "\n";
        echo "    after:  " . mb_substr($firstSample[1], 0, 100) . "\n";
    }
}

echo "\nTOTAL changed: $totalFixed\n";
echo $apply ? "DONE (apply mode)\n" : "DRY RUN - rerun with ?apply=1&token=... to write\n";
