@extends('layouts.main')

@section('title', 'Settings')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-cog mr-2"></i>Settings</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success shadow-sm">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-white border-0 pt-4 pb-0">
            <h5 class="mb-0 font-weight-bold text-primary">
                <i class="fas fa-user mr-2"></i> Informasi Pengguna
            </h5>
        </div>

        <div class="card-body bg-light p-4 rounded-bottom">

            @php
                $id = Auth::user()->id;
            @endphp

            <form action="{{ route('settings.update', $id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="form-group mb-3">
                    <label class="font-weight-semibold">
                        <i class="fas fa-user mr-2 text-primary"></i> Nama Lengkap
                    </label>
                    <input type="text" name="name" class="form-control shadow-sm"
                        value="{{ old('name', Auth::user()->name) }}" required>

                    @error('name')
                        <small class="text-danger d-block mt-1"><i
                                class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</small>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group mb-3">
                    <label class="font-weight-semibold">
                        <i class="fas fa-envelope mr-2 text-primary"></i> Email
                    </label>
                    <input type="email" name="email" class="form-control shadow-sm"
                        value="{{ old('email', Auth::user()->email) }}" required>

                    @error('email')
                        <small class="text-danger d-block mt-1"><i
                                class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</small>
                    @enderror
                </div>

                <!-- WhatsApp -->
                <div class="form-group mb-3">
                    <label class="font-weight-semibold">
                        <i class="fab fa-whatsapp mr-2 text-success"></i> Nomor WhatsApp
                    </label>
                    <input type="text" name="whatsapp_number" class="form-control shadow-sm"
                        value="{{ old('whatsapp_number', Auth::user()->whatsapp_number) }}"
                        placeholder="Contoh: 6281234567890">

                    <small class="text-muted">Gunakan format internasional (62...), tanpa tanda +</small>

                    @error('whatsapp_number')
                        <small class="text-danger d-block mt-1"><i
                                class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</small>
                    @enderror
                </div>

                <hr class="my-4">

                <h5 class="font-weight-bold text-primary mb-3">
                    <i class="fas fa-lock mr-2"></i> Keamanan Akun
                </h5>

                <!-- Password -->
                <div class="form-group mb-3">
                    <label class="font-weight-semibold">
                        <i class="fas fa-lock mr-2 text-primary"></i> Password Baru
                    </label>
                    <input type="password" name="password" class="form-control shadow-sm"
                        placeholder="Kosongkan jika tidak ingin mengubah password">

                    @error('password')
                        <small class="text-danger d-block mt-1"><i
                                class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</small>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group mb-4">
                    <label class="font-weight-semibold">
                        <i class="fas fa-key mr-2 text-primary"></i> Konfirmasi Password Baru
                    </label>
                    <input type="password" name="password_confirmation" class="form-control shadow-sm"
                        placeholder="Ulangi password baru">
                </div>

                <button type="submit" class="btn btn-primary shadow-sm px-4 py-2">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>

            </form>
        </div>
    </div>

@endsection
