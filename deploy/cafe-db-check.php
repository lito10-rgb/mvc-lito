<?php
header('Content-Type: text/plain; charset=UTF-8');
error_reporting(E_ALL);
ini_set('display_errors', '1');

$host = 'localhost';
$db   = 'cafeperu_26';
$user = 'cafeperu_cafeperuano';
$pass = 'pelota10*';

$c = @new mysqli($host, $user, $pass, $db);
if ($c->connect_error) {
    echo "CONNECT ERROR: " . $c->connect_error . "\n";
    exit;
}
$c->set_charset('utf8mb4');
echo "DB: $db @ $host\n";
echo "server: " . $c->server_info . "\n";
echo "conn charset: " . $c->character_set_name() . "\n\n";

$tables = [];
$r = $c->query("SHOW TABLES");
while ($row = $r->fetch_row()) {
    $tables[] = $row[0];
}
echo "tables: " . count($tables) . "\n\n";

$MOJI = "\xE2\x94\x9C"; // UTF-8 bytes of U+251C '├' (CP850 signature)
$CLEAN_E = "\xC3\xA9";   // UTF-8 bytes of 'é'
$hitCols = [];
$totalMoji = 0;
$totalCleanE = 0;

foreach ($tables as $t) {
    $r = $c->query("SHOW FULL COLUMNS FROM `$t`");
    if (!$r) continue;
    while ($col = $r->fetch_assoc()) {
        $type = strtolower($col['Type']);
        $isText = strpos($type, 'varchar') === 0 || strpos($type, 'text') === 0 || strpos($type, 'char') === 0;
        if (!$isText) continue;
        $f = $col['Field'];
        $q = $c->query("SELECT COUNT(*) c FROM `$t` WHERE HEX(`$f`) LIKE '%E2949C%'");
        $moji = $q ? (int)$q->fetch_assoc()['c'] : -1;
        $q = $c->query("SELECT COUNT(*) c FROM `$t` WHERE HEX(`$f`) LIKE '%C3A9%'");
        $cleanE = $q ? (int)$q->fetch_assoc()['c'] : -1;
        if ($moji > 0 || $cleanE > 0) {
            printf("%-34s %-26s %-22s moji=%d cleanE=%d\n", $t, $f, $col['Collation'], $moji, $cleanE);
        }
        if ($moji > 0) { $totalMoji += $moji; $hitCols[] = [$t, $f]; }
        if ($cleanE > 0) $totalCleanE += $cleanE;
    }
}

echo "\n=== SAMPLES (columns containing the ├ marker) ===\n";
$shown = 0;
foreach ($hitCols as [$t, $f]) {
    if ($shown >= 25) break;
    $r = $c->query("SELECT HEX(`$f`) h, `$f` v FROM `$t` WHERE HEX(`$f`) LIKE '%E2949C%' LIMIT 1");
    if ($r && $row = $r->fetch_row()) {
        echo "[$t.$f] hex=" . substr($row[0], 0, 140) . "\n";
        echo "        txt=" . mb_substr($row[1], 0, 100, 'UTF-8') . "\n";
        $shown++;
    }
}

echo "\n=== TOTALS ===\n";
echo "rows with ├ marker: $totalMoji | rows with clean é: $totalCleanE\n";
