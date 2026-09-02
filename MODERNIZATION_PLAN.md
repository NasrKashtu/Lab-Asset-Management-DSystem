# Lab Asset Management — Modernization & Restructuring Plan

**Status:** Proposed
**Author:** Nasr Kashtu
**Last updated:** 2026-09-03

---

## 1. Where the project stands today

The app works as a demo but is built on patterns that are not acceptable for a
production system in 2026.

### 1.1 Current layout

```
Lab-Asset-Management-DSystem/
└── lab_asset_management/
    └── js/                         ← misnamed folder; contains PHP, not JS
        ├── index.php               ← just redirects to login
        ├── login.php               ← auth + view in one file
        ├── logout.php
        ├── dashboard.php
        ├── asset_management.php     ← DB + POST handling + HTML in one file
        ├── maintenance.php          ← same pattern
        ├── procurement.php          ← same pattern
        ├── disposal.php             ← same pattern
        ├── report.php               ← same pattern
        └── includes/
            ├── db.php               ← hardcoded DB credentials
            ├── head.php / foot.php / nav.php
            ├── icons.php            ← inline Heroicons
            └── ui.php               ← status_chip(), empty_state_row() helpers
    └── sql.txt                     ← seed INSERTs only; no CREATE TABLE
README.md                           ← references config/ and database/ dirs that don't exist
```

### 1.2 Problems, by severity

**Critical — security**

| # | Issue | Location |
|---|-------|----------|
| C1 | SQL injection on every query — user input concatenated into SQL strings | `login.php:9`, `asset_management.php:15`, `maintenance.php:14`, `procurement.php:14`, `disposal.php:15` |
| C2 | Passwords hashed with `md5()` — unsalted, broken | `login.php:6`, `sql.txt:1` |
| C3 | DB credentials committed in source | `includes/db.php:2-5` |
| C4 | Raw SQL error strings echoed to the browser (`"Error: " . $sql . ... . $conn->error`) | all form pages |
| C5 | No CSRF protection on any form | all form pages |
| C6 | No auth/role enforcement on writes — any logged-in user can POST to `disposal.php` etc. regardless of role (roles only filter the nav/dashboard links) | all form pages |
| C7 | Session cookie flags not hardened (no `HttpOnly` / `Secure` / `SameSite`, no session regeneration on login) | `login.php` |

**High — correctness & data model**

- H1: `procurement.php:10` reads `$_SESSION['user_id']`, but `login.php` never sets it → every procurement insert fails or writes a blank user.
- H2: No `schema.sql` — table structure exists only implicitly. `sql.txt` has INSERTs referencing tables/columns nobody can recreate.
- H3: `calibration_status` is a free-form enum typed by hand; it should be **derived** from a `next_calibration_date`.
- H4: No foreign keys, no timestamps (`created_at` / `updated_at`), no soft-delete, no audit trail.
- H5: Tables referenced across the app (`movements`, `usage_logs`, `labs`, `request_assets`) have no UI and no schema.
- H6: `report.php` counts calibration states in PHP after `SELECT *` — should be a `GROUP BY`.

**Medium — architecture & tooling**

- M1: No separation of concerns — routing, DB access, business logic, and HTML all live in each page file.
- M2: No dependency manager (`composer.json`), no autoloading, no namespaces.
- M3: Tailwind loaded from the CDN (`cdn.tailwindcss.com`) — not for production; no build step, no purge.
- M4: No `.gitignore`, no `.env`, no environment separation (dev/stage/prod).
- M5: No tests, no linter, no static analysis, no CI.
- M6: No migrations — schema changes are manual.
- M7: Copy-pasted markup: the `$inputClass` / `$labelClass` strings and the whole form-card + table layout are duplicated across five files.
- M8: Folder nesting (`repo/lab_asset_management/js/`) and the `js` name are misleading.

**What is already good (keep it)**

- The Tailwind visual language on the `upgrating-THE-UI` branch is clean and consistent.
- `includes/` already shows the instinct to factor out shared UI (`nav.php`, `head.php`, `ui.php`, `icons.php`).
- Role-based option lists in `dashboard.php` / `nav.php` are a sensible base for a real authorization layer.

---

## 2. Target: what "modern" means here

Goals, in priority order:

