<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Notifications\CustomResetPasswordNotification;
// --- INICIO CÓDIGO AGREGADO ---
use Illuminate\Notifications\Notifiable; // <-- AÑADE ESTA LÍNEA
// --- FIN CÓDIGO AGREGADO ---

class Supervisor extends Authenticatable implements CanResetPasswordContract
{
    // --- LÍNEA MODIFICADA ---
    use HasFactory, CanResetPassword, Notifiable; // <-- AÑADE NOTIFIABLE AQUÍ
    // --- FIN LÍNEA MODIFICADA ---

    protected $table = 'supervisor';

    protected $primaryKey = 'id_supervisor';

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
        return 'id_supervisor';
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
     * Enviar la notificación de reseteo de contraseña.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token, $this->email));
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
     * Relación con las notificaciones del supervisor
     */
    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class, 'id_supervisor', 'id_supervisor');
    }

    /**
     * Obtener notificaciones no leídas del supervisor
     */
    public function notificacionesNoLeidas()
    {
        return $this->notificaciones()->noLeidas();
    }
}