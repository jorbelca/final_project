<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Subscription::all();
    }

    public static function getUserSubscription(User $user)
    {
        return Subscription::where('user_id', $user->id)->first();
    }

    /**
     * Create a new subscription with the plan free
     */
    public static function create(User $user)
    {
        $plan = Plan::findById(1);
        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }
        // Check if the user already has a subscription
        $subscription = new Subscription();
        $subscription->user_id = $user->id;
        $subscription->plan_id = $plan->id;
        $subscription->active = 1;
        $subscription->starts_at = now();
        $subscription->credits = $plan->credits;
        $subscription->save();

        return response()->json(['message' => 'Subscription created successfully'], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubscriptionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription)
    {
        //
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
    public function update(UpdateSubscriptionRequest $request, Subscription $subscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription)
    {
        //
    }
}
