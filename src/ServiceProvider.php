<?php


namespace LaravelTool\Audit;


use Illuminate\Contracts\Http\Kernel;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Database\Events\TransactionCommitted;
use Illuminate\Database\Events\TransactionRolledBack;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
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
        }

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    protected function registerEvents()
    {
        Event::listen(TransactionBeginning::class, DatabaseTransactionBegin::class);
        Event::listen(TransactionCommitted::class, DatabaseTransactionCommit::class);
        Event::listen(TransactionRolledBack::class, DatabaseTransactionRollback::class);
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
