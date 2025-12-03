@extends('layouts.app')

@section('title', 'Register')

@section('content')

    <div class="text-center">
        <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
    </div>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form class="user" id="formAuthentication" action="{{ route('auth.register.submit') }}" method="POST">
        @csrf

        <div class="form-group">
            <input type="text" name="username" id="username"
                class="form-control @error('username') is-invalid @enderror form-control-user" placeholder="Name"
                value="{{ old('username') }}">
            @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <input type="email" name="email" id="email"
                class="form-control @error('email') is-invalid @enderror form-control-user" placeholder="Email Address"
                value="{{ old('email') }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group row">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <input type="password" name="password" id="password"
                    class="form-control @error('password') is-invalid @enderror form-control-user" placeholder="Password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-sm-6">
                <input type="password" class="form-control form-control-user" id="password_confirmation"
                    name="password_confirmation" placeholder="Repeat Password">
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-user btn-block">Register Account</button>
    </form>

    <hr>
    <div class="text-center">
        <a class="small" href="{{ route('login') }}">Already have an account? Login!</a>
    </div>

@endsection
