<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public ?string $gender = null;
    public ?string $date_of_birth = null;
    public $profile_image;
    public ?string $phone = null;
    public ?string $address = null;

    public function mount(): void
    {
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->gender = $user->gender;
        $this->date_of_birth = $user->date_of_birth;
        $this->profile_image = $user->profile_image;
         // 🛠 Add these two lines:
        $this->phone = $user->phone;
        $this->address = $user->address;
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],
            'phone' => ['nullable', 'string', 'max:15'],
            'address' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:male,female,other'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'profile_image' => ['nullable'],
        ]);

        // 🛠 Only replace the image path if a new image was uploaded
        if (is_object($this->profile_image)) {
            $validated['profile_image'] = $this->profile_image->store('profile_images', 'public');
        } else {
            // 🛠 Use the existing image from current user model
            $validated['profile_image'] = $user->profile_image;
        }

        if ($user->email !== $validated['email']) {
            $user->email_verified_at = null;
        }

        $user->fill($validated)->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your basic profile information')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
             <!-- Profile Image Upload with Preview and Full-Size Popup -->
            <div x-data="{ showModal: false }">
                <label for="profile_image" class="block text-sm font-medium text-zinc-800 dark:text-gray-200">{{ __('Profile Image') }}</label>
                <input type="file" wire:model="profile_image" id="profile_image" class="mt-1 block w-full" accept="image/*" />

                @if ($profile_image)
                    @if (is_string($profile_image))
                        <!-- Existing image from DB -->
                        <img src="{{ asset('storage/' . $profile_image) }}"
                            @click="showModal = true"
                            class="mt-3 h-20 w-20 rounded-full object-cover cursor-pointer hover:opacity-90 transition"
                            alt="Profile Image Preview" />
                    @else
                        <!-- New image preview -->
                        <img src="{{ $profile_image->temporaryUrl() }}"
                            @click="showModal = true"
                            class="mt-3 h-20 w-20 rounded-full object-cover cursor-pointer hover:opacity-90 transition"
                            alt="Profile Image Preview" />
                    @endif
                @endif

                <!-- Modal for full-size image -->
                <div
                    x-show="showModal"
                    x-transition
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70"
                    x-cloak
                    @click.away="showModal = false"
                >
                    <div class="relative">
                        <button
                            class="absolute top-0 right-0 m-2 text-white text-2xl"
                            @click="showModal = false"
                            title="Close"
                        >&times;</button>

                        @if ($profile_image)
                            @if (is_string($profile_image))
                                <img src="{{ asset('storage/' . $profile_image) }}" class="max-h-[90vh] max-w-[90vw] rounded shadow-xl" />
                            @else
                                <img src="{{ $profile_image->temporaryUrl() }}" class="max-h-[90vh] max-w-[90vw] rounded shadow-xl" />
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}
                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>
            <div>
                <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Phone</label>
                <div class="flex rounded-md shadow-sm">
                    <span class="inline-flex items-center px-3 rounded-md border border-r-0 border-zinc-300 dark:border-zinc-600 bg-gray-50 text-gray-500 dark:bg-zinc-700 dark:text-gray-200 text-sm">
                        +41
                    </span>
                    <flux:input
                        wire:model="phone"
                        id="phone"
                        type="text"
                    />
                </div>
            </div>

            <div class="mt-6">
                <flux:input wire:model="address" :label="__('Address')" type="text" />
            </div>

            <!-- Gender Selection -->
            <!-- User Gender -->
            <div class="mt-6">
                <flux:radio.group wire:model="gender" label="Gender" variant="segmented">
                <flux:radio value="male" label="Male" />
                <flux:radio value="female" label="Female" />
                <flux:radio value="other" label="Other" />
                </flux:radio.group>
            </div>

            <!-- Date of Birth -->
            <div class="my-6">
                <flux:input wire:model="date_of_birth" :label="__('Date of Birth')" type="date" max="{{ now()->toDateString() }}" />
            </div>
            
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>
                <x-action-message class="me-3" on="profile-updated">{{ __('Saved.') }}</x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>

