<?php


namespace LaravelTool\Audit\Listeners;


use Illuminate\Database\Events\TransactionBeginning;

class DatabaseTransactionBegin
{
    public function handle(TransactionBeginning $event)
    {
    }
}