1. **Secure by default** — no raw SQL, hashed passwords, CSRF tokens, enforced authorization, secrets out of git.
2. **Separation of concerns** — HTTP layer, domain logic, persistence, and presentation are distinct.
3. **Reproducible** — one command to install, one to run, one to migrate/seed. Runs the same on any machine and in CI.
4. **Testable** — domain logic covered by automated tests; CI runs them on every push.
5. **Marketable stack** — technologies a hiring manager recognizes: PHP 8.3, a mainstream framework, Composer, Vite, Docker, GitHub Actions.
6. **Preserve the UI** — carry the existing Tailwind design across, don't rebuild it.

### 2.1 Recommended stack — Laravel 11

| Concern | Choice | Why |
|---|---|---|
| Language | PHP 8.3 | Typed properties, enums, readonly, match |
| Framework | **Laravel 11** | Batteries-included; the default PHP stack employers expect |
| Auth | Laravel Breeze (Blade) | Hashing, login throttling, password reset, email verify out of the box |
| ORM | Eloquent | Parameterized by design — removes the entire SQLi class |
| DB | MySQL 8 / MariaDB 11 | Same as today; no data-store migration needed |
| Views | Blade + Blade components | Port existing partials 1:1; kills the copy-paste (M7) |
| Assets | Vite + Tailwind (installed, not CDN) | Real build, tree-shaking, versioned assets (M3) |
| Migrations/Seeders | Laravel migrations + factories | Schema in version control (H2, M6) |
| Authorization | Policies + Gates | Real role enforcement on every action (C6) |
| Validation | Form Requests | Centralised, reusable input rules |
| Tests | Pest (or PHPUnit) | Feature + unit tests |
| Static analysis | Larastan (PHPStan) level 6+ | Catch type errors before runtime |
| Style | Laravel Pint | Consistent formatting |
| Local env | Laravel Sail (Docker) | `./vendor/bin/sail up` — identical env everywhere (M4) |
| CI | GitHub Actions | Pint + Larastan + Pest on every PR (M5) |

### 2.2 Alternative — lean vanilla PHP (if a framework is unwanted)

Keep it dependency-light but still modern:

