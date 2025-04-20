<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('pages.delete_account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('pages.final_delete_account_warning') }}
        </p>
    </header>

    <button class="btn btn-danger"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">{{ __('pages.delete_account') }}</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('pages.confirm_delete_account') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('pages.delete_account_warning') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('pages.password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('pages.password') }}" />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <button class="btn btn-secondary" x-on:click="$dispatch('close')">
                    {{ __('pages.cancel') }}
                </button>

                <button class="btn btn-danger">
                    {{ __('pages.delete_account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>