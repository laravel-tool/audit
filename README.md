# Laravel Tool Audit
Collecting audit infos from changes via observer

## Installation
### Composer
```bash
composer require laravel-tool/audit
```
### Laravel
Service provider auto discovered
### Lumen
Add to **bootstrap/app.php**
```php
$app->register(LaravelTool\Audit\ServiceProvider::class);
```
### Config
```bash
php artisan vendor:publish --provider=LaravelTool\Audit\ServiceProvider
```
### Migrations
```bash
php artisan migrate
```

## Usage
Install trait to Model class
```php
use LaravelTool\Audit\Traits\Auditable;
```
