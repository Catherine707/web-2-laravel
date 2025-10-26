<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit.prevent="login" class="flex flex-col gap-6">
    <flux:input
        wire:model="email"
        :label="__('Email address')"
        type="email"
        required
        autofocus
        autocomplete="email"
        placeholder="email@example.com"
        :error="$errors->first('email')"
    />

    <div class="relative">
        <flux:input
            wire:model="password"
            :label="__('Password')"
            type="password"
            required
            autocomplete="current-password"
            :placeholder="__('Password')"
            viewable
            :error="$errors->first('password')"
        />

        @if (Route::has('password.request'))
            <flux:link class="absolute end-0 top-0 text-sm" :href="route('password.request')" wire:navigate>
                {{ __('Forgot your password?') }}
            </flux:link>
        @endif
    </div>

    <div class="flex items-center justify-end">
        <flux:button type="submit" class="w-full">
            {{ __('Log in') }}
        </flux:button>
    </div>
</form>



