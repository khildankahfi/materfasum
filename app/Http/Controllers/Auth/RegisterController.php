<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('user.dashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'unique:users,email'],
            'phone_code'            => ['nullable', 'string', 'max:10', 'regex:/^\+\d+$/'],
            'phone'                 => ['nullable', 'string', 'max:20', 'regex:/^[1-9][0-9\s\-]*$/'],
            'address'               => ['nullable', 'string', 'max:500'],
            'password'              => ['required', 'min:8', 'confirmed'],
        ], [
            'name.required'         => 'Nama lengkap wajib diisi.',
            'email.required'        => 'Email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah terdaftar.',
            'phone.regex'           => 'Nomor HP tidak boleh diawali angka 0. Contoh: 812 3456 7890',
            'password.required'     => 'Password wajib diisi.',
            'password.min'          => 'Password minimal 8 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
        ]);

        // Combine phone_code + phone into a single full international number
        $fullPhone = null;
        if (!empty($validated['phone'])) {
            $phoneCode  = $validated['phone_code'] ?? '+62';
            $phoneNumber = preg_replace('/[\s\-]/', '', $validated['phone']);
            $fullPhone  = $phoneCode . $phoneNumber;
        }

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $fullPhone,
            'address'  => $validated['address'] ?? null,
            'password' => Hash::make($validated['password']),
            'role'     => 'user',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('user.dashboard')->with('success', 'Akun berhasil dibuat. Selamat datang, ' . $user->name . '!');
    }
}