<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'dni',
        'direccion',
        'telefono',
        'verification_token',
        'password_created',
        'is_blocked',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'password_created' => 'boolean',
            'is_blocked' => 'boolean',
        ];
    }

    public function generateVerificationToken(): string
    {
        $token = Str::random(32);
        $this->update(['verification_token' => $token]);

        return $token;
    }

    public function verifyEmailWithToken(string $token): bool
    {
        if ($this->verification_token === $token) {
            $this->markEmailAsVerified();

            return true;
        }

        return false;
    }

    public function markEmailAsVerified(): void
    {
        $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
            'verification_token' => null,
        ])->save();
    }

    /**
     * Get the pedidos asignados al delivery.
     */
    public function pedidosAsignados(): HasMany
    {
        return $this->hasMany(Pedido::class, 'delivery_id');
    }

    /**
     * Get all pedidos for this user (as client).
     */
    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class, 'user_id');
    }

    /**
     * Scope a query to only include clientes.
     */
    public function scopeClientes($query)
    {
        return $query->where('role', 'cliente');
    }

    /**
     * Scope a query to only include blocked users.
     */
    public function scopeBloqueados($query)
    {
        return $query->where('is_blocked', true);
    }

    /**
     * Get the activity logs for this user.
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
