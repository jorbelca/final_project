<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incidencie extends Model
{

    protected $table = 'incidencies';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'questioner_id',
        'answerer_id',
        'question',
        'answer',
        'reponse_date'
    ];
    /**
     * Las relaciones del modelo.
     */
    public function questioner()
    {
        return $this->belongsTo(User::class, 'questioner_id');
    }

    // Relación con el usuario que responde (answerer)
    public function answerer()
    {
        return $this->belongsTo(User::class, 'answerer_id');
    }
}
