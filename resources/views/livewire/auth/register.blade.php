<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public $user_type;
    public string $gender = '';
    public string $date_of_birth = ''; // Use string to handle date input
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'user_type' => ['required'],
            'gender' => ['required'], 
            'date_of_birth' => ['required', 'date', 'before:today'], // Must be a valid past date
        ]);


        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        // $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
        // Redirect based on user type
        if ($user->user_type == 2) {
            $this->redirect(route('homestaurant.application'), navigate: true);
        } else {
            $this->redirectIntended(route('landing', absolute: false), navigate: true);
        }
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name"
            :placeholder="__('Full name')" />

        <!-- Email Address -->
        <flux:input wire:model="email" :label="__('Email address')" type="email" required autocomplete="email"
            placeholder="email@example.com" />

        <!-- User Roles -->
        <flux:radio.group wire:model="user_type" label="Role">
            <flux:radio value="1" label="General user"
                description="General users can regular purchasing and browsing." checked />
            <flux:radio value="2" label="Homestaurant owner"
                description="Homestaurant owners can set up and sell homemade food with full user features." />
        </flux:radio.group>

        <!-- User Gender -->
        <flux:radio.group wire:model="gender" label="Gender" variant="segmented">
            <flux:radio value="male" label="Male" />
            <flux:radio value="female" label="Female" checked />
            <flux:radio value="other" label="Other" />
        </flux:radio.group>

        <flux:input type="date" wire:model="date_of_birth" max="{{ now()->toDateString() }}" label="Date of birth" />

        <!-- Password -->
        <flux:input wire:model="password" :label="__('Password')" type="password" required autocomplete="new-password"
            :placeholder="__('Password')" viewable />

        <!-- Confirm Password -->
        <flux:input wire:model="password_confirmation" :label="__('Confirm password')" type="password" required
            autocomplete="new-password" :placeholder="__('Confirm password')" viewable />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary"
                class="w-full text-white bg-red-700 hover:bg-red-800 cursor-pointer font-semibold">
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
        {{ __('Already have an account?') }}
        <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
    </div>
</div>
