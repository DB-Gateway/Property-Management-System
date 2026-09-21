# Gateway Property Management System

A Laravel 12 and MariaDB request-tracking application for Gateway Dealers, Dial-A / PM Support, PM Managers, and system administrators.

## XAMPP setup

The project is configured for the local XAMPP MariaDB database `gateway_pms` with the default `root` user and a blank password.

1. Start Apache and MySQL in the XAMPP Control Panel.
2. From this directory, run `composer install` if dependencies are not present.
3. Run `php artisan key:generate` if `APP_KEY` is empty.
4. For a new database, run `php artisan migrate --seed` to create the schema and import the 70 Luzon dealers and branch accounts. Normal seeding does not create sample requests.
5. Open `http://localhost/JR-files/PMS/public`.

The dealer seed source is `Directory_Luzon Dealers.xlsx`; its rows are normalized into `database/data/dealers.json` for repeatable MariaDB seeding.

## Default accounts

All seeded accounts use the password `Gateway@2026`.

| Role | Email |
| --- | --- |
| Administrator | `IT.admin@gateway.ph` |
| Dial-A | `dial.handyman@gateway.ph` |
| PM Manager | `pm.manager@gateway.ph` |
| PM Admin | `pm.admin@gateway.ph` |
| Dealer (Mitsubishi Pasig - Renie Laranga, GM) | `dealer.mitsubishi-pasig@gateway.ph` |

Dealer logins use `dealer.<full-branch-name>@gateway.ph`, with lowercase, hyphenated branch names (for example, `dealer.mg-otis@gateway.ph`). Each of the 70 directory entries has one account. Names come from the directory contacts; entries without a named contact use the branch name. `DealerAccountSeeder` and `PMManagerSeeder` rename the previous seeded logins in place and reset their passwords, preserving request ownership. Existing unrelated accounts are retained.

## Role workflow

- Dealer submissions first enter **Awaiting PM Review**. The PM Manager or PM Admin chooses the priority, records decision remarks visible to the dealer, and assigns **Dial-A** or **In house**. Dial-A cannot access or start an unreviewed request. Existing requests keep their current workflow.
- The PM dashboard links to the review queue across all months. Approval records the reviewer and notifies the dealer; Dial-A receives the request only when it is assigned to Dial-A.
- PM actions use a password confirmation modal. After successful verification, users can enable one-click confirmation for that sign-in (up to 12 hours). The app remembers verification rather than storing the password; signing out, changing passwords, or switching accounts invalidates it. Every action still requires an explicit confirmation.
- PM Managers and PM Admins have the same access. They can update priority, manage dealer details, publish reports, and assign dealer requests to **Dial-A** or **In house** from the Request Assignment panel above Activity Progress. Assignment and reassignment require the signed-in PM user's own password.
- In house records the PM user responsible for the Inspection Request, accepts a free-text Work Order, and accepts Completion Report attachments. Each step displays its c/o attribution. Uploading a Completion Report after saving the Work Order completes the request. Switching back to Dial-A restores the saved Dial-A workflow and hides the in-house details, retaining their history.
- The designated Dial-Lead conducts the scheduled inspection with field representatives, is exclusively responsible for uploading Work Order image attachments, and confirms final completion.
- Dial-A is strictly responsible for uploading Service Reports (strictly images), and notifies the Dial-Lead upon completion via a dedicated notification button.
- Representatives accompany the Dial-Lead during inspections and have field access to view request details and progress.
- Administrators have monitoring access to requests, users, roles, areas, audit logs, and settings. They cannot change request status.

Password reset emails require an outgoing email service. The default `log` mailer does not deliver messages, so password recovery reports that email is unavailable until a sending transport is configured. See [Email setup](docs/email-setup.md) for the required SMTP details and verification steps.

## Verification

For an existing installation, run `php artisan migrate`, then `php artisan db:seed --class=PMAdminSeeder`. This creates `pm.admin@gateway.ph` with the default password shown above and requires a password change at first login. Re-running this seeder preserves an existing account's password.

Run `php artisan test` for the authentication, workbook import, role authorization, request submission, and page-render test suite.
