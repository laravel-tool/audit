<?php


namespace LaravelTool\Audit\Listeners;


use Illuminate\Database\Events\TransactionRolledBack;
use LaravelTool\Audit\AuditService;

class DatabaseTransactionRollback
{
    public function handle(TransactionRolledBack $event)
    {
        app(AuditService::class)->rollback($event->connection->transactionLevel());
    }
}
