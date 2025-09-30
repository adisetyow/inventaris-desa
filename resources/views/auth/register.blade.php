@extends('layouts.guest')

@section('title', 'Register - Sistem Inventaris Desa')

@section('content')
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <img src="{{ asset('img/logo.JPG') }}" alt="Logo Desa" class="login-logo">
                <h4 class="login-title">Registrasi Akun Baru</h4>
                <p class="login-subtitle">Sistem Inventaris Aset Desa</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input id="nama" type="text" class="form-control" name="nama" value="{{ old('nama') }}" required
                        autocomplete="name" autofocus>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required
                        autocomplete="email">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" class="form-control" name="password" required
                        autocomplete="new-password">
                </div>

                <div class="mb-4">
                    <label for="password-confirm" class="form-label">Konfirmasi Password</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                        autocomplete="new-password">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-block">
                        Register
                    </button>
                </div>
            </form>
            <div class="text-center mt-4">
                <p>Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
            </div>
        </div>
    </div>
@endsection