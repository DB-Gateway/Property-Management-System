@php($unreadCount = auth()->user()->unreadNotifications()->count())
<details class="notification-bell" id="notificationBell" data-feed-url="{{ route('notifications.index') }}" data-read-all-url="{{ route('notifications.read-all') }}" data-user-id="{{ auth()->id() }}">
    <summary aria-label="Notifications{{ $unreadCount ? ', '.$unreadCount.' unread' : '' }}" aria-controls="notificationPanel">
        <svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/><path d="M10 21h4"/></svg>
        <span class="notification-badge" data-notification-count @if(!$unreadCount) hidden @endif>{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
    </summary>
    <section class="notification-panel" id="notificationPanel" aria-label="Notifications">
        <div class="notification-head"><h2>Notifications</h2><button type="button" data-notification-read-all>Mark all read</button></div>
        <p class="notification-feedback" data-notification-feedback role="status"></p>
        <div class="notification-list" data-notification-list><p class="notification-empty">Loading notifications…</p></div>
        <button type="button" class="notification-more" data-notification-more hidden>Load older notifications</button>
        <div class="notification-device">
            <strong>Desktop notifications</strong>
            <p data-push-status>Turn on notifications for this account on this device.</p>
            <button type="button" data-push-enable>Enable notifications</button>
            <button type="button" data-push-disable hidden>Turn off on this device</button>
        </div>
    </section>
</details>
