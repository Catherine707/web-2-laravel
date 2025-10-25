<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

   <form wire:submit.prevent="register" class="flex flex-col gap-6">
    <flux:input wire:model="name"  :label="__('Name')" type="text" required autofocus autocomplete="name"/>
    <flux:input wire:model="email" :label="__('Email address')" type="email" required autocomplete="email"/>
    <flux:input wire:model="password" :label="__('Password')" type="password" required autocomplete="new-password" viewable/>
    <flux:input wire:model="password_confirmation" :label="__('Confirm password')" type="password" required autocomplete="new-password" viewable/>

    <flux:button type="submit" variant="primary" class="w-full">
        {{ __('Create account') }}
    </flux:button>
</form>



    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        <span>{{ __('Already have an account?') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
    </div>
</div>
