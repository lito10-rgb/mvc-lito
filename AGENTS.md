# AGENTS.md

## Key Context
- PHP 8.3 Laravel 12 app (mvc-lito) on XAMPP, deployed to cPanel
- Database: MariaDB on XAMPP (`mysql -u root mqyeq`), InnoDB crash recovery via `Start-Process mysqld.exe`
- Spanish UI throughout
- cPanel hosting (no Git) — deploy via GitHub Actions + FTP
- PowerShell pipe corrupts UTF-8; use PHP `mysqli` for data import/migration when encoding matters

## Commands
- **Start MySQL**: `Start-Process "C:\xampp\mysql\bin\mysqld.exe" --datadir "C:\xampp\mysql\data"`
- **MySQL CLI**: `C:\xampp\mysql\bin\mysql.exe -u root mqyeq`
- **Import/export DB**: `mysqldump -u root mqyeq > dump.sql`
- **Serve**: `php artisan serve`
- **Migrate**: `php artisan migrate`

## Cotización Exchange Rate Feature
- `tipo_cambio` column (decimal 8,3) on `cotizaciones` table, nullable, defaults to 3.75
- `moneda` (PEN/USD) per product item in `productos_json` array
- `array_values($items)` in `store()` and `update()` to fix IndexUndefined 0 on row delete/add
- Create view: Moneda column + currency selector + tipo_cambio field, auto-loads last exchange rate
- Edit view: same currency selector and tipo_cambio field
- Show/Print: currency symbol per row, tipo_cambio in totals

## Deploy
- Push to `master` or `main` → `.github/workflows/deploy.yml` triggers
- Needs 9 GitHub secrets: FTP_HOST, FTP_USERNAME, FTP_PASSWORD, FTP_TARGET_DIR, APP_URL, DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD
- After deploy, run `php artisan migrate --force` on cPanel terminal
