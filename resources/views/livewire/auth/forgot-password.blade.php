 <div class="flex flex-col gap-6">
    <x-auth-header :title="__('Forgot password')" :description="__('Enter your email to receive a password reset link')" />

    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">

        <flux:input
            wire:model="email"
            :label="__('Email Address')"
            type="email"
            required
            autofocus
            placeholder="email@example.com"
            viewable
        />

        <flux:button variant="primary" type="submit" class="w-full">{{ __('Email password reset link') }}</flux:button>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
        {{ __('Or, return to') }}
        <flux:link :href="route('login')" wire:navigate>{{ __('log in') }}</flux:link>
    </div>
</div>
