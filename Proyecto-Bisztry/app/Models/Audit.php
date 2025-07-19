<?php

namespace App\Models;

use OwenIt\Auditing\Models\Audit as BaseAudit; // Importa el modelo base del paquete

class Audit extends BaseAudit // Tu modelo Audit debe extender el BaseAudit del paquete
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'old_values' => 'json', // O 'array', ambos deberían funcionar con Laravel 9+
        'new_values' => 'json',
        'created_at' => 'datetime', // ¡Esto asegura que sea un objeto Carbon!
        'updated_at' => 'datetime', // ¡Y esto también!
    ];

    /**
     * Get the user that owns the audit.
     * This relation connects the audit record to the User who performed the action.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function user()
    {
        return $this->morphTo();
    }

    /**
     * Get the auditable model that the audit refers to.
     * This relation connects the audit record to the model that was audited (e.g., Cliente, Producto).
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function auditable()
    {
        return $this->morphTo();
    }
}