<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search"
        id="globalSearchForm">
        <div class="input-group">
            <input type="text" id="globalSearchInput" class="form-control bg-light border-0 small"
                placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2" autocomplete="off" />
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>

    <div id="searchResults" class="position-absolute bg-white border rounded shadow"
        style="z-index: 1000; display:none; width:420px; max-height: 100px; margin-top:150px; overflow-y:auto;">
    </div>

    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search" id="globalSearchFormMobile">
                    <div class="input-group">
                        <input type="text" id="globalSearchInputMobile" class="form-control bg-light border-0 small"
                            placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2"
                            autocomplete="off" />
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div id="searchResultsMobile" class="position-absolute bg-white border rounded shadow"
                style="z-index: 1000; display:none; width:350px; max-height: 100px; margin-left:-250px; margin-top:80px; overflow-y:auto;">
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                    @if (Auth::check())
                        {{ Auth::user()->name }}
                    @endif
                </span>
                <img class="img-profile rounded-circle" src="{{ asset('sbadmin/img/undraw_profile.svg') }}">
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                <a class="dropdown-item" data-toggle="modal" data-target="#profilModal"><i
                        class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>Profile</a>
                <a class="dropdown-item" href="{{ route('settings.index') }}"><i
                        class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>Settings</a>
                <div class="dropdown-divider"></div>

                <form method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <button class="dropdown-item" type="submit"><i
                            class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</button>
                </form>

            </div>
        </li>

    </ul>

    <!-- Modal Profile -->
    <div class="modal fade" id="profilModal" tabindex="-1" aria-labelledby="profilModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0">

                {{-- HEADER --}}
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title d-flex align-items-center" id="profilModalLabel">
                        <i class="fas fa-user-circle mr-2"></i> Profil Pengguna
                    </h5>
                    <button class="close text-white" type="button" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                {{-- BODY --}}
                <div class="modal-body">

                    {{-- ALERT JIKA WhatsApp KOSONG --}}
                    @if (Auth::user()->whatsapp_number == null)
                        <div class="alert alert-warning d-flex align-items-start">
                            <i class="fas fa-exclamation-triangle fa-lg mr-2 mt-1"></i>
                            <div>
                                <strong>Nomor WhatsApp belum diisi!</strong><br>
                                Masukkan nomor WhatsApp untuk menerima notifikasi stok kritis.
                            </div>
                        </div>
                    @endif

                    {{-- DATA PROFILE --}}
                    <div class="card border-0 shadow-sm">
                        <ul class="list-group list-group-flush">

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">Nama</span>
                                <strong>{{ Auth::user()->name }}</strong>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">Email</span>
                                <strong>{{ Auth::user()->email }}</strong>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">Nomor WhatsApp</span>

                                @if (Auth::user()->whatsapp_number)
                                    <span class="badge badge-success px-3 py-2">
                                        <i class="fab fa-whatsapp mr-1"></i>
                                        {{ Auth::user()->whatsapp_number }}
                                    </span>
                                @else
                                    <span class="badge badge-secondary px-3 py-2">Belum diisi</span>
                                @endif
                            </li>

                        </ul>
                    </div>

                    {{-- INFO TAMBAHAN --}}
                    @if (Auth::user()->whatsapp_number == null)
                        <p class="mt-3 mb-0 text-muted">
                            Hubungkan WhatsApp Anda untuk mendapatkan pemberitahuan otomatis.
                        </p>
                    @endif

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer bg-light">

                    @if (Auth::user()->whatsapp_number == null)
                        <a href="{{ route('settings.index') }}" class="btn btn-primary">
                            <i class="fas fa-edit mr-1"></i> Isi Nomor WhatsApp
                        </a>
                    @endif

                    <button class="btn btn-secondary" type="button" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Tutup
                    </button>
                </div>

            </div>
        </div>
    </div>


</nav>
