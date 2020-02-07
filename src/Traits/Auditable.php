<?php

namespace LaravelTool\Audit\Traits;

use LaravelTool\Audit\Models\Audit;
use LaravelTool\Audit\Observers\AuditObserver;

/**
 * Trait Auditable
 *
 *
 * @package App\Models\Traits
 */
trait Auditable
{

    /**
     * Scope boot
     */
    public static function bootAuditable()
    {
        if (config('audit.cli') || PHP_SAPI !== 'cli') {
            static::observe(new AuditObserver);
        }
    }

    public function getAuditExcludes()
    {
        return $this->auditExcludes ?? [];
    }

    public function audits()
    {
        return $this->morphMany(Audit::class, 'model');
    }
}
