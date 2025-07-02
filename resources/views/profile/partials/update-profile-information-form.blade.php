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
                             alt="Profile Photo Preview"
                             class="w-100 border-radius-lg shadow-sm">
                    </div>
                    <div>
                        <input class="form-control" type="file" name="profile_photo" id="profile_photo"
                               onchange="document.getElementById('photo-preview').src = window.URL.createObjectURL(this.files[0])">
                        <small class="text-muted">Pilih gambar baru untuk mengganti foto profil.</small>
                    </div>
                </div>
            </div>

            {{-- Nama Lengkap --}}
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" id="name" name="name" class="form-control form-control-minimal @error('name') is-invalid @enderror"
                       value="{{ old('name', $user->name) }}" required autofocus>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Username --}}
            <div class="col-md-6 mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control form-control-minimal @error('username') is-invalid @enderror"
                       value="{{ old('username', $user->username) }}" required>
                @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Alamat Email --}}
            <div class="col-md-12 mb-3">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" id="email" name="email" class="form-control form-control-minimal @error('email') is-invalid @enderror"
                       value="{{ old('email', $user->email) }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Divisi (Kondisional) --}}
            <div class="col-md-6 mb-3">
                <label for="division" class="form-label">Divisi</label>
                @if (Auth::user()->role && Auth::user()->role->slug === 'admin')
                    <input type="text" id="division" name="division" class="form-control form-control-minimal @error('division') is-invalid @enderror"
                           value="{{ old('division', $user->division) }}">
                    @error('division') <div class="invalid-feedback">{{ $message }}</div> @enderror
                @else
                    <input type="text" id="division" name="division" class="form-control form-control-minimal"
                           value="{{ $user->division }}" readonly>
                    <small class="form-text text-muted">Hanya Admin yang dapat mengubah divisi.</small>
                @endif
            </div>

            {{-- Nomor Telepon --}}
            <div class="col-md-6 mb-3">
                <label for="phone_number" class="form-label">Nomor Telepon</label>
                <input type="tel" id="phone_number" name="phone_number" class="form-control form-control-minimal @error('phone_number') is-invalid @enderror"
                       value="{{ old('phone_number', $user->phone_number) }}">
                @error('phone_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            {{-- Status Akun (Kondisional) --}}
            <div class="col-12 mb-4">
                <label class="form-label">Status Akun</label>
                @if (Auth::user()->role && Auth::user()->role->slug === 'admin' && Auth::id() !== $user->id)
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                            @checked(old('is_active', $user->is_active))>
                        <label class="form-check-label" for="is_active">Aktif</label>
                        <small class="d-block text-muted">Nonaktifkan untuk menonaktifkan akun pengguna ini.</small>
                    </div>
                @else
                    <div>
                        @if ($user->is_active)
                            <span class="badge badge-sm bg-gradient-success">Aktif</span>
                        @else
                            <span class="badge badge-sm bg-gradient-secondary">Tidak Aktif</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="d-flex align-items-center gap-4">
            <button type="submit" class="btn bg-gradient-dark">Simpan Perubahan</button>
            @if (session('status') === 'profile-updated')
                <div id="save-status" class="text-success small">
                    Berhasil disimpan.
                </div>
            @endif
        </div>
    </form>
    
    {{-- Form tersembunyi untuk kirim email verifikasi --}}
    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
        <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-none">
            @csrf
        </form>
    @endif

    {{-- Script untuk menghilangkan pesan "Saved." setelah beberapa detik --}}
    <script>
        const saveStatus = document.getElementById('save-status');
        if (saveStatus) {
            setTimeout(() => {
                saveStatus.style.display = 'none';
            }, 3000);
        }
    </script>
</section>
