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

{{-- Simple Modal for Dealer Priority Change Notification --}}
<div class="admin-password-overlay" id="dealerPriorityNoticeOverlay" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="dealerPriorityNoticeTitle">
    <div class="admin-password-modal" style="max-width: 440px; padding: 24px 22px 20px;">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
            <div style="display:flex; align-items:center; gap:10px;">
                <span class="notification-icon notification-icon-priority_changed" style="width:36px; height:36px; border-radius:9px; display:grid; place-items:center;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><path d="M4 22v-7"></path></svg>
                </span>
                <div>
                    <h2 id="dealerPriorityNoticeTitle" style="margin:0 0 2px; font-size:16px; font-weight:700; color:#1e293b;">Priority Status Updated</h2>
                    <small id="dealerPriorityNoticeRef" style="color:#64748b; font-weight:600; font-size:12px;"></small>
                </div>
            </div>
            <button type="button" id="dealerPriorityNoticeCloseBtn" style="background:none; border:none; font-size:20px; line-height:1; color:#94a3b8; cursor:pointer; padding:2px 6px;" aria-label="Close modal">&times;</button>
        </div>

        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:12px 14px; margin-bottom:16px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                <span style="font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Priority Changed</span>
                <div style="display:flex; align-items:center; gap:6px;">
                    <span id="dealerNoticeOldPriority" class="badge"></span>
                    <span style="color:#94a3b8; font-weight:bold;">&rarr;</span>
                    <span id="dealerNoticeNewPriority" class="badge"></span>
                </div>
            </div>
            <div style="border-top:1px solid #e2e8f0; padding-top:10px;">
                <span style="display:block; font-size:11px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px;">Remarks</span>
                <p id="dealerNoticeRemarks" style="margin:0; font-size:13px; color:#1e293b; line-height:1.5; white-space:pre-wrap;"></p>
            </div>
        </div>

        <div class="admin-password-actions" style="display:flex; justify-content:flex-end; gap:10px;">
            <button class="button button-light" type="button" id="dealerPriorityNoticeDismiss">Close</button>
            <a class="button button-primary" id="dealerPriorityNoticeViewLink" href="#" style="text-decoration:none;">View Request</a>
        </div>
    </div>
</div>
