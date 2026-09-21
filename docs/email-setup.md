# Enable password-reset email

PMS needs one approved sender mailbox or SMTP relay to send reset links to users.
Users do not need to share their mailbox passwords with PMS.

## Details to request from IT or your email provider

- The approved sender email address (and permission to send from it).
- The outgoing SMTP server hostname and port.
- The connection security: STARTTLS or implicit TLS.
- The SMTP authentication method. For password-based SMTP, obtain the username and the provider-issued app password or SMTP credential. For an IP-authorized relay, IT must authorize this application's server. OAuth-only providers require a provider-specific integration rather than a static mailbox password.
- One recipient address you control for a delivery test.

Enter credentials directly in the local `.env` file. Do not commit them or paste them into chat.

## Configure the application

Replace the existing `MAIL_` values in `.env` using the values provided by IT. This is a template, not a working account:

```dotenv
MAIL_MAILER=smtp
MAIL_SCHEME=smtp
MAIL_HOST="YOUR_SMTP_HOST"
MAIL_PORT=587
MAIL_USERNAME="YOUR_SMTP_USERNAME"
MAIL_PASSWORD="YOUR_APP_PASSWORD_OR_SMTP_CREDENTIAL"
MAIL_FROM_ADDRESS="YOUR_APPROVED_SENDER_EMAIL"
MAIL_FROM_NAME="Gateway Property Management System"
```

For STARTTLS, this project's Symfony mail transport uses `MAIL_SCHEME=smtp`; port 587 is commonly used. For implicit TLS, use `MAIL_SCHEME=smtps` and the provider's port (commonly 465). Use the provider's actual settings. The current config does not read `MAIL_ENCRYPTION`.

For an IT-authorized relay without username/password authentication, follow IT's settings and leave those credentials `null`. Remove any unused `MAIL_URL` setting because it can override the individual SMTP values. Set a real approved `MAIL_FROM_ADDRESS`; the development `.local` sender is not a production sender.

Then run:

```powershell
php artisan config:clear
```

Restart the local development server if it is running so it picks up the updated environment.

## Test delivery

1. Use a PMS account whose saved email address is a mailbox you control. Save any email changes before requesting a reset.
2. Submit Forgot Password while signed out, or use Send Password Reset Email from an administrator's Edit User page.
3. Check the recipient inbox and spam folder. A successful application response means the sending transport accepted the message; verify receipt separately.
4. Open the newest link in a signed-out browser or private window and set a new password. Reset links expire after 60 minutes; wait at least 60 seconds before requesting another.

`APP_URL` must be the PMS address recipients can open. The current installation uses a private network address, so recipients need network/VPN access to open the reset page. A public deployment needs an appropriate HTTPS address.

The reset notification is sent synchronously by the current implementation, so it does not require a queue worker.

## If it fails

- **Outgoing email is not configured:** the selected transport is `log`, `array`, missing, or has a logging fallback. These cannot establish email delivery and are blocked before creating a reset token.
- **Email could not be sent:** check the provider settings, approved sender, authentication method, and outbound connectivity. The application log contains the provider exception; redact credentials and reset tokens before sharing excerpts.
- **Request accepted but no inbox message:** check spam, the recipient address, and the provider's delivery logs. SMTP acceptance does not guarantee inbox delivery.
- **Link opens the dashboard:** sign out or use a private window; the reset routes currently accept guests only.

## Provider references

- [Laravel mail configuration and the log driver](https://laravel.com/docs/12.x/mail)
- [Google Workspace: send email from an application](https://support.google.com/a/answer/176600?hl=en)
- [Microsoft 365: application email, authentication, and SMTP relay](https://learn.microsoft.com/en-us/exchange/mail-flow-best-practices/how-to-set-up-a-multifunction-device-or-application-to-send-email-using-microsoft-365-or-office-365)
