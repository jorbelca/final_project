<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /** @use HasFactory<\Database\Factories\ClientFactory> */
    use HasFactory;
    protected $table = 'clients';
    protected $primary_key = 'client_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'company_name',
        'image_url'
    ];

    /**
     * Las relaciones del modelo.
     */

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'client_user', 'client_id', 'user_id')->withTimestamps();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }
}
