<?php

namespace LaravelTool\Audit\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use LaravelTool\Audit\Models\Audit;

class AuditJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $auditRows;

    /**
     * Create a new job instance.
     * @param  array[]  $auditRows
     */
    public function __construct($auditRows)
    {
        $this->auditRows = $auditRows;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Audit::query()->insert($this->auditRows);
    }
}
