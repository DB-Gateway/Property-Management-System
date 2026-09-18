# Gateway Property Management System

A Laravel 12 and MariaDB request-tracking application for Gateway Dealers, Dial-A / PM Support, PM Managers, and system administrators.

## XAMPP setup

The project is configured for the local XAMPP MariaDB database `gateway_pms` with the default `root` user and a blank password.

1. Start Apache and MySQL in the XAMPP Control Panel.
2. From this directory, run `composer install` if dependencies are not present.
3. Run `php artisan key:generate` if `APP_KEY` is empty.
4. Run `php artisan migrate:fresh --seed` to create the schema, import the 70 Luzon dealers, and load demo requests.
5. Open `http://localhost/JR-files/PMS/public`.

The dealer seed source is `Directory_Luzon Dealers.xlsx`; its rows are normalized into `database/data/dealers.json` for repeatable MariaDB seeding.

## Demo accounts

All seeded demo accounts use the password `Gateway@2026`.

| Role | Email |
| --- | --- |
| Administrator | `administrator@gateway.com` |
| Designated Dial-Lead | `diallead@gateway.com` |
| Field Representative 1 | `rep1@gateway.com` |
| Field Representative 2 | `rep2@gateway.com` |
| Field Representative 3 | `rep3@gateway.com` |
| PM Manager | `pmmanager@gateway.com` |
| Dealer (Mitsubishi Pasig - Renie Laranga, GM) | `dealer@gateway.com` |

Additional dealer accounts use `dealer2@gateway.com` through `dealer70@gateway.com`, populated with their official branch manager names and designations from `Directory_Luzon Dealers.xlsx`.

## Role workflow

- Dealers submit requests for their assigned branch. Each request is automatically assigned to the designated Dial-Lead upon submission. Attachments submitted by dealers are also strictly images (JPG, JPEG, PNG, WEBP).
- PM Managers have view-only access to requests and dealer requirements, and exclusively publish and print completed request operations reports (including request photos). They do not approve requests, assign who the Dial-Lead is, or choose representatives.
- The designated Dial-Lead conducts the scheduled inspection with field representatives, is exclusively responsible for uploading Work Order image attachments, and confirms final completion.
- Dial-A is strictly responsible for uploading Service Reports (strictly images), and notifies the Dial-Lead upon completion via a dedicated notification button.
- Representatives accompany the Dial-Lead during inspections and have field access to view request details and progress.
- Administrators have monitoring access to requests, users, roles, areas, audit logs, and settings. They cannot change request status.

Forgot-password emails use Laravel's log mailer in local development and are written to `storage/logs/laravel.log`.

## Verification

Run `php artisan test` for the authentication, workbook import, role authorization, request submission, and page-render test suite.
