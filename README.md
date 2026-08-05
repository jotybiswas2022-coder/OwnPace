<p align="center">
    <a href="https://freebuff.com">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="180" alt="Laravel">
    </a>
</p>

# OwnPace — Buy Now, Pay Comfortably

A TALL-stack (Tailwind · Alpine · Laravel · Livewire) installment-payment commerce platform.
Customers shop a catalog and pay over time with flexible weekly or monthly plans — with
insurance, a wallet system, delivery tracking, campaigns, and a full admin console.

> Own at your own pace.

## Tech stack

| Layer      | Choice |
|------------|--------|
| Framework  | Laravel 12 (PHP ^8.2) |
| Frontend   | Tailwind CSS v4 · Alpine.js 3 · Bootstrap Icons |
| Components | Livewire 4 (shop catalog, checkout breakdown) |
| Roles/ACL  | spatie/laravel-permission (namespaced `acl_*` tables) |
| Build      | Vite 7 |
| Auth       | Laravel `Auth::routes()` + custom `frontend.auth_layout` |

## Features

**Storefront**
- Livewire shop catalog with filters, search and pagination
- Product pages, cart, checkout with an interactive breakdown
- Buy-now-pay-later plans: interest, per-installment amounts, insurance toggle
- Wallet: top-ups, withdrawable-vs-spend-only ledger, bonus rules
- Promo codes, wishlist, product requests, plan changes & exchanges
- Order tracking timeline, delivery proxies, post-delivery reviews

**Customer account**
- Orders (installment progress rings), wallet, profile, addresses, saved cards & banks
- KYC verification, account closure requests, notifications

**Admin console** (`/admin`)
- Dashboard, products (incl. CSV import), categories, brands, suppliers, promo codes
- Installment plans, transactions, orders & delivery management, product fees
- Customer management (reminders, support notes, spatie role assignment)
- Unified request review (plan changes, exchanges, product requests, account closures)
- Campaigns with segments, templates, scheduling, open/click tracking
- Roles & permissions editor, reporting dashboard with CSV exports
- Settings + encrypted Secure Config (gateways, SMTP, SMS), wallet & insurance rules

**Payments** — pluggable gateway adapters for **Paystack**, **Flutterwave** and **KoraPay**
with callbacks and webhooks. Messaging goes through a store SMTP server or a Termii-style
SMS provider, configured per channel on the Secure Config screen.

## Requirements

- PHP **8.2+** with extensions: `pdo_mysql`, `mbstring`, `xml`, `gd`/`imagick` (images), `zip`
- Composer 2, Node 20+ / npm
- MySQL 8 (or compatible) — a schema snapshot lives at `database/flexipay_store.sql`
- [FFmpeg](https://ffmpeg.org) (used by `pbmedia/laravel-ffmpeg` for media handling)

## Setup

```bash
# 1. Install PHP + JS dependencies
composer install
npm install

# 2. Environment
cp .env.example .env
php artisan key:generate

# 3. Configure the database in .env (DB_*), then migrate + seed
php artisan migrate --seed          # creates roles + a test@example.com user
php artisan storage:link

# 4. Build assets
npm run build                       # or `npm run dev` while developing

# 5. Serve
php artisan serve                   # queue worker for notifications/campaigns:
php artisan queue:work
```

The `composer run setup` script automates steps 1–4. `composer run dev` boots the
server, queue worker and Vite together.

### Creating an admin

Seed users get the **Customer** role. To promote an account to admin, either:

```bash
php artisan tinker
# \App\Models\User::where('email', 'you@example.com')->first()?->assignRole('Super Admin');
```

…or, on the Customers screen of the admin console, assign the *Admin* / *Super Admin*
role to the user. Only Super Admins can manage settings, users and roles.

## Environment variables

Standard Laravel keys apply (`APP_*`, `DB_*`, `SESSION_*`, `QUEUE_*`, `CACHE_*`, `MAIL_*`).
Provider credentials can live in `.env` as **fallbacks** — the same keys can instead be
saved (encrypted) on the **Settings → Secure Config** screen, which takes precedence:

| Variable                 | Used for                          |
|--------------------------|-----------------------------------|
| `PAYSTACK_SECRET_KEY`    | Paystack payment gateway          |
| `FLUTTERWAVE_SECRET_KEY` | Flutterwave payment gateway       |
| `KORAPAY_SECRET_KEY`     | KoraPay payment gateway           |
| `MAIL_*`                 | Default mailer (used when no store SMTP is configured) |

Store name, contact details, delivery threshold, wallet rules, insurance rate,
notification channel toggles and gateway/SMTP/SMS credentials are all managed from
the admin Settings screens and stored on the `settings` row.

## Folder structure

```
app/
├── helper.php                 # global helpers: storeName(), formatPrice(), imageUrl()…
├── Services/                  # domain logic — every money decision lives here
│   ├── MoneyService.php              # formatting, rounding, parsing
│   ├── InstallmentCalculatorService.php
│   ├── InstallmentScheduleService.php
│   ├── WalletService.php              # ledger + withdrawable rules
│   ├── DeliveryStatusService.php
│   ├── Payments/                      # Paystack / Flutterwave / KoraPay adapters
│   ├── Messaging/                     # MailerFactory, SmsService, channel toggles
│   ├── Campaigns/                     # segments, mail builder, click/open tracking
│   └── Reporting/                     # dashboard aggregations
├── Livewire/                 # ShopCatalog, CheckoutBreakdown
├── Policies/                 # per-module authorization
└── Notifications/            # payment due/overdue, order status, delivery confirm

resources/views/
├── frontend/                 # storefront + account (layouts/store, layouts/auth)
├── backend/                  # admin console (layouts/console + per-module pages)
├── components/               # progress-ring, etc.
└── livewire/                 # Livewire component views
routes/
├── web.php                   # storefront, account, auth
└── admin.php                 # /admin console (all admin routes)
database/
├── migrations/               # schema (incl. acl_* permission tables)
├── seeders/                  # roles & permissions
└── flexipay_store.sql        # reference schema snapshot
```

## Design system

Both the storefront and the admin console share one design system in
`resources/css/app.css`:

- **Tokens** — `@theme` blocks define `--color-brand`, `--color-mango`, `--color-grass`,
  `--color-ember`, `--color-ink`, `--color-paper`, fonts and shadows. Semantic AA-checked
  shades (`mango-ink`, `grass-deep`, `ember-deep`) are used for small text on light backgrounds.
- **Components** — `os-btn`, `os-card`, `os-chip`, `os-input`, `os-table` (collapses to
  labelled cards on mobile via `data-label` attributes), `os-stat`, `os-tabs`, `os-prose`, `os-skeleton`.
- **Motion** — Alpine `x-reveal` (scroll reveal) and `x-countup` in `resources/js/app.js`;
  both respect `prefers-reduced-motion`. Toast notifications are driven by a single
  Alpine `toastHost` partial + `window.flash(message, type)`.

## Testing

```bash
composer test        # PHPUnit
npm run build        # production asset build
```

## License

MIT — built on the [Laravel framework](https://laravel.com), which is open-sourced
software licensed under the [MIT license](https://opensource.org/licenses/MIT).
