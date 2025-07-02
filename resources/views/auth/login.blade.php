<x-guest-layout>
    {{-- Container utama untuk layout split screen --}}
    <div class="login-container">

        {{-- Kolom Kiri: Form Login --}}
        <div class="login-form-section">
            <div class="login-form-wrapper">

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <h2 class="form-title">Selamat Datang</h2>
                <p class="form-subtitle">
                    Silahkan masukkan Email dan Password Anda!<br>
                </p>

                <form method="POST" action="{{ route('login') }}" class="mt-4">
                    @csrf
                    <div>
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" type="email" name="email" :value="old('email')" required
                            autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="password" value="Password" />
                        <x-text-input id="password" type="password" name="password" required
                            autocomplete="current-password" value="" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    <div class="block mt-4 mb-4">
                        <label for="remember_me" class="inline-flex items-center form-check form-switch">
                            <input id="remember_me" type="checkbox" class="form-check-input" name="remember" checked>
                            <span class="ms-2 form-check-label">{{ __('Remember me') }}</span>
                        </label>
                    </div>
                    <div class="text-center">
                        <x-primary-button class="sign-in-btn">
                            {{ __('SIGN IN') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Kolom Kanan: Gambar --}}
        <div class="login-image-section">
            {{-- Gambar diatur melalui CSS --}}
        </div>

    </div>
</x-guest-layout>
