<section>
    <form method="post" action="{{ route('profile.update') }}" class="mt-4" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="row">
            {{-- Foto Profil --}}
            <div class="col-12 mb-4">
                <label class="form-label">Photo Profile</label>
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-xl me-3">
                        <img id="photo-preview"
                            src="{{ $user->profile_photo_url ?? 'https://placehold.co/100x100/ced4da/6c757d?text=No+Photo' }}"
                            alt="Profile Photo Preview" class="w-100 border-radius-lg shadow-sm">
                    </div>
                    <div>
                        <input class="form-control" type="file" name="profile_photo" id="profile_photo"
                            onchange="document.getElementById('photo-preview').src = window.URL.createObjectURL(this.files[0])">
                        <small class="text-muted">Pilih gambar baru untuk mengganti foto profil.</small>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" id="name" name="name"
                    class="form-control form-control-minimal @error('name') is-invalid @enderror"
                    value="{{ old('name', $user->name) }}" required autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-12 mb-3">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" id="email" name="email"
                    class="form-control form-control-minimal @error('email') is-invalid @enderror"
                    value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="division" class="form-label">Divisi</label>
                @can('manage users')
                    <input type="text" id="division" name="division"
                        class="form-control form-control-minimal @error('division') is-invalid @enderror"
                        value="{{ old('division', $user->division?->name) }}">
                    @error('division')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                @else
                    <input type="text" id="division" name="division" class="form-control form-control-minimal"
                        value="{{ $user->division?->name }}" readonly>
                    <small class="form-text text-muted">Hanya Admin yang dapat mengubah divisi.</small>
                @endcan
            </div>

            <div class="col-md-6 mb-3">
                <label for="phone_number" class="form-label">Nomor Telepon</label>
                <input type="tel" id="phone_number" name="phone_number"
                    class="form-control form-control-minimal @error('phone_number') is-invalid @enderror"
                    value="{{ old('phone_number', $user->phone_number) }}">
                @error('phone_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 mb-4">
                <label class="form-label">Status Akun</label>
                @can('manage users')
                    <div class="d-flex align-items-center">
                        <div class="form-check form-switch me-3">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                @checked(old('is_active', $user->is_active))>
                            <label class="form-check-label" for="is_active">Aktif</label>
                        </div>
                    </div>
                @else
                    <div>
                        @if ($user->is_active)
                            <span class="badge badge-sm bg-gradient-success">Aktif</span>
                        @else
                            <span class="badge badge-sm bg-gradient-secondary">Tidak Aktif</span>
                        @endif
                    </div>
                @endcan
            </div>
        </div>

        <div class="d-flex align-items-center gap-4">
            <button type="submit" class="btn bg-gradient-dark">Simpan Perubahan</button>
            @if (session('status') === 'profile-updated')
                <div id="save-status" class="text-success small">
                    Berhasil disimpan.
                </div>
            @endif
        </div>
    </form>

    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
        <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-none">
            @csrf
        </form>
    @endif

    <script>
        const saveStatus = document.getElementById('save-status');
        if (saveStatus) {
            setTimeout(() => {
                saveStatus.style.display = 'none';
            }, 3000);
        }
    </script>
</section>
