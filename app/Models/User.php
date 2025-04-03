<?php

namespace App\Models;

    // use Illuminate\Contracts\Auth\MustVerifyEmail;

;

use App\Services\CloudinaryService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        "user_logo_path",
        "active"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_path',
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
        ];
    }
    public function getProfilePhotoPathAttribute()
    {
        if ($this->attributes['profile_photo_path'] == null) {
            return null;
        };
        //en local -> return asset('storage/' . $this->attributes['profile_photo_path']);
        //Cloudinary
        return $this->attributes['profile_photo_path'];
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class);
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_user',  'user_id', 'client_id')->withTimestamps();
    }
    public function costs()
    {
        return $this->hasMany(Cost::class);
    }
    public function incidencies()
    {
        return $this->hasMany(Support::class);
    }
    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }
    public function prompt()
    {
        return $this->hasOne(Prompt::class);
    }

    public function deleteProfilePhoto(): void
    {
        if (!$this->profile_photo_path) {
            return;
        }

        // Eliminar de Cloudinary
        CloudinaryService::deletePhoto($this->profile_photo_path, 'user_images');

        // Eliminar de la base de datos
        $this->forceFill([
            'profile_photo_path' => null,
        ])->save();
    }

    function hasCredits()
    {
        return $this->subscription->credits > 0;
    }
}
