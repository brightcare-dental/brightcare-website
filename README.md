# Bright Care Dental Clinic

Website for Bright Care Dental Clinic — Mangalapuram, Vengode and Mananakku, Trivandrum.

## Stack

Plain PHP 8 (no framework) with a custom admin CMS, backed by MySQL.

```
public_html/
  index.php, about.php, services.php, ...   public pages
  service.php, post.php, case.php           detail pages (clean URLs via .htaccess)
  includes/   bootstrap.php (data layer), header.php, footer.php, icons.php, db.php
  admin/      custom CMS — services, doctors, branches, posts, gallery, enquiries
  api/        enquiry.php — booking endpoint
  assets/     css, js, img, uploads
```

## Configuration

Database credentials are **never committed**. They are read in this order:

1. Environment variables — `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`
2. `public_html/includes/db.config.php` (gitignored)

On a new server:

```bash
cp public_html/includes/db.config.example.php public_html/includes/db.config.php
# then edit it with the real values
```

## First-time setup

`admin/_install.php` creates every table and seeds the initial content
(services, doctors, branches, blog posts, settings).

```bash
php public_html/admin/_install.php
```

**Delete `_install.php` and `_cases.php` from the server once it has run** — they are
unauthenticated when reachable over the web.

## Local development

`.htaccess` handles clean URLs on Apache. PHP's built-in server ignores `.htaccess`,
so it needs a router that emulates the rewrites, or every URL falls back to the
homepage:

```bash
php -S localhost:8990 -t public_html router.php
```

## Requirements

- PHP 8 with `pdo_mysql`
- MySQL / MariaDB
- Apache with `mod_rewrite` (or an equivalent rewrite config)
- A writable `public_html/assets/uploads/` for CMS media
