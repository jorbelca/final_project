<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Budget;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
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

            return response()->json(['message' => 'Error '], 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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

            return response()->json(['message' => 'Error '], 400);
        }
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscription $subscription)
    {
        //
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

            return response()->json(['message' => 'Error '], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {

            $user->delete();

            return response()->json(['message' => 'Deleted'], 200);
        } catch (\Throwable $th) {
            // Para cualquier otro error
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
            return response()->json(['message' => 'Error '], 400);
        }
    }


    public function admin(User $user)
    {
        try {
            if (!Gate::allows('view', $user, User::class)) {
                return redirect()->route('budgets.index');
            };
            $usersAndBudgetsAndClients = User::with('budgets', 'clients', 'costs')->get();

            return Inertia::render('Admin', ['users' => $usersAndBudgetsAndClients]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error ' . $th], 400);
        }
    }
}
