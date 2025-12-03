@extends('layouts.main')

@section('title', 'Pengaturan Akun')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-cog mr-2"></i> Settings
        </h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <h5 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-user-edit mr-2"></i> Informasi Profil
                    </h5>
                </div>

                <div class="card-body p-4">

                    {{-- HAPUS parameter id dari route! --}}
                    <form action="{{ route('settings.update') }}" method="POST" id="settingsForm">
                        @csrf
                        @method('PUT')

                        @php
                            $user = Auth::user();
                        @endphp

                        <!-- Name -->
                        <div class="form-group">
                            <label class="font-weight-semibold">
                                <i class="fas fa-user mr-2 text-primary"></i> Nama Lengkap
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name"
                                class="form-control shadow-sm @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}" required autocomplete="name">

                            @error('name')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label class="font-weight-semibold">
                                <i class="fas fa-envelope mr-2 text-primary"></i> Email
                                <span class="text-danger">*</span>
                            </label>
                            <input type="email" name="email"
                                class="form-control shadow-sm @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" required autocomplete="email">

                            @error('email')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- WhatsApp dengan input mask -->
                        <div class="form-group">
                            <label class="font-weight-semibold">
                                <i class="fab fa-whatsapp mr-2 text-success"></i> Nomor WhatsApp
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white">+62</span>
                                </div>
                                <input type="tel" name="whatsapp_number"
                                    class="form-control shadow-sm @error('whatsapp_number') is-invalid @enderror"
                                    value="{{ old('whatsapp_number', $user->whatsapp_number ? substr($user->whatsapp_number, 2) : '') }}"
                                    placeholder="81234567890" id="whatsappInput" maxlength="13">
                            </div>
                            <small class="form-text text-muted">
                                Contoh: 81234567890 (tanpa +62)
                            </small>

                            @error('whatsapp_number')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <h5 class="font-weight-bold text-primary mb-3">
                            <i class="fas fa-shield-alt mr-2"></i> Keamanan Akun
                        </h5>

                        <!-- Current Password -->
                        <div class="form-group">
                            <label class="font-weight-semibold">
                                <i class="fas fa-lock mr-2 text-warning"></i> Password Saat Ini
                                <span class="text-danger">*</span>
                            </label>
                            <input type="password" name="current_password"
                                class="form-control shadow-sm @error('current_password') is-invalid @enderror"
                                placeholder="Masukkan password saat ini untuk konfirmasi" required
                                autocomplete="current-password">

                            @error('current_password')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="form-group">
                            <label class="font-weight-semibold">
                                <i class="fas fa-lock mr-2 text-primary"></i> Password Baru
                            </label>
                            <input type="password" name="password"
                                class="form-control shadow-sm @error('password') is-invalid @enderror"
                                placeholder="Kosongkan jika tidak ingin mengubah" autocomplete="new-password"
                                id="newPassword">

                            <small class="form-text text-muted">
                                Minimal 8 karakter, mengandung huruf dan angka
                            </small>

                            @error('password')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label class="font-weight-semibold">
                                <i class="fas fa-key mr-2 text-primary"></i> Konfirmasi Password Baru
                            </label>
                            <input type="password" name="password_confirmation" class="form-control shadow-sm"
                                placeholder="Ulangi password baru" autocomplete="new-password" id="confirmPassword">
                        </div>

                        <!-- Password Strength Meter -->
                        <div class="mb-4" id="passwordStrength" style="display: none;">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                            </div>
                            <small class="form-text" id="passwordStrengthText"></small>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali
                            </a>

                            <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- SIDEBAR INFO --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <h5 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-2"></i> Informasi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info border-0">
                        <h6 class="font-weight-bold">
                            <i class="fas fa-lightbulb mr-2"></i> Tips Keamanan:
                        </h6>
                        <ul class="mb-0 pl-3">
                            <li>Gunakan password yang kuat</li>
                            <li>Jangan bagikan password Anda</li>
                            <li>Pastikan email valid untuk reset password</li>
                            <li>Verifikasi nomor WhatsApp untuk notifikasi</li>
                        </ul>
                    </div>

                    <div class="mt-3">
                        <h6 class="font-weight-bold text-muted">
                            <i class="fas fa-history mr-2"></i> Terakhir Diperbarui:
                        </h6>
                        <p class="mb-0">
                            {{ $user->updated_at->translatedFormat('d F Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        // WhatsApp input mask (hanya angka)
        document.getElementById('whatsappInput').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^\d]/g, '');
        });

        // Password strength checker
        document.getElementById('newPassword').addEventListener('input', function(e) {
            const password = e.target.value;
            const strengthMeter = document.getElementById('passwordStrength');
            const progressBar = strengthMeter.querySelector('.progress-bar');
            const strengthText = document.getElementById('passwordStrengthText');

            if (password.length === 0) {
                strengthMeter.style.display = 'none';
                return;
            }

            strengthMeter.style.display = 'block';

            // Calculate strength
            let strength = 0;
            if (password.length >= 8) strength += 25;
            if (/[A-Z]/.test(password)) strength += 25;
            if (/[a-z]/.test(password)) strength += 25;
            if (/[0-9]/.test(password)) strength += 25;

            // Update UI
            progressBar.style.width = strength + '%';

            // Set color and text
            if (strength < 50) {
                progressBar.className = 'progress-bar bg-danger';
                strengthText.textContent = 'Password lemah';
                strengthText.className = 'form-text text-danger';
            } else if (strength < 75) {
                progressBar.className = 'progress-bar bg-warning';
                strengthText.textContent = 'Password cukup';
                strengthText.className = 'form-text text-warning';
            } else {
                progressBar.className = 'progress-bar bg-success';
                strengthText.textContent = 'Password kuat';
                strengthText.className = 'form-text text-success';
                progressBar.style.width = 100 + '%';
            }
        });

        // Form validation dengan SweetAlert
        document.getElementById('settingsForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah submit default

            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const submitBtn = document.getElementById('submitBtn');

            // Simpan referensi form
            const form = this;

            // Disable button to prevent double submit
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';

            // Check password match
            if (newPassword && newPassword !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Password baru dan konfirmasi password tidak cocok!',
                    confirmButtonText: 'Coba Lagi',
                    confirmButtonColor: '#dc3545',
                    timer: 3000,
                    timerProgressBar: true
                }).then(() => {
                    // Reset button state
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Simpan Perubahan';

                    // Focus ke field password
                    document.getElementById('confirmPassword').focus();
                });
                return;
            }

            // Validasi berhasil, tampilkan konfirmasi
            Swal.fire({
                title: 'Simpan Perubahan?',
                text: "Apakah Anda yakin ingin menyimpan perubahan pengaturan?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading
                    Swal.fire({
                        title: 'Menyimpan...',
                        text: 'Harap tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit form setelah delay untuk simulasi
                    setTimeout(() => {
                        // Jika menggunakan AJAX, tambahkan kode AJAX di sini
                        // Untuk form submit biasa:
                        form.submit();

                        // Atau jika ingin tampilkan sukses dulu:
                        /*
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Pengaturan berhasil disimpan',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#28a745',
                            timer: 2000,
                            timerProgressBar: true
                        }).then(() => {
                            form.submit();
                        });
                        */
                    }, 1500);
                } else {
                    // Reset button state jika dibatalkan
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Simpan Perubahan';
                }
            });
        });

        // Tambahkan fungsi untuk menampilkan SweetAlert saat ada perubahan berhasil disimpan
        // (Tambahkan ini jika Anda ingin menampilkan notifikasi setelah form berhasil submit)
        function showSuccessAlert() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('saved') === 'true') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Pengaturan berhasil diperbarui',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#28a745',
                    timer: 3000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false
                });
            }
        }

        // Panggil fungsi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            showSuccessAlert();
        });
    </script>
@endsection
