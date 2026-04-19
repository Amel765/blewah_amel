@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="text-center mb-4">
    <h3 class="fw-bold">Login</h3>
    <p class="text-muted">Masuk ke sistem SPK Blewah</p>
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

<form action="{{ route('login') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" class="form-control" id="password" required>
    </div>
    <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>
    <div class="text-center">
        <span>Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none">Daftar</a></span>
    </div>
</form>
@endsection
