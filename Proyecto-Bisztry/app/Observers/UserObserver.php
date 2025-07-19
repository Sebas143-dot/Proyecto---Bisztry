<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB; // <-- ¡Añade esta línea!

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->logAudit('created', $user);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // Solo auditar si realmente hubo cambios en los atributos del modelo
        if ($user->isDirty()) {
            $this->logAudit('updated', $user);
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->logAudit('deleted', $user);
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        $this->logAudit('restored', $user);
    }

    /**
     * Handle the User "forceDeleted" event.
     */
    public function forceDeleted(User $user): void
    {
        $this->logAudit('forceDeleted', $user);
    }

    /**
     * Helper method to log audit entries.
     */
    protected function logAudit(string $event, User $user): void
    {
        $userId = Auth::id();

        DB::table('audits')->insert([ // <-- Aquí se usa DB sin la barra inicial, porque ya está importado
            'auditable_id' => $user->id,
            'auditable_type' => get_class($user),
            'event' => $event,
            'user_id' => $userId,
            'old_values' => json_encode($user->getOriginal()),
            'new_values' => json_encode($user->getChanges()),
            'url' => Request::fullUrl(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::header('User-Agent'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}