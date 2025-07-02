<section>
    <header>
        {{-- Kita bisa menyederhanakan header ini agar lebih ringkas --}}
        <p class="text-muted">
            {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')

        <div class="row">
            {{-- Password Saat Ini --}}
            <div class="col-12 mb-3">
                <label for="update_password_current_password" class="form-label">{{ __('Password Saat Ini') }}</label>
                <input type="password" id="update_password_current_password" name="current_password" 
                       class="form-control form-control-minimal @error('current_password', 'updatePassword') is-invalid @enderror" 
                       autocomplete="current-password">
                @error('current_password', 'updatePassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password Baru --}}
            <div class="col-12 mb-3">
                <label for="update_password_password" class="form-label">{{ __('Password Baru') }}</label>
                <input type="password" id="update_password_password" name="password" 
                       class="form-control form-control-minimal @error('password', 'updatePassword') is-invalid @enderror" 
                       autocomplete="new-password">
                @error('password', 'updatePassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Konfirmasi Password Baru --}}
            <div class="col-12 mb-3">
                <label for="update_password_password_confirmation" class="form-label">{{ __('Konfirmasi Password') }}</label>
                <input type="password" id="update_password_password_confirmation" name="password_confirmation" 
                       class="form-control form-control-minimal @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                       autocomplete="new-password">
                @error('password_confirmation', 'updatePassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="d-flex align-items-center gap-4 mt-3">
            <button type="submit" class="btn bg-gradient-dark">Simpan</button>

            @if (session('status') === 'password-updated')
                <div id="password-save-status" class="text-success small">
                    Berhasil disimpan.
                </div>
            @endif
        </div>
    </form>
    
    {{-- Script untuk menghilangkan pesan "Saved." setelah beberapa detik --}}
    <script>
        const passwordSaveStatus = document.getElementById('password-save-status');
        if (passwordSaveStatus) {
            setTimeout(() => {
                passwordSaveStatus.style.display = 'none';
            }, 3000);
        }
    </script>
</section>
