{{-- resources/views/partials/sidebar.blade.php --}}
<aside class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Sucofindo">
        </div>
        <button class="sidebar-toggle" onclick="toggleSidebar()" title="Toggle sidebar">
            <svg class="sidebar-toggle-desktop-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                 stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="6"  x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
            <svg class="sidebar-toggle-mobile-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"
                 aria-hidden="true">
                <circle cx="12" cy="5" r="2"/>
                <circle cx="12" cy="12" r="2"/>
                <circle cx="12" cy="19" r="2"/>
            </svg>
        </button>
    </div>

    <nav class="sidebar-nav">
        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
           data-tip="Dashboard">
            <span class="nav-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </span>
            <span class="nav-label">Dashboard</span>
        </a>

        <hr class="nav-divider">

        {{-- Data Stock --}}
        <a href="{{ route('data-stok.index') }}"
            class="nav-item {{ request()->routeIs('data-stok.*') ? 'active' : '' }}"
            data-tip="Data Stock">
            <span class="nav-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                </svg>
            </span>
            <span class="nav-label">Data Stock</span>
        </a>

        {{-- Barang Masuk --}}
        <a href="{{ route('barang-masuk.index') }}" class="nav-item {{ request()->routeIs('barang-masuk.*') ? 'active' : '' }}" data-tip="Barang Masuk">
            <span class="nav-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 8 16 12 12 16"/>
                    <line x1="8" y1="12" x2="16" y2="12"/>
                </svg>
            </span>
            <span class="nav-label">Barang Masuk</span>
        </a>

        {{-- Barang Keluar --}}
        <a href="{{ route('barang-keluar.index') }}" class="nav-item {{ request()->routeIs('barang-keluar.*') ? 'active' : '' }}" data-tip="Barang Keluar">
            <span class="nav-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 16 8 12 12 8"/>
                    <line x1="16" y1="12" x2="8" y2="12"/>
                </svg>
            </span>
            <span class="nav-label">Barang Keluar</span>
        </a>

        {{-- History Barang (submenu) --}}
        <div class="nav-submenu-toggle" onclick="toggleSubmenu(this)" data-tip="History Barang">
            <span class="nav-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
            </span>
            <span class="nav-label">History Barang</span>
            <svg class="arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 12 15 18 9"/>
            </svg>
        </div>
        <div class="nav-submenu">
            <a href="{{ route('history-barang', 'smo-tank') }}"
               class="{{ request()->route('unit') === 'smo-tank' ? 'active' : '' }}">Smo Tank</a>
            <a href="{{ route('history-barang', 'fire') }}"
               class="{{ request()->route('unit') === 'fire' ? 'active' : '' }}">FIRE</a>
            <a href="{{ route('history-barang', 'isps-code') }}"
               class="{{ request()->route('unit') === 'isps-code' ? 'active' : '' }}">ISPS CODE</a>
            <a href="{{ route('history-barang', 'jip') }}"
               class="{{ request()->route('unit') === 'jip' ? 'active' : '' }}">JIP</a>
            <a href="{{ route('history-barang', 'spot-order') }}"
               class="{{ request()->route('unit') === 'spot-order' ? 'active' : '' }}">Spot ORDER</a>
            <a href="{{ route('history-barang', 'aebt') }}"
               class="{{ request()->route('unit') === 'aebt' ? 'active' : '' }}">AEBT</a>
            <a href="{{ route('history-barang', 'industri') }}"
               class="{{ request()->route('unit') === 'industri' ? 'active' : '' }}">INDUSTRI</a>
            <a href="{{ route('history-barang', 'hmpm') }}"
               class="{{ request()->route('unit') === 'hmpm' ? 'active' : '' }}">HMPM</a>
        </div>

        <hr class="nav-divider">

        {{-- Manajemen Barang (admin only) --}}
   
@if(Auth::user()->role === 'admin')

<a href="{{ route('manajemen-barang') }}"
   class="nav-item {{ request()->routeIs('manajemen-barang') ? 'active' : '' }}">

    <span class="nav-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
            <line x1="12" y1="22.08" x2="12" y2="12"/>
        </svg>
    </span>

    <span class="nav-label">Manajemen Barang</span>

</a>

<a href="{{ route('laporan-stok.index') }}"
   class="nav-item {{ request()->routeIs('laporan-stok.*') ? 'active' : '' }}">

    <span class="nav-icon">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="8" y1="13" x2="16" y2="13"/>
            <line x1="8" y1="17" x2="16" y2="17"/>
        </svg>
    </span>

    <span class="nav-label">Laporan Stok</span>

</a>

@endif

        {{-- Manajemen User (admin only) --}}
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('manajemen-user') }}" class="nav-item {{ request()->routeIs('manajemen-user') ? 'active' : '' }}" data-tip="Manajemen User">
            <span class="nav-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </span>
            <span class="nav-label">Manajemen User</span>
        </a>
        @endif
    </nav>

    <hr class="nav-divider">

    <div class="sidebar-bottom">
        <div class="sidebar-profile">
            <div class="sidebar-profile-avatar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
            <div class="sidebar-profile-info">
                <div class="sidebar-profile-name">{{ Auth::user()->name }}</div>
                <div class="sidebar-profile-role">{{ Auth::user()->role ?? 'User' }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="margin-left:auto; flex-shrink:0;">
                @csrf
                <button type="submit" class="btn-logout-icon" title="Log Out" data-tip="Log Out">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8l4 4-4 4"/>
                        <path d="M22 12H9"/>
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>
