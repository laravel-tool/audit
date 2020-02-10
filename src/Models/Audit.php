<?php

namespace LaravelTool\Audit\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Audit
 *
 * @property integer $id
 * @property string $event
 * @property string $user_type
 * @property integer $user_id
 * @property string $model_type
 * @property integer $model_id
 * @property string $parent_type
 * @property integer $parent_id
 * @property array $changes
 * @property string $ip
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
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
