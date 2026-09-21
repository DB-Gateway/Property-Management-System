<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in · Gateway PMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/G-logo-no-bg.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="login-page login-page--signin">
    <svg class="login-backdrop" viewBox="0 0 1917 902" preserveAspectRatio="xMidYMid slice" aria-hidden="true" focusable="false">
        <defs>
            <linearGradient id="login-background" x1="0" y1="0" x2="1917" y2="902" gradientUnits="userSpaceOnUse">
                <stop offset="0" stop-color="#0b0710" />
                <stop offset=".48" stop-color="#080d18" />
                <stop offset="1" stop-color="#08192c" />
            </linearGradient>
            <radialGradient id="login-left-glow" cx="0" cy="0" r="1" gradientTransform="translate(210 305) rotate(20) scale(790 610)" gradientUnits="userSpaceOnUse">
                <stop stop-color="#5b0022" stop-opacity=".18" />
                <stop offset=".72" stop-color="#5b0022" stop-opacity="0" />
            </radialGradient>
            <radialGradient id="login-right-glow" cx="0" cy="0" r="1" gradientTransform="translate(1750 750) rotate(-150) scale(920 580)" gradientUnits="userSpaceOnUse">
                <stop stop-color="#12345d" stop-opacity=".24" />
                <stop offset=".76" stop-color="#12345d" stop-opacity="0" />
            </radialGradient>
        </defs>
        <rect width="1917" height="902" fill="url(#login-background)" />
        <rect width="1917" height="902" fill="url(#login-left-glow)" />
        <rect width="1917" height="902" fill="url(#login-right-glow)" />
        <g fill="none" stroke-width="1.15" vector-effect="non-scaling-stroke">
            <path d="M-10 174 L700 -2" stroke="#e5194b" stroke-opacity=".65" />
            <path d="M-5 423 L634 -8" stroke="#7b1730" stroke-opacity=".38" />
            <path d="M302 510 L895 -8" stroke="#651327" stroke-opacity=".28" />
            <path d="M1055 906 L1922 589" stroke="#173d70" stroke-opacity=".50" />
            <path d="M1135 906 L1922 701" stroke="#d51b44" stroke-opacity=".60" />
            <path d="M1250 906 L1540 730" stroke="#71152d" stroke-opacity=".33" />
        </g>
    </svg>

    <main class="login-card login-card--signin">
        <div class="login-logo">
            <img class="logo-emblem-image" src="{{ asset('images/G-logo-no-bg.png') }}" alt="Gateway emblem">
        </div>
        <p class="login-brand">GATEWAY PROPERTY MANAGEMENT SYSTEM</p>

        @if(session('status'))<div class="auth-status">{{ session('status') }}</div>@endif
        @if($errors->any())<div class="auth-error">{{ $errors->first() }}</div>@endif

        <form class="login-form" method="POST" action="{{ route('login.attempt') }}">
            @csrf
            <div class="login-field">
                <svg class="login-field-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M20 21a8 8 0 0 0-16 0M12 13a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" />
                </svg>
                <label class="visually-hidden" for="email">Email Address</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="Enter your company email" autocomplete="email" required autofocus>
            </div>
            <div class="login-field login-field--password">
                <svg class="login-field-icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M7 10V8a5 5 0 0 1 10 0v2M6 10h12a1 1 0 0 1 1 1v9H5v-9a1 1 0 0 1 1-1Z" />
                </svg>
                <label class="visually-hidden" for="password">Password</label>
                <input id="password" name="password" type="password" placeholder="Enter your password" autocomplete="current-password" required>
                <button class="password-toggle" type="button" aria-label="Show password" aria-pressed="false" data-password-toggle>
                    <svg class="password-eye password-eye--show" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M2.5 12s3.5-5 9.5-5 9.5 5 9.5 5-3.5 5-9.5 5-9.5-5-9.5-5Z" />
                        <circle cx="12" cy="12" r="2.5" />
                    </svg>
                    <svg class="password-eye password-eye--hide" viewBox="0 0 24 24" aria-hidden="true" hidden>
                        <path d="m4 4 16 16M10.6 7.1A11 11 0 0 1 12 7c6 0 9.5 5 9.5 5a15 15 0 0 1-3 3.2M7.2 8.5A15 15 0 0 0 2.5 12s3.5 5 9.5 5c.9 0 1.8-.1 2.6-.3M9.9 9.9a3 3 0 0 0 4.2 4.2" />
                    </svg>
                </button>
            </div>
            <div class="login-options">
                <label class="check-label"><input type="checkbox" name="remember" value="1"> <span>Remember me</span></label>
                <a href="{{ route('password.request') }}">Forgot password?</a>
            </div>
            <button class="sign-in-button" type="submit">SIGN IN</button>
        </form>
        <footer>&copy; {{ date('Y') }} Gateway Motors. All rights reserved.</footer>
    </main>

    <script>
        const passwordToggle = document.querySelector('[data-password-toggle]');
        const passwordInput = document.getElementById('password');

        passwordToggle?.addEventListener('click', () => {
            const isVisible = passwordInput.type === 'text';
            passwordInput.type = isVisible ? 'password' : 'text';
            passwordToggle.setAttribute('aria-label', isVisible ? 'Show password' : 'Hide password');
            passwordToggle.setAttribute('aria-pressed', String(!isVisible));
            passwordToggle.querySelector('.password-eye--show').hidden = !isVisible;
            passwordToggle.querySelector('.password-eye--hide').hidden = isVisible;
        });
    </script>
</body>
</html>
