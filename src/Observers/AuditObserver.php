<?php

namespace LaravelTool\Audit\Observers;


use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Arr;
use LaravelTool\Audit\AuditService;
use LaravelTool\Audit\Traits\Auditable;

class AuditObserver
{
    public $excluded = ['created_at', 'updated_at'];

    /**
     * @param  Model|Auditable  $model
     */
    public function created($model)
    {
        $this->createJob($model, 'created');
    }

    /**
     * @param  Model|Auditable  $model
     */
    public function updated($model)
    {
        $this->createJob($model, 'updated');
    }

    /**
     * @param  Model|Auditable  $model
     */
    public function deleted($model)
    {
        $this->createJob($model, 'deleted');
    }

    /**
     * @param  Model|Auditable  $model
     * @param                 $event
     */
    protected function createJob($model, $event)
    {
        /** @var Guard $auth */
        $auth = app('auth');
        if ($auth->check()) {
            /** @var Model|Authenticatable $user */
            $user = $auth->user();
            $userType = get_class($user);
            $userId = $user->getKey();
        } else {
            $userType = null;
            $userId = null;
        }

        $modelType = get_class($model);
        if ($model instanceof Pivot) {
            $pivot = $model::query()
                ->where($model->getForeignKey(), $model->{$model->getForeignKey()})
                ->where($model->getRelatedKey(), $model->{$model->getRelatedKey()})
                ->first();
            $modelId = $pivot->{$pivot->getKey()};
        } else {
            $modelId = $model->getKey();
        }

        $ip = app('request')->ip();

        $excluded = array_merge($this->excluded, $model->getAuditExcludes());
        $original = $model->getOriginal();

        $changes = [];
        foreach (Arr::except($model->getDirty(), $excluded) as $key => $value) {
            $originalValue = $original[$key] ?? null;

            if (is_null($originalValue) && is_null($value)) {
                continue;
            }

            if ($originalValue == $value) {
                continue;
            }

            $changes[$key] = [$originalValue, $value];
        }

        if (empty($changes)) {
            return;
        }

        $parentType = null;
        $parentId = null;
        if (defined(get_class($model).'::AUDIT_PARENT_TYPE') && defined(get_class($model).'::AUDIT_PARENT_FIELD')) {
            $parentType = $model::AUDIT_PARENT_TYPE;
            $parentId = $model->{$model::AUDIT_PARENT_FIELD};
        }

        app(AuditService::class)->push($model->getConnection()->transactionLevel(), [
            'event'       => $event,
            'model_type'  => $modelType,
            'model_id'    => $modelId,
            'user_type'   => $userType,
            'user_id'     => $userId,
            'parent_type' => $parentType,
            'parent_id'   => $parentId,
            'changes'     => json_encode($changes),
            'ip'          => $ip,
            'created_at'  => Carbon::now()
        ]);

    }
}
