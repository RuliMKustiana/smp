<?php

namespace App\Http\Controllers;

// Hapus 'use App\Http\Requests\ProfileUpdateRequest;'
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage; // <-- Tambahkan untuk mengelola file
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan form untuk mengedit profil pengguna.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Memperbarui informasi profil pengguna.
     * Method ini sudah dirombak total untuk menangani field baru dan upload foto.
     */
    public function update(Request $request): RedirectResponse
    {
        // Aturan validasi untuk semua field baru Anda
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,'.$request->user()->id],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$request->user()->id],
            'division' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], // Validasi untuk gambar
            'is_active' => ['sometimes', 'boolean'],
        ]);

        // 1. Handle upload foto profil
        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama dari storage jika ada
            if ($request->user()->profile_photo_path) {
                Storage::disk('public')->delete($request->user()->profile_photo_path);
            }
            // Simpan foto baru di folder 'public/profile-photos'
            $validated['profile_photo_path'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        // 2. Handle status aktif (checkbox)
        // Jika checkbox dicentang, nilainya '1'. Jika tidak, tidak ada nilai.
        $validated['is_active'] = $request->has('is_active');

        // 3. Cek jika email diubah, reset verifikasi email
        if ($request->user()->email !== $validated['email']) {
            $validated['email_verified_at'] = null;
        }

        // 4. Update data user
        $request->user()->update($validated);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Menghapus akun pengguna.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
