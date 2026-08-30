# WordPress Docker Setup

A local WordPress development environment powered by Docker Compose.

## Stack

| Service      | Image                     | Port   |
| ------------ | ------------------------- | ------ |
| WordPress    | `wordpress:php8.3-apache` | `8080` |
| MySQL        | `mysql:8.0`               | `3306` |
| phpMyAdmin   | `phpmyadmin:latest`       | `8081` |

## Prerequisites

- [Docker](https://docs.docker.com/get-docker/) installed and running
- [Docker Compose](https://docs.docker.com/compose/install/) (included with Docker Desktop)

## Quick Start

### 1. Configure Environment Variables

Copy and edit the `.env` file to customize your settings:

```bash
cp .env.example .env   # if starting fresh
```

Or edit the existing `.env` file:

```env
# Ports
WORDPRESS_PORT=8080
PHPMYADMIN_PORT=8081

# MySQL
MYSQL_ROOT_PASSWORD=rootpassword123
MYSQL_DATABASE=wordpress_db
MYSQL_USER=wp_user
MYSQL_PASSWORD=wp_password123

# WordPress
WORDPRESS_TABLE_PREFIX=wp_
```

> **Note:** If you change database credentials in `.env`, make sure to update `wordpress/wp-config.php` accordingly.

### 2. Start the Containers

```bash
docker compose up -d
```

Wait for all containers to be healthy:

```bash
docker compose ps
```

### 3. Access the Services

| Service    | URL                           |
| ---------- | ----------------------------- |
| WordPress  | http://localhost:8080          |
| phpMyAdmin | http://localhost:8081          |

**phpMyAdmin login:**
- Server: `db`
- Username: `wp_user` (or `root`)
- Password: as set in `.env`

## Common Commands

```bash
# Start all services
docker compose up -d

# Stop all services
docker compose down

# Stop and remove all data (MySQL data will be lost!)
docker compose down -v

# View logs (all services)
docker compose logs -f

# View logs (specific service)
docker compose logs -f wordpress
docker compose logs -f db

# Restart a specific service
docker compose restart wordpress

# Rebuild containers (after config changes)
docker compose up -d --build

# Check container status
docker compose ps

# Access WordPress container shell
docker compose exec wordpress bash

# Access MySQL CLI
docker compose exec db mysql -u wp_user -p wordpress_db
```

## Project Structure

```
BLOG_WORDPRES/
├── wordpress/              # WordPress source files (mounted into container)
│   ├── wp-admin/
│   ├── wp-content/
│   │   ├── plugins/
│   │   ├── themes/
│   │   └── uploads/        # (gitignored)
│   ├── wp-includes/
│   ├── wp-config.php       # Database config (gitignored)
│   └── ...
├── mysql-data/             # MySQL persistent data (gitignored, auto-created)
├── docker-compose.yml      # Docker services configuration
├── .env                    # Environment variables (gitignored)
├── .gitignore
└── README.md
```

## Customization

### Changing Ports

Edit `.env` and restart:

```bash
# In .env
WORDPRESS_PORT=3000
PHPMYADMIN_PORT=3001

# Then restart
docker compose up -d
```

### Installing Plugins & Themes

You can install plugins and themes in two ways:

1. **Via WordPress Admin:** Go to http://localhost:8080/wp-admin → Plugins/Themes
2. **Manually:** Place files directly in `wordpress/wp-content/plugins/` or `wordpress/wp-content/themes/`

### Enabling Debug Mode

Edit `wordpress/wp-config.php`:

```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );    // Logs to wp-content/debug.log
define( 'WP_DEBUG_DISPLAY', false );
```

## Backup & Restore

### Export Database

```bash
docker compose exec db mysqldump -u root -p wordpress_db > backup.sql
```

### Import Database

```bash
docker compose exec -T db mysql -u root -p wordpress_db < backup.sql
```

## Troubleshooting

### Container won't start

```bash
# Check logs for errors
docker compose logs -f

# Ensure ports are not in use
lsof -i :8080
lsof -i :8081
```

### Permission issues on `wordpress/` directory

```bash
# Fix ownership inside the container
docker compose exec wordpress chown -R www-data:www-data /var/www/html/wp-content
```

### MySQL connection refused

The WordPress container waits for MySQL to be healthy before starting. If issues persist:

```bash
# Check MySQL health
docker compose exec db mysqladmin ping -h localhost -u root -p

# Restart the stack
docker compose down && docker compose up -d
```

### Reset everything (fresh start)

```bash
docker compose down -v
rm -rf mysql-data/
docker compose up -d
```

> **Warning:** This will delete all database data. Make sure to export a backup first.
