@extends('layouts.auth')

@section('title', 'Daftar')

@section('content')
<div class="text-center mb-4">
    <h3 class="fw-bold">Daftar</h3>
    <p class="text-muted">Buat akun sebagai User</p>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('register') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Nama Lengkap</label>
        <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" class="form-control" id="password" required>
    </div>
    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
    </div>
    <button type="submit" class="btn btn-primary w-100 mb-3">Daftar</button>
    <div class="text-center">
        <span>Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none">Login</a></span>
    </div>
</form>
@endsection
