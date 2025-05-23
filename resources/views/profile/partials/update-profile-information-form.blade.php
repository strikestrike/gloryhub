<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('pages.profile_information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("pages.update_profile_info") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="mb-3">
            <x-input-label for="avatar" class="form-label" :value="__('Avatar')" />
            <x-text-input
                id="avatar"
                name="avatar"
                type="file"
                class="form-control"
                accept="image/*" />
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />

            @if ($user->avatar)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="rounded-circle" width="100">
            </div>
            @endif
        </div>

        <div class="mb-3">
            <x-input-label for="name" class="form-label" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="form-control" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="mb-3">
            <x-input-label for="email" class="form-label" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="form-control" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div>
                <p class="text-sm mt-2 text-gray-800">
                    {{ __('pages.email_unverified') }}

                    <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('pages.resend_verification') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                <p class="mt-2 font-medium text-sm text-green-600">
                    {{ __('pages.verification_link_sent') }}
                </p>
                @endif
            </div>
            @endif
        </div>

        <div class="flex items-center pb-4">
            <button type="submit" class="btn btn-primary btn-sm mb-3">{{ __('pages.save') }}</button>

            @if (session('status') === 'profile-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600">{{ __('Saved') }}</p>
            @endif
        </div>
    </form>
</section>