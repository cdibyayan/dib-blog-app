<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    // Redirect the user to the social media provider's login page
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    // Handle the callback from the provider
    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }

        // Find or create the user based on the provider's user information
        $existingUser = User::where('provider_id', $user->id)->where('provider', $provider)->first();

        if ($existingUser) {
            // Log the user in
            Auth::login($existingUser);
        } else {
            // Create a new user if it doesn't exist
            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'provider' => $provider,
                'provider_id' => $user->id,
                'password' => bcrypt('password'), // Generate a random password
            ]);

            Auth::login($newUser);
        }

        // Redirect to the home page or dashboard
        return redirect('/home');
    }
}
