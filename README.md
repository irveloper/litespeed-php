# LiteSpeed PHP

A Docker-based development environment for **Symfony 7.2** running on **OpenLiteSpeed** — a high-performance, event-driven web server designed as a drop-in replacement for Apache.

## Why LiteSpeed?

LiteSpeed is not just "faster Apache." It's a fundamentally different architecture:

- **Event-driven, non-blocking I/O** — handles thousands of concurrent connections with minimal overhead, unlike Apache's process-per-request model.
- **LSAPI** — a highly optimized PHP execution protocol that's significantly faster than mod_php or FPM. PHP processes are kept warm and reused, eliminating cold-start latency.
- **Built-in HTTP/2 & HTTP/3** — no extra reverse proxy needed. LiteSpeed speaks modern protocols natively.
- **Zero-downtime graceful restarts** — config reloads don't drop a single connection.
- **Apache-compatible rewrite rules** — `.htaccess` files work transparently. No need to rewrite existing rule-sets.
- **Built-in caching (LSCache)** — full-page cache with ESI support, purge controls, and PHP integration.
- **Anti-DDoS & connection limiting** — built-in protection without extra middleware.

In short: you get near-Nginx performance with Apache-compatible configs and a vastly better PHP execution model.

## Architecture

```
┌──────────────────────────────────────┐
│  OpenLiteSpeed (web-litespeed)       │
│  ┌────────────────────────────────┐  │
│  │  LSAPI ── PHP (embedded)      │  │
│  │  docRoot: /var/www/vhosts/    │  │
│  │  localhost/public/             │  │
│  └────────────────────────────────┘  │
│  Ports: 80, 443, 7080 (admin), 8088 │
└──────────────────────────────────────┘
                  │
        volume mount (./src/php-app)
                  │
┌──────────────────────────────────────┐
│  Symfony 7.2 Application            │
│  - Controllers, Forms, Security     │
│  - Twig, Doctrine, Mailer, etc.     │
└──────────────────────────────────────┘
```

## Quick Start

```bash
docker compose up -d
```

The application will be available at **http://localhost:8087**.

### Services & Ports

| Port  | Service                  | Notes                            |
|-------|--------------------------|----------------------------------|
| 8087  | HTTP (app)               | Main application                 |
| 8443  | HTTPS (app)              | TLS via acme.sh                  |
| 7080  | LiteSpeed Admin Console  | Web admin panel                  |
| 8088  | HTTP alternate           | Reserved                         |

## Project Structure

```
├── conf/
│   └── litespeed/           # OpenLiteSpeed configuration
│       ├── Dockerfile       # Custom image (adds composer, vhost config)
│       ├── docker.conf      # Virtual host template for Symfony
│       └── vhconf.conf      # Virtual host overrides
├── src/
│   └── php-app/             # Symfony 7.2 application
│       ├── config/          # Symfony bundles, packages, routes, services
│       ├── public/          # Document root (index.php entry point)
│       ├── src/             # Application code (Controller, Kernel)
│       ├── tests/           # PHPUnit tests
│       ├── templates/       # Twig templates
│       ├── translations/    # Translation files
│       ├── var/             # Cache, logs
│       ├── vendor/          # Composer dependencies
│       └── composer.json
├── docker-compose.yml       # Service definition
├── azure-pipelines.yml      # CI/CD pipeline
└── README.md
```

## Key Configuration Details

### LiteSpeed Virtual Host

The Docker image overrides the default LiteSpeed vhost template (`docker.conf`) to point `docRoot` at `public/` instead of `html/`, so Symfony's front controller is served directly. The vhost supports:

- Gzip compression
- Log rotation (10 MB rolling, 7-day retention)
- `.htaccess` rewrite loading (auto)
- SSL via acme.sh (auto-provisioned certs)

### PHP Execution

PHP runs via **LSAPI** (embedded in LiteSpeed), not PHP-FPM. This means PHP processes are managed by the web server itself — no separate PHP container, no socket configuration, no extra moving parts. The `composer` binary is included in the image for dependency management.

## CI/CD (Azure Pipelines)

The pipeline has two stages:

1. **Validate** — runs `composer validate --strict`, installs dependencies, and executes `phpunit` tests.
2. **Build** — builds the Docker image using `conf/litespeed/Dockerfile`.

Triggered on pushes to `main`.

## Development Workflow

1. **Start the environment:** `docker compose up -d`
2. **Install/update PHP deps:** `docker compose exec web-litespeed composer install`
3. **Run Symfony commands:** `docker compose exec web-litespeed php bin/console <command>`
4. **Run tests:** `docker compose exec web-litespeed php vendor/bin/phpunit`
5. **View logs:** `docker compose logs -f web-litespeed`

## References

- [OpenLiteSpeed Documentation](https://docs.openlitespeed.org/)
- [Symfony 7.2 Documentation](https://symfony.com/doc/7.2/index.html)
- [Original inspiration](https://github.com/sheltie-fusafusa/litespeed-laravel-docker)
