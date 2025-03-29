<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;

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
        $plan = Plan::find(1); // Assuming 1 is the ID of the free plan
        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }
        // Check if the user already has a subscription
        $subscription = new Subscription();
        $subscription->user_id = $user->id;
        $subscription->plan_id = $plan->id;
        $subscription->active = 1;
        $subscription->starts_at = now();
        $features = json_decode($plan->features, true);
        $subscription->credits = $features['Credits'];
        $subscription->payment_number = null;
        $subscription->save();

        return response()->json(['message' => 'Subscription created successfully'], 201);
    }





    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubscriptionRequest $request, Subscription $subscription)
    {
        try {
            $user = User::find($request->input('subscription.user_id'));

            if (!Gate::allows('update', $user)) {
                return SubscriptionController::notify("", "Inactive User", false);
            }


            $validatedData = $request->validate([
                'default_taxes' => 'required|int|between:1,99',
                'company_name' => 'nullable|string|max:255',
            ]);

            $user->default_taxes = $validatedData['default_taxes'] ?? $user->default_taxes ?? 20;
            $user->company_name = $validatedData['company_name'] ?? $user->company_name ?? null;
            $user->save();

            if (
                $request->input('subscription.id') == $subscription->id &&
                $request->input('subscription.plan_id') == $subscription->plan_id &&
                $request->input('subscription.payment_number') == $subscription->payment_number
            ) {
                return SubscriptionController::notify('', "Updated");
            }

            if (!Gate::allows('update', $subscription)) {
                return SubscriptionController::notify("", "Inactive User", false);
            }
            //Cambiar el plan y los creditos
            $plan = Plan::find($request->input('subscription.plan_id'));
            if (!$plan) {
                return SubscriptionController::notify("", "Plan not found", false);
            }
            $features = json_decode($plan->features, true);

            // Prepare the data for validation
            $subscriptionData = $request->input('subscription', []);
            $subscriptionData['plan_id'] = $plan->id;
            $subscriptionData['credits'] = $features['Credits'];
            $subscriptionData['starts_at'] = now();
            $subscriptionData['ends_at'] = $plan->duration_in_days ? now()->addDays(intval($plan->duration_in_days)) : null;

            $request->merge(['subscription' => $subscriptionData]);

            // Validar los datos
            $validated = $request->validate([
                'subscription.user_id' => 'required|int|exists:users,id',
                'subscription.plan_id' => 'required|int|exists:plans,id',
                'subscription.active' => 'required|boolean',
                'subscription.starts_at' => 'required|date',
                'subscription.ends_at' => 'nullable|date',
                'subscription.credits' => 'required|int|min:0',
                'subscription.payment_number' => 'string|nullable|regex:/^[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}$/',
            ]);



            $subscription->update($validated['subscription']);


            return SubscriptionController::notify('', "Updated");
        } catch (\Throwable $th) {
            dd($th);
            return SubscriptionController::notify('', "Error updating the subscription", false);
        }
    }




    public function notify(String $sub_route, String $message, bool $success = true): RedirectResponse
    {
        if (!$success) {
            return redirect()->route('profile.show')->with([
                'flash' => [
                    'banner' => $message,
                    'bannerStyle' => 'danger',
                ]
            ]);
        }
        return redirect()->route('profile.show')->with([
            'flash' => [
                'banner' => $message,
                'bannerStyle' => 'success',
            ]
        ]);
    }
}
