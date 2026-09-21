<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    public const DEFAULT_PASSWORD = 'Gateway@2026';

    public const ROLES = [
        'admin' => 'Administrator',
        'pm_manager' => 'PM Manager',
        'pm_admin' => 'PM Admin',
        'dial_a' => 'Dial-A',
        'dealer' => 'Dealer',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'dealer_id',
        'name',
        'email',
        'designation',
        'role',
        'is_active',
        'password',
        'must_change_password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
            'is_active' => 'boolean',
            'must_change_password' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    public function submittedRequests()
    {
        return $this->hasMany(PropertyRequest::class, 'submitted_by');
    }

    public function assignedRequests()
    {
        return $this->hasMany(PropertyRequest::class, 'assigned_support_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSupport(): bool
    {
        return $this->isDialA();
    }

    public function isDialLead(): bool
    {
        return $this->isDialA();
    }

    public function isHandyman(): bool
    {
        return false;
    }

    public function isDialA(): bool
    {
        return in_array($this->role, ['dial_a', 'pm_support', 'dial_lead']);
    }

    public function isRepresentative(): bool
    {
        return false;
    }

    public function isManager(): bool
    {
        return in_array($this->role, ['pm_manager', 'pm_admin'], true);
    }

    public function isDealer(): bool
    {
        return $this->role === 'dealer';
    }

    public function canManageDealers(): bool
    {
        return $this->isManager() || $this->isAdmin();
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'Administrator',
            'pm_manager' => 'PM Manager',
            'pm_admin' => 'PM Admin',
            'dial_a', 'pm_support', 'dial_lead' => 'Dial-A',
            default => 'Dealer',
        };
    }

    public function getInitialsAttribute(): string
    {
        return collect(explode(' ', trim($this->name)))
            ->filter()
            ->take(2)
            ->map(fn (string $part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');
    }
}
