# Education ERP

A Laravel 11 backend for an Education ERP system. This is a clean, API-ready
foundation prepared for future modular expansion — no business logic or ERP
modules are implemented yet.

## Tech Stack

- **Laravel 11** (PHP 8.3+)
- **MySQL** database
- **Laravel Sanctum** — API token authentication
- **Spatie Laravel Permission** — roles & permissions
- **Laravel Telescope** — local debugging (dev)
- **Laravel Debugbar** — local debugging (dev)
- **CORS** — enabled via Laravel's built-in `HandleCors` (`config/cors.php`)
- **Queue** — `database` driver
- **Redis** — configured in `.env` (ready, not yet used)

## Project Structure

```
app/
├── Core/      # Foundational/base classes, contracts, cross-cutting infra (placeholder)
├── Modules/   # Self-contained ERP feature modules (placeholder)
├── Shared/    # Reusable, domain-agnostic helpers (placeholder)
├── Http/Controllers/Api/Auth/AuthController.php
└── Models/User.php
routes/
└── api.php    # Versioned API routes under /api/v1
```

## Requirements

- PHP 8.3+
- Composer 2.x
- MySQL 8.x
- (Optional) Redis

## Setup

```bash
# 1. Clone the repository
git clone https://github.com/muaviayhasan/ERP-system.git
cd ERP-system

# 2. Install PHP dependencies
composer install

# 3. Create your environment file
cp .env.example .env

# 4. Generate the application key
php artisan key:generate

# 5. Configure the database in .env
#    DB_DATABASE=education_erp
#    DB_USERNAME=your_user
#    DB_PASSWORD=your_password
#    (create the database first, e.g. CREATE DATABASE education_erp;)

# 6. Run the migrations
php artisan migrate

# 7. Link the storage directory
php artisan storage:link

# 8. Install front-end dependencies and build assets (Vite + Tailwind)
npm install
npm run build          # production build  (use `npm run dev` while developing)

# 9. Start the development server
php artisan serve
```

The API is then available at `http://127.0.0.1:8000/api/v1`, and the admin
panel at `http://127.0.0.1:8000/dashboard`.

## Front-end assets

The admin panel UI is built with **Tailwind CSS via Vite**. Source lives in
`resources/css/app.css`, `resources/js/app.js` (which bundles Alpine.js, jQuery,
Select2, Inputmask, and the form helpers), and `tailwind.config.js` (design
tokens). Build with `npm run build`, or run `npm run dev` for hot reloading
during development. Compiled output goes to `public/build/` (git-ignored).

## Queue Worker

The queue uses the `database` driver. To process jobs:

```bash
php artisan queue:work
```

## API

All routes are versioned under `/api/v1`. Public auth endpoints aside, every
endpoint requires a Sanctum bearer token:

```
Authorization: Bearer <token>
```

### Authentication

| Method | Endpoint         | Auth          | Description                   |
|--------|------------------|---------------|-------------------------------|
| POST   | `/auth/register` | Public        | Register a user, return token |
| POST   | `/auth/login`    | Public        | Log in, return token          |
| GET    | `/auth/me`       | `auth:sanctum`| Current authenticated user    |
| POST   | `/auth/logout`   | `auth:sanctum`| Revoke the current token      |

### Resource modules

The system exposes ~65 resourceful endpoints (`index`/`store`/`show`/`update`/
`destroy`) across the ERP modules — students, guardians, teachers, staff,
academic structure (campuses, departments, programs, courses, subjects, classes,
sections, batches, semesters, academic years), attendance, assignments,
homeworks, study materials, timetables, exams, results, fees, scholarships,
fines, refunds, accounting (expenses/income/ledger), library, transport, hostel,
notices, reports, settings, users, and roles.

Module routes live in `routes/api/*.php` and are auto-loaded inside the
authenticated `v1` group. Example (students):

```
GET    /api/v1/students            # paginated list
POST   /api/v1/students            # create
GET    /api/v1/students/{id}       # show
PUT    /api/v1/students/{id}       # update
DELETE /api/v1/students/{id}       # delete
```

**Query params on list endpoints**: `?search=`, `?per_page=` (max 100),
`?sort=column` / `?sort=-column` (desc), `?with=relation1,relation2`, plus
exact-match filters per resource (e.g. `?status=active&campus_id=1`).

**Response envelope** (consistent across the API):

```json
{ "success": true, "message": "...", "data": { ... } }
```

List responses additionally include `links` and `meta` (pagination). Errors
return `{ "success": false, "message": "...", "errors": { ... } }` with the
appropriate status (401 unauthenticated, 403 forbidden, 404 not found, 422
validation).

### Security & access control

Access control uses **Spatie Laravel Permission**, enforced on every module
endpoint:

- **RBAC, fail-closed**: each route requires a `{resource}.{action}` permission
  (`view/create/edit/delete`). The `EnsureApiPermission` middleware denies (403)
  unless the user holds the ability; `super-admin` bypasses via `Gate::before`.
  Authorization runs *before* route-model binding (no 404 existence leaks).
- **10 roles**: `super-admin`, `admin`, `hod`, `teacher`, `accountant`,
  `librarian`, `transport-manager`, `hostel-warden`, `student`, `parent`.
- **Encrypted secrets**: integration credentials and `two_factor_secret` are
  encrypted at rest and masked (`********`) in API responses; sensitive settings
  are masked too.
- **Audit logging**: every successful write is recorded to `activity_logs` with
  a sanitized payload (secrets redacted).
- **Rate limiting**: `throttle:10,1` on auth endpoints, `throttle:120,1` on the
  authenticated API.

The seeded admin (`admin@erp.test` / `password`) is a super-admin. See
`documentation.md` §15 for the full security model.

## Development Tools

- **Telescope**: available at `/telescope` (local environment).
- **Debugbar**: rendered automatically when `APP_DEBUG=true`.

## Business workflows (service layer)

Per `documentation.md` §12, non-trivial logic lives in services, not
controllers:

- **`FeePaymentService`** — recording a payment is one atomic DB transaction:
  it persists the payment, issues a receipt, updates the student fee
  assignment balances/status, applies to the installment, posts a **credit to
  the fee ledger** (the financial source of truth), and refreshes the pending
  fee. `POST /api/v1/fee-payments` delegates to it.
- **`AttendanceService`** — marking attendance recomputes the student's rate
  for the class and raises, updates, or clears the **low-attendance alert**
  against the institute's required threshold. `POST /api/v1/attendances`
  delegates to it.

## Testing

A PHPUnit feature suite runs against an in-memory SQLite database
(`phpunit.xml`) and locks in the security model and workflows:

```bash
php artisan test
```

Covers: authentication + token issuance, the **RBAC matrix** (per-role
allow/deny, fail-closed, authorize-before-binding), **secret encryption &
masking**, **audit logging** (including secret redaction), and the **fee-payment
→ ledger** and **attendance → alert** workflows.

## License

The Laravel framework is open-sourced software licensed under the
[MIT license](https://opensource.org/licenses/MIT).
