<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Comite extends Authenticatable
{
    use HasFactory;

    protected $table = 'comite';

    protected $primaryKey = 'id_comite';

    public $timestamps = false;

    protected $fillable = [
        'email',
        'password_hash',
    ];

    protected $hidden = [
        'password_hash',
    ];

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id_comite';
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Get the name of the password attribute for the user.
     *
     * @return string
     */
    public function getAuthPasswordName()
    {
        return 'password_hash';
    }

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    /**
     * Find supervisor by email
     */
    public static function findByEmail($email)
    {
        return self::where('email', $email)->first();
    }

    /**
     * Verify if supervisor exists by email
     */
    public static function existePorEmail($email): bool
    {
        return self::where('email', $email)->exists();
    }

    /**
     * Create new supervisor
     */
    public static function crearSupervisor($email, $passwordHash): self
    {
        return self::create([
            'email' => $email,
            'password_hash' => $passwordHash,
        ]);
    }

    /**
     * Update supervisor password
     */
    public function actualizarPassword($passwordHash): bool
    {
        return $this->update([
            'password_hash' => $passwordHash,
        ]);
    }

    /**
     * Get supervisor email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Scope para buscar supervisores por criterio de email
     */
    public function scopePorEmail($query, $email)
    {
        return $query->where('email', 'like', "%{$email}%");
    }

    /**
     * Relación con las notificaciones del comité
     */
    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class, 'id_comite', 'id_comite');
    }

    /**
     * Obtener notificaciones no leídas del comité
     */
    public function notificacionesNoLeidas()
    {
        return $this->notificaciones()->noLeidas();
    }

    /**
     * Create new comite member
     */
    public static function crearComite($email, $passwordHash): self
    {
        return self::create([
            'email' => $email,
            'password_hash' => $passwordHash,
        ]);
    }
}
