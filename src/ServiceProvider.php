<?php


namespace LaravelTool\Audit;


use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Database\Events\TransactionCommitted;
use Illuminate\Database\Events\TransactionRolledBack;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\Str;
use LaravelTool\Audit\Listeners\DatabaseTransactionBegin;
use LaravelTool\Audit\Listeners\DatabaseTransactionCommit;
use LaravelTool\Audit\Listeners\DatabaseTransactionRollback;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'audit');
        $this->registerEvents();

        $config = $this->app['config']->get('audit');

        $this->app->singleton(AuditService::class, function () use ($config) {
            return new AuditService($config);
        });
    }

    public function boot()
    {
        if (!$this->isLumen()) {
            $this->publishes([$this->configPath() => config_path('audit.php')]);
        } else {
            $this->app->configure('audit');
        }

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    protected function registerEvents()
    {
        app('events')->listen(TransactionBeginning::class, DatabaseTransactionBegin::class);
        app('events')->listen(TransactionCommitted::class, DatabaseTransactionCommit::class);
        app('events')->listen(TransactionRolledBack::class, DatabaseTransactionRollback::class);
    }

    protected function configPath()
    {
        return __DIR__.'/../config/audit.php';
    }

    protected function isLumen()
    {
        return Str::contains($this->app->version(), 'Lumen');
    }
}
