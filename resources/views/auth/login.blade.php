@extends('layouts.guest')

@section('title', 'Login - Sistem Inventaris Desa')

{{-- Tambahkan link ke stylesheet baru di dalam section head layout guest Anda --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login-style.css') }}">
@endpush

@section('content')
    <div class="login-card">
        <div class="login-header">
            {{-- Logo ditambahkan di sini --}}
            <img src="{{ asset('img/logo.JPG') }}" alt="Logo Desa" class="logo-img">
            <h4><i class="fas fa-boxes me-2"></i> INVENTARIS DESA</h4>
            <p class="mb-0 mt-2">Silakan masuk dengan akun Anda</p>
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

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-floating mb-3">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                        placeholder="name@example.com" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    <label for="email"><i class="fas fa-envelope me-2"></i>Alamat Email</label>
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-floating mb-4">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password" placeholder="Password" required>
                    <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Ingat Saya
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-decoration-none forgot-password">
                            Lupa Password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
                    <i class="fas fa-sign-in-alt me-2"></i> MASUK
                </button>
            </form>
        </div>

        <div class="login-footer text-center p-3">
            <p class="mb-0 text-muted small">
                &copy; {{ date('Y') }} Sistem Inventaris Desa Karangduren. All rights reserved.
            </p>
        </div>
    </div>
@endsection