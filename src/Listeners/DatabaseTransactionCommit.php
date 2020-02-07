<?php


namespace LaravelTool\Audit\Listeners;


use Illuminate\Database\Events\TransactionCommitted;
use LaravelTool\Audit\AuditService;

class DatabaseTransactionCommit
{
    public function handle(TransactionCommitted $event)
    {
        app(AuditService::class)->commit($event->connection->transactionLevel());
    }
}
