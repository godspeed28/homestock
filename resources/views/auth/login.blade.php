@extends('layouts.app')

@section('title', 'Login')

@section('content')

    <div class="text-center">
        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
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

    <form id="formAuthentication" class="user" action="{{ route('auth.login.submit') }}" method="POST">
        @csrf

        <div class="form-group">
            <input type="email" id="email" name="email"
                class="form-control @error('email') is-invalid @enderror form-control-user" aria-describedby="emailHelp"
                placeholder="Enter Email Address..." autofocus value="{{ old('email') }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <input type="password" id="password" name="password"
                class="form-control @error('password') is-invalid @enderror form-control-user" placeholder="Password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <div class="custom-control custom-checkbox small">
                <input type="checkbox" class="custom-control-input" id="remember" name="remember"
                    {{ old('remember') ? 'checked' : '' }}>
                <label class="custom-control-label" for="remember">Remember
                    Me</label>
            </div>
        </div>
        <button class="btn btn-primary btn-user btn-block" type="submit">Login</button>
    </form>
    <hr>
    <div class="text-center">
        <a class="small" href="{{ route('auth.forgotpass') }}">Forgot Password?</a>
    </div>
    <div class="text-center">
        <a class="small" href="{{ route('auth.register') }}">Create an Account!</a>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Loading state pada form submit
            const form = document.getElementById('formAuthentication');
            const submitBtn = document.getElementById('submit-btn');
            const spinner = document.getElementById('spinner');
            const btnText = document.getElementById('btn-text');

            if (form && submitBtn) {
                form.addEventListener('submit', function() {
                    // Disable button dan show spinner
                    submitBtn.disabled = true;
                    spinner.classList.remove('d-none');
                    btnText.textContent = 'Signing in...';
                });
            }

            // Auto-focus on login field jika ada error
            @if ($errors->has('email') || $errors->has('password'))
                const loginField = document.getElementById('email');
                if (loginField) {
                    loginField.focus();
                }
            @endif
        });
    </script>
@endsection
