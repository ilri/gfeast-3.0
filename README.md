# G‑Feast 3.0

G‑Feast 3.0 is the web admin console and REST API backend for the **G‑FEAST** (Gendered Feed Assessment Tool)
platform. It is the server‑side counterpart of the [G‑Feast 3.0 Android app](https://play.google.com/store/apps/details?id=org.gfeast.unmiti)
and is used to manage organizations, projects, centres, partners, surveys, beneficiaries and lookup data,
and to sync data to/from field devices.

The application is built on **CodeIgniter 3** (PHP) and runs on a standard WAMP / LAMP stack with MySQL.

---

## About the project

**G‑FEAST** is the gendered version of the **Feed Assessment Tool (FEAST)**. It aims to identify the aspects of
gender relations in households that affect animal feeding practices, the uptake of feeding interventions, and
the differences in opportunities and constraints in animal feeding between different household types.

The **FEAST Data Application** is a software utility for collecting and analysing data related to smallholder
farming communities' livestock feed resources. The software was originally developed in 2009 and is intended
for use in conjunction with the **Farmer‑Centred Diagnosis** research methodology, which involves holding focus
group discussions and one‑on‑one interviews with farmers to get their input on local conditions, feed‑related
problems and potential solutions.

Use of FEAST helped farmers, extension workers and researchers to fully diagnose and understand problems and
potential interventions in animal feeding. However, one of the challenges with the original FEAST methodology
was that it was based on very technical aspects (including rainfall patterns, types and breeds of livestock,
livestock product markets and the quantities that farmers sold), while the complex gender dynamics in
feed/forage provision were **not** integrated into the methodology of FEAST application.

As a result, the analysis of gender relations was excluded from feed assessments, making it difficult to
integrate gendered feed interventions. Following this shortcoming, **ILRI** (the International Livestock
Research Institute) and the **International Center for Agricultural Research in the Dry Areas (ICARDA)**, in
collaboration with the **Royal Tropical Institute (KIT)**, designed a gender‑responsive approach to feed
assessment — the **G‑FEAST** tool — in 2016.

📖 Read more: <https://www.ilri.org/news/gendered-feed-assessment-tool-g-feast>

---

## Table of contents

1. [About the project](#about-the-project)
2. [Features](#features)
3. [Tech stack](#tech-stack)
4. [Repository layout](#repository-layout)
5. [Requirements](#requirements)
6. [Installation](#installation)
7. [Configuration](#configuration)
8. [Running the app](#running-the-app)
9. [REST API (mobile sync)](#rest-api-mobile-sync)
10. [Modules / controllers](#modules--controllers)
11. [Uploads & storage](#uploads--storage)
12. [Deployment](#deployment)
13. [Privacy & licensing](#privacy--licensing)

---

## Features

- Multi‑tenant **organization → project → centre → partner** hierarchy
- **User & role management** with dynamic menu / permission rendering (`Dynamicmenu_model`)
- **Survey & reporting** workflows: registration, beneficiary data, survey list, plot info,
  activity / visit reports, value‑chain actor management
- **Lookup table management** (counties, sub‑counties, wards, schools, gender, education,
  technology types & practices, training partners, debt types, financing access types,
  event types, markets, value‑chain actor types, yes/no, etc.)
- **Dashboards** — overall, Eklavya, Sarathi‑Mitra, plot info, farmer details
- **KML / map** visualisation (Leaflet + grouped layers + marker cluster)
- **Excel / CSV exports** via PHPExcel (`Exportdata`, `Exportfarmerdata`)
- **REST API** for the Android app: authentication, lookups, sync (download/upload),
  agency, client and user endpoints
- **Email** via PHPMailer (`Phpmailer_lib`) for password reset / notifications
- **AES** encryption helper (`PHP_AES_Cipher`) for sensitive payloads
- Data migration utility (`Data_migration` controller) and a bundled **schema dump**
  at `uploads/database/g_feast_3-0.sql`

## Tech stack

| Layer            | Technology                                   |
|------------------|----------------------------------------------|
| Framework        | CodeIgniter 3 (`system/`)                    |
| Language         | PHP 7.x+ (mysqli driver)                     |
| Database         | MySQL / MariaDB                              |
| Front‑end assets | Bootstrap + jQuery (`include/`, `includeout/`) |
| Maps             | Leaflet, leaflet.markercluster, grouped‑layer control (`includeout/leaflet*`) |
| Charts           | amCharts 4 (`includeout/amcharts4`)          |
| Excel            | PHPExcel (`application/third_party/PHPExcel`)|
| Mail             | PHPMailer (`application/libraries/Phpmailer_lib.php`) |

## Repository layout

```
newgitgfeast3-0/
├── application/                 # CodeIgniter app code
│   ├── config/                  # Config (templates committed; real configs are .gitignored)
│   │   ├── config.php.template
│   │   ├── database.php.template
│   │   ├── autoload.php
│   │   ├── routes.php           # default_controller = login
│   │   └── …
│   ├── controllers/             # Web controllers (see Modules section)
│   │   └── api/                 # Mobile / REST endpoints
│   │       ├── Auth.php
│   │       ├── Sync.php
│   │       ├── Client.php
│   │       ├── Data.php
│   │       ├── Users.php
│   │       └── Agency.php
│   ├── models/                  # *_model.php DB access classes
│   ├── views/                   # Blade‑like PHP views grouped by module
│   ├── libraries/               # PHPMailer + AES cipher
│   ├── third_party/PHPExcel/    # Bundled PHPExcel
│   ├── helpers/  hooks/  language/
│   ├── cache/  logs/            # Runtime (gitignored)
│   └── .htaccess
├── system/                      # CodeIgniter 3 core (do not edit)
├── include/                     # Front‑end vendor & app assets (Bootstrap, jQuery, plugins…)
├── includeout/                  # External libs loaded via <script> (amCharts, Leaflet, intlTelInput…)
├── uploads/
│   ├── admin/   client/   user/ # Default avatars & uploaded media
│   └── database/g_feast_3-0.sql # SQL schema dump
├── index.php.template.php       # Front‑controller template (rename to index.php)
├── .htaccess-template-local     # Apache rewrite rules — local / WAMP
├── .htaccess-template-server    # Apache rewrite rules — production server
├── LICENSE.txt
└── README.md
```

> Several `.template` files are committed instead of the real ones so that secrets and
> environment‑specific paths stay out of git. Copy them to their real names during setup
> (see [Installation](#installation)).

## Requirements

- PHP **7.2+** (works on 7.4 / 8.0 with the bundled PHPExcel)
- MySQL **5.7+** or MariaDB 10.x
- Apache with `mod_rewrite` enabled (WAMP/LAMP/XAMPP all work)
- PHP extensions: `mysqli`, `mbstring`, `gd`, `curl`, `openssl`, `zip`, `xml`

## Installation

1. **Clone** into your web root (e.g. `C:\wamp64\www\` on Windows or `/var/www/html/` on Linux):

   ```bash
   git clone <repo-url> newgitgfeast3-0
   cd newgitgfeast3-0
   ```

2. **Create the front controller** by copying the template:

   ```bash
   cp index.php.template.php index.php
   ```

3. **Create the `.htaccess`** appropriate to your environment:

   ```bash
   # Local (WAMP/XAMPP)
   cp .htaccess-template-local .htaccess

   # Production server
   cp .htaccess-template-server .htaccess
   ```

4. **Create the config files** from the templates and fill in real values:

   ```bash
   cp application/config/config.php.template   application/config/config.php
   cp application/config/database.php.template application/config/database.php
   ```

5. **Import the database schema**:

   ```bash
   mysql -u root -p <your_db_name> < uploads/database/g_feast_3-0.sql
   ```

6. **Permissions** (Linux only) — make the runtime folders writable by the web server:

   ```bash
   chmod -R 775 application/cache application/logs uploads
   ```

## Configuration

### `application/config/config.php`

The template auto‑detects `base_url` from `$_SERVER['HTTP_HOST']`, which works for most
local setups. Override it for production:

```php
$config['base_url'] = 'https://your-domain.example.com/';
$config['index_page'] = '';   // because we use mod_rewrite
```

Other items typically reviewed: `encryption_key`, `sess_*`, `cookie_*`, `csrf_*`.

### `application/config/database.php`

Edit the `default` group:

```php
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '••••••••',
    'database' => 'g_feast_3_0',
    'dbdriver' => 'mysqli',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    // …
);
```

### Environment

`index.php` reads `$_SERVER['CI_ENV']` (default `development`). On production, set:

```apache
SetEnv CI_ENV production
```

### Autoloaded libraries / helpers

Defined in [application/config/autoload.php](application/config/autoload.php):

- libraries: `database`, `session`, `form_validation`
- helpers: `url`, `form`, `security`

## Running the app

Open the site in your browser — the default route lands on the **Login** controller
([application/config/routes.php:52](application/config/routes.php#L52)):

```
http://localhost/newgitgfeast3-0/        →  controllers/Login::index
```

After login, the dynamic menu drives navigation per the user's role.

## REST API (mobile sync)

All mobile/API endpoints live under [application/controllers/api/](application/controllers/api/) and accept JSON
(`Content-Type: application/json`). Every controller emits permissive CORS headers so the
Android app can reach them directly.

Base URL: `https://<host>/api/<controller>/<method>`

| Endpoint                  | Purpose                                                        |
|---------------------------|----------------------------------------------------------------|
| `api/Auth`                | Login / token issuance for the mobile client                   |
| `api/Sync` (`download`/`upload`) | Two‑way sync between the device and server (see [api/Sync.php](application/controllers/api/Sync.php)) |

Sync requests post a JSON body with at least `purpose` (`download` or `upload`),
`user_id` and `unit_id`. Optional `limit` arrays restrict which lookup tables are
returned. See `Sync::download` / `Sync::upload` for the contract.

## Modules / controllers

Web (HTML) controllers under [application/controllers/](application/controllers/):

- **Auth, Login, Password** — authentication, password reset
- **Dashboard, Dashboard_new** — main / new dashboards (plus `_bkp` legacy backups)
- **Organization, Projects, Centre, Partners** — tenant hierarchy management
- **User_management, Viewmanager** — users, roles, dynamic menus
- **Survey, Reporting, Reports** — survey collection, beneficiary reports, plot/agreement views
- **Lookup_tables, Sitemanager** — master data (counties, wards, technology, training partners, …)
- **Value_chain_manangement** — value‑chain actors and types *(spelling preserved)*
- **Locationsetting, Kml** — geographic settings & KML map view
- **Exportdata, Exportfarmerdata** — Excel/CSV exports via PHPExcel
- **News, Helper, Privacy_policy, Termsofuse, Welcome, Nopermission** — static / utility pages
- **Data_migration** — one‑off migration scripts (admin only)

Each controller has a matching view folder under [application/views/](application/views/) and a model
under [application/models/](application/models/).

## Uploads & storage

```
uploads/
├── admin/    default.png and uploaded admin media
├── client/   default.png and uploaded client media
├── user/     default.png and uploaded user avatars
└── database/ g_feast_3-0.sql   # schema dump used during setup
```

Runtime caches and logs (`application/cache/`, `application/logs/`) are emptied by the
`.gitignore` rules and recreated on first run.

## Deployment

1. Use `.htaccess-template-server` and set `CI_ENV=production` in the vhost.
2. Real `config.php` and `database.php` should never be committed (they are excluded).
3. Disable `display_errors` (already handled in [index.php.template.php:73-79](index.php.template.php#L73-L79)).
4. Make sure `application/logs/` and `uploads/` are writable by the web server only.
5. Schedule a regular MySQL dump — the bundled `uploads/database/g_feast_3-0.sql`
   is a snapshot, not a live backup.

## Privacy & licensing

- The **mobile app privacy policy** is rendered by [application/views/privacy_policy.php](application/views/privacy_policy.php) and lists
  ILRI / Unmiti (`admin@unmiti.com`) as the data controller.
- See [LICENSE.txt](LICENSE.txt) for the full license terms (CodeIgniter portions are MIT‑licensed
  by EllisLab / BCIT).

---

For internal questions or access requests, contact **admin@unmiti.com**.
