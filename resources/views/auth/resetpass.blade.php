@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')

    <div class="text-center">
        <h1 class="h4 text-gray-900 mb-2">Reset Password </h1>
        <p class="mb-4">Enter your new password!</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form id="formAuthentication" class="user" action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <input type="email" class="form-control @error('email') is-invalid @enderror form-control-user" id="email"
                name="email" placeholder="Enter your email" value="{{ $email ?? old('email') }}" readonly />
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group row">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <input type="password" id="password"
                    class="form-control @error('password') is-invalid @enderror form-control-user" name="password"
                    placeholder="New Password" />
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-sm-6">
                <input type="password" id="password_confirmation" class="form-control form-control-user"
                    name="password_confirmation" placeholder="Confirm Password" />
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-user btn-block">
            Reset Password
        </button>
    </form>
    <hr>
    <div class="text-center">
        <a href="{{ route('login') }}" class="small">Back to login</a>
    </div>
@endsection
