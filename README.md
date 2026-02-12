# Frontend Service

Public-facing web application for the portfolio blog platform. Serves the homepage, blog post views, category/tag browsing, and the authenticated user panel. Built with Laravel and Vite (Tailwind CSS).

## Architecture

```
Browser ──▶ Traefik ──▶ Nginx ──▶ PHP-FPM (Laravel)
                                      │
                           ┌──────────┼──────────┐
                           ▼          ▼          ▼
                        Blog API   SSO OAuth   Users API
                                      │
                                      ▼
                                 RabbitMQ (post.viewed events)
```

**Domain:** `frontend.microservices.local`

## Tech Stack

- **Backend:** PHP 8.2 / Laravel 12
- **Frontend:** Vite 7 / Tailwind CSS 4 / Axios
- **Session store:** Redis 7
- **Authentication:** OAuth 2.0 (via SSO service / Laravel Passport)

## Features

- Homepage with recent blog posts
- Blog post reading with category and tag browsing
- Pagination for post listings
- OAuth 2.0 authentication (login, register, logout via SSO)
- User panel with sidebar navigation:
  - User profile (view/edit)
  - Password management
- Post view tracking (publishes `post.viewed` events to RabbitMQ)
- i18n support
- Kubernetes-ready health endpoints (`/health`, `/ready`)

## Services Communication

| Target | Protocol | Purpose |
|--------|----------|---------|
| Blog API | HTTP (internal) | Posts, categories, tags, comments |
| Users API | HTTP (internal) | User profile data, password updates |
| SSO | OAuth 2.0 | Authentication (Authorization Code flow) |
| RabbitMQ | AMQP | Publish `post.viewed` events |

## Getting Started

### Prerequisites

- Docker & Docker Compose
- Running infrastructure services (Traefik, RabbitMQ, Redis)
- Running Blog, Users, and SSO services

### Development

```bash
cp src/.env.example src/.env
# Edit .env with your configuration

docker compose up -d
```

Containers:

| Container | Role | Port |
|-----------|------|------|
| `frontend-app` | PHP-FPM | 9000 (internal) |
| `frontend-nginx` | Web server | via Traefik |
| `frontend-vite` | Vite dev server (HMR) | 5174 |
| `frontend-redis` | Session/cache store | 6379 |

### Running Tests

```bash
docker compose run --rm --no-deps \
  -e APP_ENV=testing -e DB_CONNECTION=sqlite -e DB_DATABASE=:memory: \
  frontend-app ./vendor/bin/phpunit
```

## Test Coverage

| Metric | Value |
|--------|-------|
| Line coverage | 61.4% |
| Tests | 46 |

## Roadmap

- [x] Homepage with blog integration
- [x] OAuth 2.0 authentication with SSO
- [x] User panel (profile, sidebar, i18n)
- [x] Post, category, tag views with pagination
- [x] Post view tracking via RabbitMQ
- [x] Kubernetes manifests and health endpoints
- [ ] Post reading view with comments (lazy-loading)
- [ ] Post editor (create/edit with categories, tags, slug)
- [ ] User's posts list (table with pagination, edit/delete actions)
- [ ] Author stats panel (view counts from Analytics API)

## License

All rights reserved.
