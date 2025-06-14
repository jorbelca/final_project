<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;

use App\Http\Requests\UpdateUserRequest;
use App\Models\Client;
use App\Models\Subscription;
use App\Models\Support;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return User::all();
        } catch (\Throwable $th) {
            throw new \Exception("Error fetching users", 0, $th);
            return response()->json(['message' => 'Error '], 400);
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            // Validar
            $validated = $request->validated();

            $validated['active'] = 1;

            $newUser = new User($validated);

            $newUser->save();

            return response()->json(['message' => 'User Created : \n ', $newUser], 201);
        } catch (\Throwable $th) {
            throw new \Exception("Error creating user", 0, $th);
            return response()->json(['message' => 'Error '], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            return $user;
        } catch (\Throwable $th) {
            throw new \Exception("Error fetching user", 0, $th);
            return response()->json(['message' => 'Error '], 400);
        }
    }


    // Show the budgets of one user
    public function showUserBudgets(string $id)
    {
        try {
            $user = User::with('budgets')->findOrFail($id);


            return response()->json(['Budgets of ' . $user->name => $user->budgets]);
        } catch (\Throwable $th) {
            throw new \Exception("Error fetching user budgets", 0, $th);
            return response()->json(['message' => 'Error '], 400);
        }
    }
    // Show the clients of one user
    public function showUserClients(string $id)
    {
        try {
            $user = User::with('clients')->findOrFail($id);

            return response()->json(['Clients of ' . $user->name => $user->clients]);
        } catch (\Throwable $th) {
            throw new \Exception("Error fetching user clients", 0, $th);
            return response()->json(['message' => 'Error '], 400);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $validated = $request->validated();

            $user->update($validated);

            return response()->json(['message' => 'User Updated ', "userUpdated" => $user], 200);
        } catch (\Throwable $th) {
            throw new \Exception("Error updating user", 0, $th);
            return response()->json(['message' => 'Error '], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public static function destroy(User $user)
    {
        try {
            // Eliminar el usuario

            $user->delete();

            return response()->json(['message' => 'Deleted'], 200);
        } catch (\Throwable $th) {
            // Para cualquier otro error
            throw new \Exception("Error deleting user", 0, $th);
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }


    public function changeState($id)
    {

        try {
            $user = User::findOrFail($id);

            $user->active = !$user->active;
            $user->save();

            response()->json(['message' => 'Changed'], 200);
            return redirect('admin');
        } catch (\Throwable $th) {
            throw new \Exception("Error changing user state", 0, $th);
            return response()->json(['message' => 'Error '], 400);
        }
    }


    public function admin(User $user)
    {
        try {
            if (!Gate::allows('view', $user, User::class)) {
                return redirect()->route('budgets.index');
            };
            // Obtener usuarios con budgets y contar budgets por estado
            $usersAndBudgetsAndClientsAndSubscriptionAndIncidencies = User::with(['budgets' => function ($query) {
                $query->select('state', 'user_id');
            }])->withCount(['clients', 'costs'])->get();

            // Añadir el conteo de budgets por estado a cada usuario
            $usersAndBudgetsAndClientsAndSubscriptionAndIncidencies->each(function ($user) {
                $user->budgetCounts = $user->budgets
                    ->groupBy('state')
                    ->map(fn($items) => $items->count());
            });


            // Añadir la suscripción y las incidencias a cada usuario
            $usersAndBudgetsAndClientsAndSubscriptionAndIncidencies->each(function ($user) {
                $user->setAttribute('subscription_data', Subscription::where('subscriptions.user_id', $user->id)
                    ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
                    ->select('subscriptions.*', 'plans.name as plan_name', 'plans.price as plan_price')
                    ->first());
                $user->setAttribute('incidencies_count', Support::where('support.questioner_id', $user->id)->count());
            });
            return Inertia::render('Admin/Admin', ['users' => $usersAndBudgetsAndClientsAndSubscriptionAndIncidencies]);
        } catch (\Throwable $th) {
            throw new \Exception("Error fetching admin data", 0, $th);
            return response()->json(['message' => 'Error ' . $th->getMessage()], 400);
        }
    }
}
