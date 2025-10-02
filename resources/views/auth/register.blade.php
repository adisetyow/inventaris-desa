@extends('layouts.guest')

@section('title', 'Register - Sistem Inventaris Desa')

{{-- Menambahkan link ke stylesheet yang sama dengan login --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login-style.css') }}">
@endpush

@section('content')
    <div class="login-card">
        <div class="login-header">
            {{-- Menggunakan header yang konsisten dengan login --}}
            <img src="{{ asset('img/logo.JPG') }}" alt="Logo Desa" class="logo-img">
            <h4><i class="fas fa-user-plus me-2"></i> REGISTRASI AKUN</h4>
            <p class="mb-0 mt-2">Buat akun baru untuk mengakses sistem</p>
        </div>

        <div class="login-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Menggunakan form-floating seperti di login --}}
                <div class="form-floating mb-3">
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
                        placeholder="John Doe" value="{{ old('nama') }}" required autocomplete="name" autofocus>
                    <label for="nama"><i class="fas fa-user me-2"></i>Nama Lengkap</label>
                    @error('nama')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                        placeholder="name@example.com" value="{{ old('email') }}" required autocomplete="email">
                    <label for="email"><i class="fas fa-envelope me-2"></i>Alamat Email</label>
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password" placeholder="Password" required autocomplete="new-password">
                    <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-floating mb-4">
                    <input type="password" class="form-control" id="password-confirm" name="password_confirmation"
                        placeholder="Konfirmasi Password" required autocomplete="new-password">
                    <label for="password-confirm"><i class="fas fa-check-circle me-2"></i>Konfirmasi Password</label>
                </div>

                {{-- Tombol submit yang konsisten dengan login --}}
                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
                    <i class="fas fa-user-plus me-2"></i> DAFTAR
                </button>
            </form>

            <div class="text-center mt-4">
                <p>Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none">Login di sini</a></p>
            </div>
        </div>

        <div class="login-footer text-center p-3">
            <p class="mb-0 text-muted small">
                &copy; {{ date('Y') }} Sistem Inventaris Desa Karangduren. All rights reserved.
            </p>
        </div>
    </div>
@endsection