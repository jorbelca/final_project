<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    /** @use HasFactory<\Database\Factories\CostFactory> */
    use HasFactory;
    protected $table = 'costs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'description',
        'cost',
        'unit',
        'periodicity',
    ];
    /**
     * Las relaciones del modelo.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