- **Composer** with PSR-4 autoloading, namespace `App\`.
- **Single front controller** (`public/index.php`) + a small router (`nikic/fast-route` or `bramus/router`).
- **PDO** with prepared statements, wrapped in thin repository classes.
- **Plain PHP templates** (or `league/plates`) rendered by a `View` helper.
- **`vlucas/phpdotenv`** for config, **`robmorgan/phinx`** for migrations.
- **PHPUnit** + **PHP_CodeSniffer** (PSR-12) + **PHPStan**.

Trade-off: you hand-roll auth, validation, CSRF, and routing that Laravel gives you for free. Fine as a learning exercise; slower to a production-ready result. **The rest of this document assumes the Laravel path**; the phases map onto the vanilla path with more manual work in Phase 3–4.

---

## 3. Target structure (Laravel)

```
lab-asset-management/
├── app/
│   ├── Enums/
│   │   ├── AssetStatus.php            (in_service | maintenance | retired | disposed)
│   │   ├── CalibrationState.php       (ok | due_soon | overdue)  ← derived
│   │   ├── RequestStatus.php          (pending | approved | denied)
│   │   └── UserRole.php               (admin | researcher | technician | student)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── DashboardController.php
│   │   │   ├── AssetController.php
│   │   │   ├── MaintenanceRecordController.php
│   │   │   ├── ProcurementRequestController.php
│   │   │   ├── DisposalRequestController.php
│   │   │   └── ReportController.php
│   │   ├── Requests/                  (StoreAssetRequest, StoreMaintenanceRequest, …)
│   │   └── Middleware/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Asset.php
│   │   ├── Category.php
│   │   ├── Lab.php
│   │   ├── MaintenanceRecord.php
│   │   ├── ProcurementRequest.php
│   │   ├── DisposalRequest.php
│   │   └── AssetMovement.php
│   ├── Policies/                      (AssetPolicy, DisposalRequestPolicy, …)
│   └── Services/                      (CalibrationService, ReportService)
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/                       (replaces sql.txt)
├── resources/
│   ├── views/
│   │   ├── layouts/app.blade.php      (← includes/head.php + foot.php)
│   │   ├── components/
│   │   │   ├── nav.blade.php          (← includes/nav.php)
│   │   │   ├── status-chip.blade.php  (← ui.php status_chip())
│   │   │   ├── stat-card.blade.php
│   │   │   ├── page-header.blade.php
│   │   │   └── form/{input,select,textarea}.blade.php  (← $inputClass/$labelClass)
│   │   ├── dashboard.blade.php
│   │   ├── assets/{index,create}.blade.php
│   │   ├── maintenance/…
│   │   ├── procurement/…
│   │   ├── disposal/…
│   │   └── reports/index.blade.php
│   ├── css/app.css                   (Tailwind entry)
│   └── js/app.js
├── routes/web.php                    (all routes in one place, with middleware)
├── tests/{Feature,Unit}/
├── .env.example                      (committed)  |  .env (gitignored)
├── .gitignore
├── composer.json
├── vite.config.js
├── tailwind.config.js
├── docker-compose.yml / Sail
└── README.md
```

Note: the repo root becomes the app root — drop the `lab_asset_management/js/` nesting (M8).

---

## 4. Data model redesign

### 4.1 Core tables

**users**
`id, name, email (unique), password (bcrypt), role (enum), email_verified_at, remember_token, timestamps`

**categories**
`id, name, slug, timestamps` — e.g. Equipment, Chemical, Supply, Glassware

**labs**
`id, name, building, room, timestamps` — replaces the bare `from_lab_id` / `to_lab_id` integers in `sql.txt`

**assets**
```
id
asset_tag           (unique, human-facing, e.g. LAB-000123)
name
serial_number       (nullable)
category_id         → categories
lab_id              → labs (current location, nullable)
status              enum: in_service | maintenance | retired | disposed
purchase_date
purchase_cost       (decimal, nullable)
vendor              (nullable)
warranty_expires_at (date, nullable)
calibration_interval_days (int, nullable — null = not a calibrated asset)
last_calibrated_at  (date, nullable)
next_calibration_at (date, generated / maintained by CalibrationService)
notes               (text, nullable)
timestamps
soft deletes
```
`CalibrationState` is **computed** from `next_calibration_at` vs today (H3) — no hand-typed status.

**maintenance_records**
`id, asset_id → assets, performed_by → users, performed_at, type (calibration|repair|inspection), details, cost (nullable), timestamps`
On a `calibration` record, `CalibrationService` bumps the asset's `last_calibrated_at` / `next_calibration_at`.

**procurement_requests**
`id, requested_by → users, item_description, quantity, estimated_cost, justification, status (enum), reviewed_by → users (nullable), reviewed_at (nullable), timestamps`
Fixes H1 — requester comes from `auth()->id()`, never a client field.

**disposal_requests**
`id, asset_id → assets, requested_by → users, method, reason, status (enum), reviewed_by (nullable), reviewed_at (nullable), timestamps`
On approval, sets the asset's `status` to `disposed`.

**asset_movements**
`id, asset_id → assets, moved_by → users, from_lab_id → labs (nullable), to_lab_id → labs, moved_at, timestamps`

**activity_log** (via `spatie/laravel-activitylog` or a hand-rolled `audits` table)
Who changed what, when — covers H4.

### 4.2 Relationships

- `User` hasMany `procurementRequests`, `maintenanceRecords`, `disposalRequests`
- `Asset` belongsTo `Category`, `Lab`; hasMany `maintenanceRecords`, `movements`, `disposalRequests`
- `Lab` hasMany `assets`

### 4.3 Authorization matrix (enforce in Policies — fixes C6)

| Action | admin | researcher | technician | student |
|---|:--:|:--:|:--:|:--:|
| View dashboard / reports | ✅ | ✅ | ✅ | ✅ |
| View assets | ✅ | ✅ | ✅ | ✅ |
| Create / edit assets | ✅ | ✅ | — | — |
| Log maintenance / calibration | ✅ | — | ✅ | — |
| Submit procurement request | ✅ | ✅ | — | — |
| Approve / deny procurement | ✅ | — | — | — |
| Submit disposal request | ✅ | — | ✅ | — |
| Approve disposal | ✅ | — | — | — |

---

## 5. Migration phases

Each phase is independently shippable and leaves `main` working.

### Phase 0 — Safety net & hygiene (½ day)
- [ ] Add `.gitignore` (`/vendor`, `/node_modules`, `.env`, `/public/build`, `/storage/*.key`).
- [ ] Move `includes/db.php` credentials into `.env`; commit `.env.example` only. Rotate the committed DB password.
- [ ] Stop echoing `$conn->error` / `$sql` to the browser (C4) — log instead.
- [ ] Write the real `database/schema.sql` for the *current* tables so today's app is at least reproducible.
- [ ] Tag the current state: `git tag pre-modernization`.

### Phase 1 — Scaffold Laravel alongside (1 day)
- [ ] `composer create-project laravel/laravel` into a new working dir; move files into repo root.
- [ ] Install Breeze (Blade stack), Pint, Larastan, Pest, Sail.
- [ ] `docker-compose` / Sail up with MySQL 8; app boots on `/`.
- [ ] CI workflow: `pint --test`, `phpstan`, `pest`.

### Phase 2 — Schema as migrations + seeders (1 day)
- [ ] Write migrations for every table in §4.1.
- [ ] Port `sql.txt` into `DatabaseSeeder` + model factories (real hashed passwords).
- [ ] `php artisan migrate:fresh --seed` produces a full demo dataset.

### Phase 3 — Auth & layout (1 day)
- [ ] Replace `login.php` / `logout.php` with Breeze routes; add `role` to the users table and the register/seed flow.
- [ ] Port `head.php` + `foot.php` → `layouts/app.blade.php`; move Tailwind off the CDN into Vite (`resources/css/app.css`, `tailwind.config.js` with the existing `primary` palette).
- [ ] Port `nav.php` → `<x-nav>`; `icons.php` → `<x-icon name="…">`; `ui.php` helpers → `<x-status-chip>` / `<x-empty-row>`.
- [ ] Extract the duplicated form field markup (M7) into `<x-form.input>` / `<x-form.select>` / `<x-form.textarea>`.

### Phase 4 — Port features to controllers + Eloquent (2–3 days)
One vertical slice at a time; each replaces one legacy page:
- [ ] **Assets** — `AssetController` (index/create/store/edit/update), `StoreAssetRequest`, `AssetPolicy`, `assets/*.blade.php`.
- [ ] **Maintenance** — `MaintenanceRecordController` + `CalibrationService` (derives `next_calibration_at`).
- [ ] **Procurement** — `ProcurementRequestController`; requester = `auth()->id()` (fixes H1); admin approve/deny action.
- [ ] **Disposal** — `DisposalRequestController`; approval flips `Asset::status` to `disposed`.
- [ ] **Reports** — `ReportController` + `ReportService` using `GROUP BY` aggregates (fixes H6); reuse `<x-stat-card>`.
- [ ] **Dashboard** — role-filtered cards from a config array → `DashboardController`.
- [ ] Delete the corresponding legacy `.php` file once its slice is live.

### Phase 5 — Harden & polish (1–2 days)
- [ ] Session config: `HttpOnly`, `Secure` (prod), `SameSite=Lax`, regenerate on login (C7).
- [ ] Login throttling (Breeze default) + audit log on sensitive actions (H4).
- [ ] Feature tests: one per policy rule in §4.3 + happy-path CRUD per controller.
- [ ] Larastan to level 6; fix findings.
- [ ] Pagination + search/filter on the asset and record tables.
- [ ] Rewrite `README.md` to match reality (setup, `.env`, `migrate --seed`, `sail up`).
- [ ] `CONTRIBUTING.md` + PR template; enable branch protection on `main` (CI must pass).

### Phase 6 — Nice-to-have (backlog)
- CSV / PDF export of reports.
- Email notifications on request approval / calibration overdue (queued jobs + scheduler).
- Asset QR-code labels linking to the asset detail page.
- REST or Inertia API + a small SPA layer if a richer UI is wanted later.
- Dark mode (the Tailwind setup already makes this cheap).

**Rough total: ~8–12 working days** for Phases 0–5.

---

## 6. Risks & mitigations

| Risk | Mitigation |
|---|---|
| Data loss porting the DB | No data-store change; work on a copy, keep `pre-modernization` tag, seeders rebuild demo data |
| Scope creep (rewriting UI while restructuring) | UI is ported 1:1 in Phase 3; visual changes are a separate later branch |
| Framework learning curve | Phases 1–3 are mostly scaffolding; the vanilla alternative in §2.2 is the fallback |
| Long-lived branch drift | Each phase merges to `main` on its own; legacy pages stay live until their slice replaces them |

## 7. Definition of done

- No string-interpolated SQL anywhere; `grep` for `$conn->query` returns nothing.
- Passwords use `bcrypt`/`argon2`; no `md5` in the codebase.
- No secrets in git history from this point (old ones rotated).
- `composer install && cp .env.example .env && php artisan key:generate && php artisan migrate --seed && npm run build` brings up a working app from a clean checkout.
- CI (Pint + Larastan + Pest) green on `main`.
- Every action in the §4.3 matrix is enforced by a policy and covered by a test.
- `README.md` instructions work as written.
