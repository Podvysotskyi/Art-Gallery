<x-layouts::auth :title="__('Log in')">
    <div class="flex flex-col gap-6">
        <div class="flex w-full flex-col text-center">
            <flux:heading size="xl">{{ __('Log in to your account') }}</flux:heading>
            <flux:subheading>{{ __('Continue with Google to access your account') }}</flux:subheading>
        </div>

        <a href="{{ route('auth.google') }}" class="w-full">
            <flux:button variant="primary" type="button" class="w-full" data-test="google-login-button">
                {{ __('Continue with Google') }}
            </flux:button>
        </a>
    </div>
</x-layouts::auth>
