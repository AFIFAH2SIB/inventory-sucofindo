{{-- resources/views/partials/topbar.blade.php --}}
<div class="page-header">
    @hasSection('header-icon')
        @yield('header-icon')
    @else
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
            <polyline points="9 22 9 12 15 12 15 22"/>
        </svg>
    @endif
    <h1>@yield('page-title', 'Dashboard')</h1>

    <div style="margin-left:auto; display:flex; align-items:center; gap:10px;">
        @hasSection('header-actions')
            @yield('header-actions')
        @endif
    </div>
</div>
