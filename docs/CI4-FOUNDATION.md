# CodeIgniter 4 foundation

## Stack

- **Framework:** CodeIgniter **4.7.5** (`codeigniter4/framework`, PHP **^8.2**, tested target **8.5.12**)
- **Install method:** App Starter layout (`app/`, `public/`, `writable/`, Composer `vendor/`)

## Web server

**Production:** Document root must be the `public/` directory.

**Apache (repo root):** Root `.htaccess` forwards requests to `public/` when the vhost points at the project root.

**PHP CLI / Spark (this project only — PHP 8.5.12):**

```bash
./bin/php -v          # same as /opt/php-8.5.12/bin/php + project php-8512.ini
./spark list
./composer-local.sh install   # Composer via 8.5.12
```

Do **not** use bare `php spark` (that may invoke LAMPP 7.4).

**PHP built-in server (development):**

```bash
./serve                  # localhost:8085
./serve 127.0.0.1 8085
```

## Environment

1. Copy `env.example` to `.env` (`.env` is gitignored).
2. Fill in secrets locally — never commit `.env`.
3. See `docs/PHP-INTL-REQUIREMENT.md` — **`intl` is required** before CI4 will boot.

## Database

Connection is configured via `.env` to match the existing CI3 database (`roman_thomas`, MySQLi). **No migrations or schema changes** are part of this foundation step.

## Application code

All runtime code is under `app/`, `public/`, and `vendor/`. Legacy CodeIgniter 3 trees (`application/`, `system/`) have been removed after migration to CI4.
