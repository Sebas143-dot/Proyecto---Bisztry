<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

// --- 1. IMPORTAMOS LA INTERFAZ DE JWT ---
use Tymon\JWTAuth\Contracts\JWTSubject;

// --- 2. AÑADIMOS LA IMPLEMENTACIÓN DE LA INTERFAZ ---
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // --- 3. AÑADIMOS LOS MÉTODOS REQUERIDOS POR JWT ---

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        // Este método le dice a JWT qué columna usar para identificar al usuario.
        // Usamos la clave primaria (el 'id' del usuario), que es lo estándar.
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        // Aquí podemos añadir información extra ("claims") al token.
        // Por ahora, lo dejamos vacío, pero en el futuro podrías añadir
        // el nombre del usuario o sus roles para tenerlos a mano en el frontend.
        return [];
    }
}
