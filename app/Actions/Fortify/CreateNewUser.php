<?php

namespace App\Actions\Fortify;

use App\Http\Controllers\SubscriptionController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
        // Create a new subscription for the user
        $response = SubscriptionController::create($user);

        if ($response['status'] !== 'success') {
            $user->delete(); // Rollback user creation if subscription fails
            redirect()->route('register')->with([
                'flash' => [
                    'banner' => "Error creating subscription",
                    'bannerStyle' => 'danger',
                ]
            ]);
        }
        return $user;
    }
}
