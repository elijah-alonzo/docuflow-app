<?php

namespace App\Observers;

use App\Features\Logs\Models\Log;
use App\Features\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ModelActionObserver
{
    private array $sensitiveFields = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    private array $userPiiFields = [
        'avatar',
        'first_name',
        'middle_initial',
        'last_name',
        'email',
        'contact_number',
    ];

    public function created(Model $model): void
    {
        $this->log('created', $model);
    }

    public function updated(Model $model): void
    {
        if ($this->isPasswordChange($model)) {
            $this->logPasswordChange($model);

            return;
        }

        $this->log('updated', $model);
    }

    public function deleted(Model $model): void
    {
        $this->log('deleted', $model);
    }

    private function isPasswordChange(Model $model): bool
    {
        return $model->isDirty('password');
    }

    private function logPasswordChange(Model $model): void
    {
        $this->writeLog('changed_password', $model, 'Changed password', null);
    }

    private function log(string $action, Model $model): void
    {
        $changes = $this->buildChanges($action, $model);
        $description = ucfirst($action).' '.class_basename($model);

        $this->writeLog($action, $model, $description, $changes);
    }

    private function buildChanges(string $action, Model $model): ?array
    {
        if ($action === 'updated') {
            return $this->buildUpdateChanges($model);
        }

        if ($action === 'created' || $action === 'deleted') {
            return $this->filterAttributes($model, $model->getAttributes());
        }

        return null;
    }

    private function buildUpdateChanges(Model $model): ?array
    {
        $changes = [];
        $original = $model->getOriginal();

        foreach ($model->getChanges() as $key => $newValue) {
            if ($this->shouldSkipField($model, $key)) {
                continue;
            }

            $changes[$key] = [
                'from' => $this->normalizeValue($original[$key] ?? null),
                'to' => $this->normalizeValue($newValue),
            ];
        }

        return $changes ?: null;
    }

    private function filterAttributes(Model $model, array $attributes): ?array
    {
        $filtered = [];

        foreach ($attributes as $key => $value) {
            if ($this->shouldSkipField($model, $key)) {
                continue;
            }

            $filtered[$key] = $this->normalizeValue($value);
        }

        return $filtered ?: null;
    }

    private function shouldSkipField(Model $model, string $key): bool
    {
        if (in_array($key, $this->sensitiveFields, true)) {
            return true;
        }

        if ($model instanceof User && in_array($key, $this->userPiiFields, true)) {
            return true;
        }

        return in_array($key, $model->getHidden(), true);
    }

    private function normalizeValue(mixed $value): mixed
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }

        return $value;
    }

    private function writeLog(string $action, Model $model, ?string $description, ?array $changes): void
    {
        if ($model instanceof Log || app()->runningInConsole()) {
            return;
        }

        $request = request();

        Log::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => $model->getMorphClass(),
            'model_id' => $model->getKey(),
            'description' => $description,
            'changes' => $changes,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }
}
