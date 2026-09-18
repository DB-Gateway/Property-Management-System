<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset password · Gateway PMS</title>
    <link rel="icon" type="image/png" href="{{ asset('images/G-logo-no-bg.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="login-page">
    <main class="login-card compact-auth">
        <div class="login-logo"><img class="logo-emblem-image" src="{{ asset('images/Gateway_logo_circle.png') }}" alt="Gateway emblem"></div>
        <h1>NEW PASSWORD</h1>
        @if($errors->any())<div class="auth-error">{{ $errors->first() }}</div>@endif
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="field-group"><label for="email">Email Address</label><input id="email" name="email" type="email" value="{{ old('email', $email) }}" required></div>
            <div class="field-group"><label for="password">New Password</label><input id="password" name="password" type="password" required></div>
            <div class="field-group"><label for="password_confirmation">Confirm Password</label><input id="password_confirmation" name="password_confirmation" type="password" required></div>
            <button class="sign-in-button" type="submit">RESET PASSWORD</button>
        </form>
    </main>
</body>
</html>
