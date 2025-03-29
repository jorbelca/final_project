<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $table = 'budgets';

    /**
     * Los atributos que se pueden asignar de forma masiva.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'client_id',
        'content',
        'state',
        'discount',
        'taxes'
    ];

    /**
     * Las relaciones del modelo.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

   
}
