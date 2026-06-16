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

# 8. Start the development server
php artisan serve
```

The API is then available at `http://127.0.0.1:8000/api/v1`.

## Queue Worker

The queue uses the `database` driver. To process jobs:

```bash
php artisan queue:work
```

## API Endpoints

All routes are versioned under `/api/v1`.

| Method | Endpoint             | Auth          | Description                  |
|--------|----------------------|---------------|------------------------------|
| POST   | `/auth/register`     | Public        | Register a user, return token |
| POST   | `/auth/login`        | Public        | Log in, return token         |
| GET    | `/auth/me`           | `auth:sanctum`| Current authenticated user   |
| POST   | `/auth/logout`       | `auth:sanctum`| Revoke the current token     |

Authenticated requests must send the token as a Bearer header:

```
Authorization: Bearer <token>
```

## Development Tools

- **Telescope**: available at `/telescope` (local environment).
- **Debugbar**: rendered automatically when `APP_DEBUG=true`.

## License

The Laravel framework is open-sourced software licensed under the
[MIT license](https://opensource.org/licenses/MIT).
