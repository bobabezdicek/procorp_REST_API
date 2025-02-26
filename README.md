## Spuštění projektu:

```bash
composer install
npm install
docker compose up -d
php artisan migrate:fresh
php artisan db:seed --class=UserSeeder
composer dev
```
