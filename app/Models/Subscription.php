<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /** @use HasFactory<\Database\Factories\SubscriptionsFactory> */
    use HasFactory;
    protected $table = 'subscriptions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'plan_id',
        'payment_number',
        'active',
        'starts_at',
        'ends_at',
        'credits',
        'renovations'
    ];

    // protected $hidden = [
    //     'payment_number',
    //     'active',
    //     'starts_at',
    //     'ends_at',
    //     'credits',
    // ];
    /**
     * Las relaciones del modelo.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hasExpired()
    {
        return $this->ends_at < now();
    }


    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
