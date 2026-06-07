<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Sucofindo</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @stack('styles')
</head>
<body>

<!-- Toast notifications -->
<div class="toast-container">
    @if (session('success'))
    <div id="toastSuccess" class="toast success">
        <span>&#10003;&nbsp; {{ session('success') }}</span>
        <button class="toast-close" onclick="closeToast('toastSuccess')">&times;</button>
    </div>
    @endif
    @if (session('error'))
    <div id="toastError" class="toast error">
        <span>&#10006;&nbsp; {{ session('error') }}</span>
        <button class="toast-close" onclick="closeToast('toastError')">&times;</button>
    </div>
    @endif
</div>

<!-- Background depth orbs for realistic glass blur effect -->
<div class="bg-orb bg-orb-1"></div>
<div class="bg-orb bg-orb-2"></div>
<div class="bg-orb bg-orb-3"></div>
<div class="bg-orb bg-orb-4"></div>

<div class="layout-wrapper">
    @include('components.sidebar')

    <div class="right-area">
        @include('components.topbar')

        <div class="main-wrapper">
            <div class="content">
                @yield('content')
            </div>

            @include('components.footer')
        </div>
    </div>
</div>

<!-- Base scripts -->
<script>
    /* Toast */
    function closeToast(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.style.animation = 'slideOut 0.3s ease forwards';
        setTimeout(() => el.remove(), 300);
    }
    document.addEventListener('DOMContentLoaded', function () {
        ['toastSuccess', 'toastError'].forEach(id => {
            const el = document.getElementById(id);
            if (el) setTimeout(() => closeToast(id), 5000);
        });
    });

    /* Sidebar toggle */
    function toggleSidebar() {
        document.body.classList.toggle('sidebar-collapsed');
        localStorage.setItem('sidebarCollapsed', document.body.classList.contains('sidebar-collapsed'));
    }

    /* Submenu */
    function toggleSubmenu(el) {
        el.classList.toggle('open');
        el.nextElementSibling.classList.toggle('open');
    }

    /* Restore sidebar state */
    document.addEventListener('DOMContentLoaded', function () {
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            document.body.classList.add('sidebar-collapsed');
        }
        /* Auto-open submenu if a child is active */
        document.querySelectorAll('.nav-submenu a.active').forEach(function (a) {
            const submenu = a.closest('.nav-submenu');
            if (submenu) {
                submenu.classList.add('open');
                const toggle = submenu.previousElementSibling;
                if (toggle) toggle.classList.add('open');
            }
        });
    });
</script>

@stack('scripts')
</body>
</html>
