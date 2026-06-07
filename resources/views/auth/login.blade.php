<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sucofindo</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            inset: -20px;
            background-image: url("{{ asset('images/background_sucofindo.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(8px);
            z-index: -2;
        }

        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(10, 40, 100, 0.50);
            z-index: -1;
        }

        .card-wrapper {
            position: relative;
            width: 780px;
            max-width: 95vw;
        }

        .card {
            background: rgba(255, 255, 255, 0.55);
            border-radius: 28px;
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.25),
                inset 0 1px 0 rgba(255, 255, 255, 0.9),
                inset 0 -1px 0 rgba(255, 255, 255, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.7);
            display: flex;
            flex-direction: row;
            overflow: hidden;
            min-height: 380px;
            backdrop-filter: blur(30px) saturate(180%) brightness(1.05);
            -webkit-backdrop-filter: blur(30px) saturate(180%) brightness(1.05);
        }

        /* LEFT PANEL */
        .left-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 36px;
            background: transparent;
        }

        .logo-img {
            width: 240px;
            height: auto;
            display: block;
        }

        /* RIGHT PANEL */
        .right-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 56px 48px 48px 32px;
            background: transparent;
        }

        .login-title {
            font-size: 28px;
            font-weight: 800;
            color: #1a3566;
            text-shadow: none;
            letter-spacing: 4px;
            margin-bottom: 28px;
            text-align: center;
        }

        .input-group {
            position: relative;
            width: 100%;
            margin-bottom: 18px;
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #1a3566;
            display: flex;
            align-items: center;
        }

        .input-group input {
            width: 100%;
            padding: 14px 44px 14px 48px;
            border: 1px solid rgba(255, 255, 255, 0.7);
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.45);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            font-size: 15px;
            color: #1a3566;
            outline: none;
            transition: border-color 0.25s, background 0.25s;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.6);
        }

        .input-group input::placeholder {
            color: rgba(100, 130, 180, 0.8);
        }

        .input-group input:focus {
            border-color: rgba(26, 53, 102, 0.5);
            background: rgba(255, 255, 255, 0.65);
        }

        .eye-btn {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #1a3566;
            display: flex;
            align-items: center;
            padding: 0;
        }

        .form-row {
            display: flex;
            align-items: center;
            gap: 20px;
            width: 100%;
            margin-top: 8px;
        }

        .btn-login {
            padding: 13px 38px;
            background: linear-gradient(135deg, #5b6bbf 0%, #3d55a8 100%);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 1px;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s;
            flex-shrink: 0;
        }

        .btn-login:hover {
            opacity: 0.92;
            transform: translateY(-1px);
        }

        .register-text {
            font-size: 13.5px;
            color: #1a3566;
            white-space: nowrap;
            text-shadow: none;
            margin-left: auto;
        }

        .register-text a {
            color: #1a3566;
            font-weight: 700;
            text-decoration: underline;
            text-underline-offset: 2px;
        }

        .register-text a:hover {
            text-decoration: underline;
        }

        .copyright {
            text-align: center;
            margin-top: 18px;
            font-size: 12.5px;
            color: rgba(255, 255, 255, 0.75);
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);
            letter-spacing: 0.3px;
        }

        /* Toast Notification */
        .toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .toast {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 300px;
            max-width: 400px;
            padding: 14px 18px;
            border-radius: 14px;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            animation: slideIn 0.35s ease forwards;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .toast.error {
            background: rgba(220, 50, 50, 0.85);
            color: #fff;
        }

        .toast.success {
            background: rgba(30, 130, 80, 0.85);
            color: #fff;
        }

        .toast-icon {
            font-size: 20px;
            flex-shrink: 0;
        }

        .toast-close {
            margin-left: auto;
            background: none;
            border: none;
            color: rgba(255,255,255,0.8);
            cursor: pointer;
            font-size: 18px;
            line-height: 1;
            padding: 0;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(60px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        @keyframes slideOut {
            from { opacity: 1; transform: translateX(0); }
            to   { opacity: 0; transform: translateX(60px); }
        }

        @media (max-width: 600px) {
            .card {
                flex-direction: column;
            }
            .left-panel, .right-panel {
                padding: 36px 24px;
            }
            .form-row {
                flex-direction: column;
                align-items: flex-start;
            }
            .badge-icon {
                top: -24px;
            }
        }
    </style>
</head>
<body>

<!-- Toast Container -->
<div class="toast-container">
    @if ($errors->any())
    <div id="toastError" class="toast error">
        <span class="toast-icon">&#10006;</span>
        <span>{{ $errors->first() }}</span>
        <button class="toast-close" onclick="closeToast('toastError')">&times;</button>
    </div>
    @endif
    @if (session('logout'))
    <div id="toastSuccess" class="toast success">
        <span class="toast-icon">&#10004;</span>
        <span>{{ session('logout') }}</span>
        <button class="toast-close" onclick="closeToast('toastSuccess')">&times;</button>
    </div>
    @endif
</div>

<div class="card-wrapper">
    <div class="card">
        <!-- LEFT: Logo -->
        <div class="left-panel">
            <img src="{{ asset('images/logo.png') }}" alt="Sucofindo" class="logo-img">
        </div>

        <!-- RIGHT: Login Form -->
        <div class="right-panel">
            <div class="login-title">LOGIN</div>

            <form method="POST" action="{{ route('login') }}" style="width:100%">
                @csrf

                <!-- Username / Email -->
                <div class="input-group">
                    <span class="input-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                        </svg>
                    </span>
                    <input
                        type="text"
                        name="username"
                        placeholder="Username atau Email"
                        value="{{ old('username') }}"
                        autocomplete="username"
                        required
                    >
                </div>

                <!-- Password -->
                <div class="input-group">
                    <span class="input-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18 8h-1V6A5 5 0 0 0 7 6v2H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2zm-6 9a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm3.1-9H8.9V6a3.1 3.1 0 0 1 6.2 0v2z"/>
                        </svg>
                    </span>
                    <input
                        type="password"
                        name="password"
                        id="passwordField"
                        placeholder="Password"
                        autocomplete="current-password"
                        required
                    >
                    <button type="button" class="eye-btn" onclick="togglePassword()" title="Tampilkan/Sembunyikan Password">
                        <svg id="eyeIcon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5C21.27 7.61 17 4.5 12 4.5zm0 12.5a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>
                        </svg>
                    </button>
                </div>

                <div class="form-row">
                    <button type="submit" class="btn-login">Login</button>
                    <span class="register-text">
                        <a href="#">Lupa Password?</a>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>
<p class="copyright">&copy; {{ date('Y') }} PT Sucofindo (Persero). All rights reserved.</p>

<script>
    function closeToast(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.style.animation = 'slideOut 0.3s ease forwards';
        setTimeout(() => el.remove(), 300);
    }

    // Auto dismiss after 5 seconds
    document.addEventListener('DOMContentLoaded', function () {
        ['toastError', 'toastSuccess'].forEach(function(id) {
            const el = document.getElementById(id);
            if (el) {
                setTimeout(() => closeToast(id), 5000);
            }
        });
    });

    function togglePassword() {
        const field = document.getElementById('passwordField');
        const icon  = document.getElementById('eyeIcon');
        if (field.type === 'password') {
            field.type = 'text';
            icon.innerHTML = '<path d="M17.94 17.94A10.01 10.01 0 0 1 12 20c-5 0-9.27-3.11-11-7.5a9.99 9.99 0 0 1 2.77-4.17M9.9 4.24A9.12 9.12 0 0 1 12 4c5 0 9.27 3.11 11 7.5a10.06 10.06 0 0 1-1.31 2.56M1 1l22 22" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/><path d="M10.73 10.73A3 3 0 0 0 12 15a3 3 0 0 0 2.27-1" fill="currentColor"/>';
        } else {
            field.type = 'password';
            icon.innerHTML = '<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5C21.27 7.61 17 4.5 12 4.5zm0 12.5a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>';
        }
    }
</script>

</body>
</html>
