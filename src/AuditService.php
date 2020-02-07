<?php


namespace LaravelTool\Audit;


use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use LaravelTool\Audit\Jobs\AuditJob;

class AuditService
{
    protected $config;

    protected $audit;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function push($level, $auditRow)
    {
        if ($level == 0) {
            $this->dispatch([$auditRow]);
        } else {
            if (!isset($this->audit[$level])) {
                $this->audit[$level] = [];
            }

            $this->audit[$level][] = $auditRow;
        }
    }

    public function commit($level)
    {
        if ($level === 0) {
            $this->dispatch(Arr::collapse($this->audit));
        }
    }

    public function rollback($level)
    {
        for ($i = $level + 1; $i <= max(array_keys($this->audit)); $i++) {
            unset($this->audit[$i]);
        }
    }

    protected function dispatch($auditRows)
    {
        $dispatch = dispatch(new AuditJob($auditRows));
        if (!is_null($this->config['queue']['connection'])) {
            $dispatch->onConnection($this->config['queue']['connection']);
        }
        if (!is_null($this->config['queue']['name'])) {
            $dispatch->onQueue($this->config['queue']['name']);
        }
    }
}
