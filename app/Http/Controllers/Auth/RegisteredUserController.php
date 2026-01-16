<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'gender' => ['required', 'in:male,female'],
            'age' => ['nullable', 'integer', 'min:5', 'max:100'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Get the first level (ordered by 'order' column) as default
        $firstLevel = \App\Models\Level::orderBy('order')->first();
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'age' => $request->age,
            'password' => Hash::make($request->password),
            'current_level_id' => $firstLevel?->id, // Default to first level
        ]);

        $user->assignRole('user');

        // Initialize user progress (unlock level 1)
        if ($firstLevel) {
            \App\Models\UserProgress::create([
                'user_id' => $user->id,
                'current_level_id' => $firstLevel->id,
                'highest_unlocked_level_id' => $firstLevel->id,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
