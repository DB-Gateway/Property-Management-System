# PMS request notifications

All active users except administrators have a bell beside their profile. It shows unread counts, saved history, links to the request, and **Mark all read**. Updates refresh every 30 seconds and immediately when a push arrives. Every notification belongs to one user; viewing another user's notification is rejected.

## Who gets notified

| Event | Recipients |
| --- | --- |
| New request | All active Dial-A accounts, including legacy pm_support / dial_lead roles |
| Upcoming inspection, work, service report | All active Dial-A accounts |
| Ageing inspection, work, service report, or final confirmation | All active Dial-A accounts |
| Inspection, work, or service report completed | Submitting dealer and all active PM managers |
| Request finally confirmed complete | Submitting dealer and all active PM managers |
| Schedule added, moved, or cleared | Submitting dealer and all active PM managers; includes old/new dates |

Dealers see only requests they submitted, matching the existing PMS access rules. Other accounts at the same dealership do not receive those request details.

Each completion action creates **one notification per recipient** and one push per subscribed device. Dates entered on a completion form are included in that notification. When completing the service report also closes the request, that is described in the same notification. A separate schedule edit still sends a schedule-change alert. Completion submissions reload the request under a database lock, so a double-click or a second stale submission cannot complete or notify twice. Reopening and completing a stage again generates one new notification.

The bell uses distinct icons for new requests, inspections, work, service reports, final completion, schedule changes, upcoming work, and past-due work. Existing notifications also receive these icons.

## Timing

- Ageing uses the original **request date**, in Asia/Manila time. Requests become past due when **more than 4 calendar days** old, regardless of priority. A September 15 request is due September 19 and is past due September 20. This rule is shared by the bell, dashboard, request list, and reports.
- Changing schedules or completing intermediate stages does not reset this period. Existing requests also use this rule; their historical stored `due_date` values are preserved. New requests store a four-day due date.
- Upcoming means scheduled **today or tomorrow**. Set inspection, work, and service report dates in **Request Schedule** on the request page. The assigned Dial-A account can update unfinished stages. Completed stages must first be reopened to change their dates.
- If service report has no scheduled date, it becomes due when work is completed.
- Ageing reminders identify the current unfinished stage. If the service report is done but the request is not finalized, Dial-A receives a confirmation reminder.
- Reminders run every minute, once per stage/day/schedule state/recipient. Repeated polling and marking a reminder read cannot recreate the same alert. Unread reminders are retired when schedules or completion state change. Completed requests stop reminders.
- A notification feed check also generates reminders at most once a minute, so the bell stays current during local development without a scheduler.

## Desktop / Windows notifications

After sign-in, eligible users see **Enable notifications**. Clicking opens the browser's permission dialog. Select **Allow**. Returning opted-in accounts reconnect automatically; each account/device opts in separately. **Not now** hides the invitation for seven days. The bell always offers device controls. Blocking permission shows site-settings instructions instead of repeatedly prompting.

Push uses the same Web Push approach as GAC-systems, with separate PMS subscriptions and keys. A service worker displays notifications with the tab closed; a queue worker delivers them. Logging out removes that device's subscription; switching accounts clears the previous account's registration. Other devices stay subscribed.

**HTTPS is required on network addresses.** The current development server at `http://10.0.20.100:8002` supports the bell but browsers cannot enable Windows notifications on that insecure origin. On this PC, use `http://localhost/JR-files/PMS/public`; other PCs require a trusted HTTPS address. Set APP_URL to the deployed PMS URL. Push click links resolve relative to the receiving device's application address, including XAMPP subfolders.

Delivery also depends on the browser/Windows notification settings and internet access. No Firebase account is needed. If Brave reports a push-service registration error, enable **Use Google services for push messaging** in `brave://settings/privacy` and relaunch Brave.

## Server setup

This workspace already has the migration applied and its own Web Push keys generated. Do not regenerate or share the private key.

For another installation:

```powershell
composer install
php artisan migrate
php artisan webpush:setup --subject=mailto:admin@your-domain.com
```

Use your real administrator contact address. Configure HTTPS and keep these processes running:

```powershell
php artisan queue:work database --queue=default --sleep=3 --tries=3 --timeout=30
php artisan schedule:work
```

On this Windows workspace, start both hidden helpers with:

```powershell
powershell -ExecutionPolicy Bypass -File scripts/start-notifications.ps1
```

The script checks for its existing workers before starting them. It uses the default database/default push queue; adjust it if overriding WEBPUSH_QUEUE_CONNECTION or WEBPUSH_QUEUE. Logs are in `storage/logs/notification-*.log`. Restart the script after a PC reboot, or configure these commands with your server's process manager. No Windows startup task is installed automatically.

## Verification

```powershell
php artisan test --filter="RequestNotificationTest|BrowserPushNotificationTest"
node --test tests/js/browser-push.test.mjs
php artisan notifications:remind
```

Automated tests cover recipient roles, schedule changes, read ownership, four-day boundaries, deduplication, completion, account switching, logout, permission invitations, subfolder destinations, and encrypted transport against a mocked provider. They do not send real desktop notifications.

For a live check, sign in as Dial-A at the localhost or HTTPS URL, enable notifications and Allow. Submit a request from a dealer on another browser/device. Check the bell and Windows notification. Close the Dial-A tab and repeat to verify background delivery. Complete stages and change a schedule to verify dealer/PM alerts. Native Windows delivery still needs this manual check.

References: [MDN notification permission](https://developer.mozilla.org/en-US/docs/Web/API/Notifications_API/Using_the_Notifications_API), [PHP Web Push](https://github.com/web-push-libs/web-push-php), [Laravel scheduler](https://laravel.com/docs/12.x/scheduling).
