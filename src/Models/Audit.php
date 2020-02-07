<?php

namespace LaravelTool\Audit\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Audit
 *
 * @property array $changes
 * @package App\Models
 */
class Audit extends Model
{

    protected $fillable = [
        'event',
        'user_type',
        'user_id',
        'model_type',
        'model_id',
        'parent_type',
        'parent_id',
        'changes',
        'ip',
    ];

    protected $casts = [
        'changes' => 'json',
    ];

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('audit.table'));
        parent::__construct($attributes);
    }

    public function user()
    {
        return $this->morphTo();
    }

    public function model()
    {
        return $this->morphTo();
    }

}
