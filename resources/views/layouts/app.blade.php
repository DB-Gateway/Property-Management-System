<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · Gateway PMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/G-logo-no-bg.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/notifications.css') }}?v={{ filemtime(public_path('css/notifications.css')) }}">
    @stack('styles')
    <script>
        (function() {
            try {
                if (window.innerWidth > 780 && localStorage.getItem('pms_sidebar_collapsed') === '1') {
                    document.documentElement.classList.add('sidebar-collapsed');
                }
            } catch (e) {}
        })();
    </script>
</head>
<body class="app-shell">
    <aside class="sidebar" id="sidebar">
        <div class="brand-block">
            <button class="sidebar-close-btn" type="button" data-sidebar-toggle aria-label="Close sidebar" title="Close sidebar">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
            <div class="brand-mark"><img class="logo-emblem-image" src="{{ asset('images/Gateway_logo_circle.png') }}" alt="Gateway"></div>
            <img class="brand-wordmark" src="{{ asset('images/no-bg-gateway-logo.png') }}" alt="GATEWAY">
            <div class="brand-subtitle">PROPERTY<br>MANAGEMENT SYSTEM</div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">MAIN MENU</div>
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <span class="nav-icon">⌂</span><span>Dashboard</span>
            </a>

            @if(auth()->user()->isDealer())
                <a class="nav-link {{ request()->routeIs('requests.index', 'requests.show') ? 'active' : '' }}" href="{{ route('requests.index') }}">
                    <span class="nav-icon">▣</span><span>My Request</span>
                </a>
                <a class="nav-link {{ request()->routeIs('requests.create') ? 'active' : '' }}" href="{{ route('requests.create') }}">
                    <span class="nav-icon">＋</span><span>Submit Request</span>
                </a>
            @else
                <a class="nav-link {{ request()->routeIs('requests.*') ? 'active' : '' }}" href="{{ route('requests.index') }}">
                    <span class="nav-icon">▣</span><span>{{ auth()->user()->isAdmin() ? 'Request' : 'Requests' }}</span>
                    @if(auth()->user()->isDialA())
                        @php
                            $pendingFinishCount = \App\Models\PropertyRequest::where('assignment_type', 'dial_a')->whereNotNull('service_report_completed_at')->whereNull('completed_at')->count();
                        @endphp
                        @if($pendingFinishCount > 0)
                            <span class="badge" style="background:#dc2626; color:#fff; font-size:11px; padding:2px 7px; border-radius:10px; margin-left:auto;" title="{{ $pendingFinishCount }} requests awaiting confirmation">{{ $pendingFinishCount }}</span>
                        @endif
                    @endif
                </a>
                <a class="nav-link {{ request()->routeIs('dealers.*') ? 'active' : '' }}" href="{{ route('dealers.index') }}">
                    <span class="nav-icon">▦</span><span>Dealer Directory</span>
                </a>
                @if(auth()->user()->isManager())
                    <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                        <span class="nav-icon">R</span><span>Reports</span>
                    </a>
                @endif
            @endif

            @if(auth()->user()->isAdmin())
                <div class="nav-section">ADMINISTRATION</div>
                <a class="nav-link {{ request()->routeIs('admin.areas') ? 'active' : '' }}" href="{{ route('admin.areas') }}">
                    <span class="nav-icon">◎</span><span>Areas &amp; Branches</span>
                </a>
                <a class="nav-link {{ request()->routeIs('admin.users', 'admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                    <span class="nav-icon">♟</span><span>User Management</span>
                </a>
                <a class="nav-link {{ request()->routeIs('admin.roles') ? 'active' : '' }}" href="{{ route('admin.roles') }}">
                    <span class="nav-icon">◆</span><span>Role Management</span>
                </a>
                <a class="nav-link {{ request()->routeIs('admin.audit') ? 'active' : '' }}" href="{{ route('admin.audit') }}">
                    <span class="nav-icon">≡</span><span>Audit Logs</span>
                </a>
                <a class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}" href="{{ route('admin.settings') }}">
                    <span class="nav-icon">⚙</span><span>System Settings</span>
                </a>
                <a class="nav-link nav-link-danger {{ request()->routeIs('admin.reset-requests.*') ? 'active' : '' }}" href="{{ route('admin.reset-requests.show') }}">
                    <span class="nav-icon">↺</span><span>Reset Requests</span>
                </a>
            @endif

            <div class="nav-section">ACCOUNT</div>
            <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                <span class="nav-icon">•</span><span>My Profile</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout-link" type="submit"><span class="nav-icon">⏻</span><span>Logout</span></button>
            </form>
        </div>
    </aside>

    <main class="main-area">
        <header class="profile-bar">
            <button class="sidebar-toggle mobile-menu" type="button" data-sidebar-toggle aria-label="Toggle sidebar" title="Toggle sidebar">
                <svg class="sidebar-toggle-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
            @if(!auth()->user()->isAdmin() && !auth()->user()->must_change_password)
                @include('partials.notifications')
            @endif
            <div class="profile-wrap">
                <button class="profile-button" type="button" data-profile-toggle aria-expanded="false">
                    <span class="profile-avatar">{{ auth()->user()->initials }}</span>
                    <span class="profile-copy">
                        <strong>{{ auth()->user()->name }}</strong>
                        <small>{{ auth()->user()->role_label }}</small>
                    </span>
                    <span class="chevron">⌄</span>
                </button>
                <div class="profile-menu" data-profile-menu>
                    <div class="profile-menu-head">
                        <strong>{{ auth()->user()->name }}</strong>
                        <span>{{ auth()->user()->email }}</span>
                    </div>
                    <a href="{{ route('profile.edit') }}">My Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Sign out</button>
                    </form>
                </div>
            </div>
        </header>

        <div class="page-content">
            @if(!auth()->user()->isAdmin() && !auth()->user()->must_change_password)
                <section id="browserPushPrompt" class="push-prompt" hidden aria-label="Enable desktop notifications">
                    <div><strong>Stay up to date with your requests</strong><p>Enable notifications to receive PMS alerts on this device, even when this tab is closed.</p><small data-push-feedback role="status"></small></div>
                    <div class="push-actions"><button type="button" class="button button-primary" data-push-enable>Enable notifications</button><button type="button" class="button button-light" data-push-dismiss>Not now</button></div>
                </section>
            @endif
            @if(session('status'))
                @php
                    $suppressGlobalAlert = request()->routeIs('requests.show') && (auth()->user()?->isDialA() || auth()->user()?->isDialLead() || auth()->user()?->isHandyman());
                @endphp
                @if(!$suppressGlobalAlert)
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif
            @endif
            @if($errors->any() && !request()->routeIs('password.change.*'))
                <div class="alert alert-error">
                    <strong>Please check the form:</strong>
                    <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            @yield('content')
        </div>
    </main>

    <div class="sidebar-overlay" data-sidebar-toggle></div>
    <script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}" defer></script>
    @if(auth()->user()->isManager())
        @include('requests.partials.pm-confirmation-modal')
    @endif
    @if(!auth()->user()->isAdmin() && !auth()->user()->must_change_password)
        @php
            $pushConfig = [
                'configured' => \App\Services\WebPushService::configured(),
                'publicKey' => config('webpush.public_key'),
                'userId' => (string) auth()->id(),
                'workerUrl' => url('/notification-worker.js'),
                'subscribeUrl' => route('notifications.push.store'),
                'unsubscribeUrl' => route('notifications.push.destroy'),
            ];
        @endphp
        <script id="browserPushConfig" type="application/json">{!! json_encode($pushConfig, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
        <script src="{{ asset('js/notifications.js') }}?v={{ filemtime(public_path('js/notifications.js')) }}" defer></script>
        <script src="{{ asset('js/browser-push.js') }}" defer></script>
    @endif
    @stack('scripts')
</body>
</html>
