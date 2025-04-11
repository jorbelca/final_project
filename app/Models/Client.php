<?php

namespace App\Models;

use App\Services\CloudinaryService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'image_url',
        'created_by',
        'deleted'
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

    /**
     * Reasignar el creador del cliente si es necesario.
     */
    public static function reassignCreator(Client $client): void
    {
        // Buscar el segundo usuario más antiguo con relación a este cliente
        $secondOldestUser = DB::table('client_user')
            ->where('client_id', $client->id)
            ->orderBy('created_at', 'asc')
            ->skip(1)
            ->first();


        if ($secondOldestUser) {
            // Asignar el nuevo creador
            $client->update(['created_by' => $secondOldestUser->user_id]);
        } else {
            // Si no hay más usuarios, marcar el cliente como deleted (soft delete)
            $client->update(['deleted' => '1']);
            // Si no hay budgets relacinados con el cliente, eliminarlo
            if ($client->budgets()->count() === 0) {
                Client::removeClient($client);
            }
        }
    }

    public static function removeClient(Client $client): void
    {
        //Si está eliminado, eliminarlo
        if ($client->deleted === '1') {
            //Si tiene imagen, eliminarla
            if ($client->image_url) {
                CloudinaryService::deletePhoto($client->image_url, "client_logos");
            }
            $client->delete();
        }
    }
}
