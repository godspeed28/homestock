<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">HomeStock</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ Request::routeIs('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        Inventory Management
    </div>

    <li class="nav-item {{ Request::routeIs('category.*') || Request::routeIs('items.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('items.index') }}">
            <i class="fas fa-fw fa-box"></i>
            <span>Item</span>
        </a>

    </li>

    <li class="nav-item {{ Request::routeIs('usage.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('usage.index') }}">
            <i class="fas fa-fw fa-hand-holding"></i>
            <span>Usage</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">Reports</div>

    <li class="nav-item {{ Request::routeIs('history.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('history.index') }}">
            <i class="fas fa-fw fa-history"></i>
            <span>History</span>
        </a>
    </li>

    <li class="nav-item">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn mb-0 text-danger" style="font-size: 15px;" type="submit"><i
                    class="text-danger fas fa-fw fa-sign-out-alt"></i>
                <span>Logout</span></button>
        </form>
    </li>

    <hr class="sidebar-divider">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

    @php
        $user = Auth::user();
    @endphp

    @if (empty($user->whatsapp_number))
        <div class="sidebar-card d-none d-lg-flex">
            <img class="sidebar-card-illustration mb-2" src="{{ asset('img/whatsapp.svg') }}" alt="...">
            <p class="text-center mb-2"><strong>Nomor WhatsApp Kosong! </strong>Dapatkan notifikasi stok barang kritis.
            </p>
            <a class="btn btn-success btn-sm" href="{{ route('settings.index') }}">Tambah Nomor WA</a>
        </div>
    @endif


</ul>
