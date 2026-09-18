<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot password · Gateway PMS</title>
    <link rel="icon" type="image/png" href="{{ asset('images/G-logo-no-bg.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="login-page">
    <main class="login-card compact-auth">
        <div class="login-logo"><img class="logo-emblem-image" src="{{ asset('images/Gateway_logo_circle.png') }}" alt="Gateway emblem"></div>
        <h1>RESET ACCESS</h1>
        <p class="login-subtitle">Enter your company email to receive a reset link.</p>
        @if(session('status'))<div class="auth-status">{{ session('status') }}</div>@endif
        @if($errors->any())<div class="auth-error">{{ $errors->first() }}</div>@endif
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="field-group"><label for="email">Email Address</label><input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus></div>
            <button class="sign-in-button" type="submit">SEND RESET LINK</button>
        </form>
        <a class="back-login" href="{{ route('login') }}">← Back to sign in</a>
    </main>
</body>
</html>
