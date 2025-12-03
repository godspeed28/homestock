@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
    <div class="text-center">
        <h1 class="h4 text-gray-900 mb-2">Forgot Your Password?</h1>
        <p class="mb-4">We get it, stuff happens. Just enter your email address below
            and we'll send you a link to reset your password!</p>
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

    <form class="user" method="POST" action="{{ route('auth.forgotpass.submit') }}">
        @csrf
        <div class="form-group">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror form-control-user"
                id="email" aria-describedby="emailHelp" placeholder="Enter Email Address..."
                value="{{ old('email') }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button class="btn btn-primary btn-user btn-block" type="submit">Reset Password</button>
    </form>

    <hr>

    <div class="text-center">
        <a class="small" href="{{ route('auth.register') }}">Create an Account!</a>
    </div>
    <div class="text-center">
        <a class="small" href="{{ route('login') }}">Already have an account? Login!</a>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if ($errors->has('email'))
                const emailField = document.getElementById('email');
                if (emailField) {
                    emailField.focus();
                }
            @endif
        });
    </script>
@endsection
