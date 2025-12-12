<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogObserver
{
    /**
     * Handle the Model "created" event.
     */
    public function created(Model $model): void
    {
        $this->logActivity($model, 'created', null, $model->getAttributes());
    }

    /**
     * Handle the Model "updated" event.
     */
    public function updated(Model $model): void
    {
        $this->logActivity(
            $model,
            'updated',
            $model->getOriginal(),
            $model->getAttributes()
        );
    }

    /**
     * Handle the Model "deleted" event.
     */
    public function deleted(Model $model): void
    {
        $this->logActivity($model, 'deleted', $model->getAttributes(), null);
    }

    /**
     * Log activity to audit_logs table
     *
     * @param Model $model
     * @param string $action
     * @param array|null $oldValues
     * @param array|null $newValues
     * @return void
     */
    protected function logActivity(
        Model $model,
        string $action,
        ?array $oldValues,
        ?array $newValues
    ): void {
        // Don't log if the model is AuditLog itself (prevent infinite loop)
        if ($model instanceof AuditLog) {
            return;
        }

        // Filter out timestamps and sensitive data if needed
        $oldValues = $this->filterSensitiveData($oldValues);
        $newValues = $this->filterSensitiveData($newValues);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'table_name' => $model->getTable(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Filter sensitive data from being logged
     *
     * @param array|null $data
     * @return array|null
     */
    protected function filterSensitiveData(?array $data): ?array
    {
        if (!$data) {
            return null;
        }

        // Remove sensitive fields like password, remember_token
        $sensitiveFields = ['password', 'remember_token'];

        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '***HIDDEN***';
            }
        }

        return $data;
    }
}
