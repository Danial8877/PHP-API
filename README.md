
# 🚀 MVC Framework – PHP Core

A lightweight, secure, and feature-rich MVC framework built with PHP 8.0+. It includes routing, middleware, JWT authentication, database abstraction, email/SMS support, data export helpers, and enterprise-ready security headers.

---

## Table of Contents

1. [Features](#features)
2. [Requirements](#requirements)
3. [Installation](#installation)
4. [Environment Configuration](#environment-configuration)
5. [Directory Structure](#directory-structure)
6. [Routing](#routing)
7. [Controllers & JSON Responses](#controllers--json-responses)
8. [Middleware](#middleware)
9. [Database](#database)
10. [Models](#models)
11. [Authentication (JWT)](#authentication-jwt)
12. [Email & SMS](#email--sms)
13. [Helper Functions](#helper-functions)
14. [Export Data](#export-data-excel-word-csv-sql)
15. [Error Handling](#error-handling)
16. [Security Headers](#security-headers)
17. [Running the Application](#running-the-application)

---

## ✨ Features

- 🔀 **Custom Router** – supports `GET`, `POST`, `PUT`, `PATCH`, `DELETE` + dynamic parameters (`:number`, `:all`)
- 🧩 **Middleware** – easy to add pre‑processing logic
- 🔐 **JWT Authentication** – via `firebase/php-jwt`
- 📧 **Email** – PHPMailer with SMTP support
- 📱 **SMS** – Kavenegar & Melipayamak providers
- 🗄️ **Database** – PDO with prepared statements (MySQL)
- 📁 **Environment loader** – `.env` support
- 🛡️ **Security headers** – X‑Frame‑Options, X‑XSS‑Protection, etc.
- 📊 **Data export** – Excel (.xls), Word (.doc), CSV, SQL dump
- 🌐 **CORS** – per‑origin method whitelist
- ⚡ **Error handling** – JSON responses for 403, 404, 405, 500
- 🧰 **Global helpers** – `dd()`, `redirect()`, `safeEcho()`, and more

---

## ⚙️ Requirements

- PHP >= 8.0
- MySQL / MariaDB
- Composer
- Web server (Apache / Nginx) with URL rewriting

---

## 🧰 Installation

```bash
# 1. Clone or copy the project into your web root
cd your-project-folder

# 2. Install dependencies
composer install

# 3. Create .env file
cp .env.example .env

# 4. Edit .env with your own credentials

# 5. Point your document root to the `public` folder




------

## 🌍 Environment Configuration

Create a `.env` file in the project root (one level above `public`).

ini

```
# Application
WEB=off          # "on" when inside a subfolder, "off" for root or development
PROJECTNAME=myapi
URL=http://localhost/myapi

# Database
DB_HOST=localhost
DB_NAME=mydb
DB_USER=root
DB_PASS=secret

# JWT
API_KEY=your_super_secret_jwt_key

# Mail (SMTP)
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@email.com
MAIL_PASSWORD=app-password

# SMS - Kavenegar
SMS_KAVENEGAR_API=your_api_key
SMS_KAVENEGAR_SENDER=1000xxxx

# SMS - Melipayamak
SMS_MELIPAYAMAK_USERNAME=0912xxxxxxx
SMS_MELIPAYAMAK_PASSWORD=your_panel_password
SMS_MELIPAYAMAK_SENDER=5000xxxx
```



> **Note:** The `WEB` flag changes how the router parses the URL. Set to `on` when the application runs inside a subfolder (e.g. `http://localhost/projectname/`).

------

## 📁 Directory Structure

text

```
project-root/
├── app/
│   ├── auto/               # Loads .env and error settings
│   ├── bootstrap/          # bootstrap.php – session, headers, autoload
│   ├── configs/            # Config & DB classes
│   ├── controllers/        # Your controllers
│   ├── core/               # Core.php – route dispatcher
│   ├── database/           # Database connection class
│   ├── errors/             # JSON error handlers
│   ├── helpers/            # helpers.php – global functions
│   ├── libraries/          # Controller base class, JWT helpers
│   ├── mails/              # Mail wrapper
│   ├── middlewares/        # Custom middleware classes
│   ├── models/             # Model examples
│   ├── routes/             # Route definitions (Web.php, Route.php)
│   └── sms/                # SMS providers
├── public/
│   └── index.php           # Front controller
├── vendor/                 # Composer dependencies
├── .env                    # Environment variables (ignored by git)
├── .env.example
└── composer.json
```



------

## 🚦 Routing

Define all routes in `app/routes/Web.php` inside the `routes()` method.

### Basic Syntax

php

```
Route::Get("/", "HomeController", "index", "HomeMiddleware");
Route::Post("/user", "UserController", "create");
Route::Put("/user/:number", "UserController", "update");
Route::Delete("/user/:all", "UserController", "delete");
```



- `:number` → regex `([0-9]+)`
- `:all` → regex `([^"<>\\{}|^~$&/]+)`

### CORS Configuration

In `Web.php`, the `CORS()` method returns allowed domains and methods.

php

```
static public function CORS()
{
    return [
        "https://example.com" => ["GET", "POST"],
        "https://another.com" => ["GET"]
    ];
}
```



------

## 🎮 Controllers & JSON Responses

Controllers extend `app\libraries\Controller`.

### Example

php

```
namespace app\controllers;
use app\libraries\Controller;

class UserController extends Controller
{
    public function index()
    {
        return Controller::returnJson(["users" => "list"]);
    }

    public function show($id)
    {
        return Controller::returnJson(["id" => $id]);
    }
    
    public function store($data) // for POST, PUT, PATCH
    {
        // $data is sanitized array from JSON body
        Controller::returnJson(["saved" => $data], 201);
    }
}
```



### Available Methods

| Method                                            | Description                                    |
| :------------------------------------------------ | :--------------------------------------------- |
| `Controller::returnJson($data, $code)`            | Send JSON response and exit                    |
| `Controller::getJson()`                           | Get decoded JSON from request body             |
| `Controller::makeToken(array $payload, int $ttl)` | Generate JWT                                   |
| `Controller::getUserFromToken()`                  | Decode JWT from `Authorization: Bearer` header |

------

## 🧩 Middleware

Create a middleware class in `app/middlewares/` with a `handle()` method. Return `true` to continue, `false` to stop.

php

```
namespace app\middlewares;
use app\errors\Errors;
use app\libraries\Controller;

class AuthMiddleware
{
    public function handle()
    {
        try {
            Controller::getUserFromToken();
            return true;
        } catch (\Exception $e) {
            Errors::_403_();
            return false;
        }
    }
}
```



Attach to route:
`Route::Get("/admin", "AdminController", "index", "AuthMiddleware")`

------

## 🗄️ Database

The `Database` class (in `app/database/`) creates a PDO connection.

php

```
use app\database\Database;
$db = new Database();
$conn = $db->db; // PDO instance

$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => 1]);
$user = $stmt->fetch(PDO::FETCH_OBJ);
```



Credentials are read from `.env` (`DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`).

------

## 📦 Models

A generic `Model` class is provided as a template. Customize table names and queries.

php

```
namespace app\models;
use app\database\Database;

class UserModel
{
    public static function find($id)
    {
        $db = (new Database())->db;
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}
```



------

## 🔐 Authentication (JWT)

Generate a token:

php

```
$token = Controller::makeToken(['user_id' => 123], 7200);
```



Validate token (in middleware or controller):

php

```
$user = Controller::getUserFromToken(); // returns decoded object or throws 403
```



> Keep `API_KEY` in `.env` secret!

------

## ✉️ Email & SMS

### Email (PHPMailer)

php

```
use app\mails\Mail;

Mail::email(
    'sender@example.com',    // from email
    'Sender Name',           // from name
    'receiver@example.com',  // to email
    'Receiver Name',         // to name
    'Subject',               // title
    'Plain text version',    // altBody
    '<h1>HTML message</h1>'  // body
);
```



SMTP settings are read from `.env`.

### SMS – Kavenegar

php

```
use app\sms\Sms;
$result = Sms::smsKavenegar('09123456789', 'Your code is 1234');
```



### SMS – Melipayamak

php

```
$result = Sms::smsMelipayamak('09123456789', 'Hello from Melipayamak');
```



Both return an array with `success` and either `message_id` or `error`.

------

## 🛠️ Helper Functions

Located in `app/helpers/helpers.php`:

| Function                                                     | Description                         |
| :----------------------------------------------------------- | :---------------------------------- |
| `MakeSecureHash($password)`                                  | Argon2id hash                       |
| `CheckSecureHashed($hash, $plain)`                           | Verify hash                         |
| `redirect($path)`                                            | Redirect to relative URL            |
| `dd($data)`                                                  | Dump and die                        |
| `pdf()`                                                      | Trigger browser print dialog        |
| `download($dir, $filename)`                                  | Secure file download                |
| `safeEcho($value)`                                           | HTML‑escaped output                 |
| `urlPath($path)`                                             | Print full URL (based on `URLROOT`) |
| `publicPath($path)`                                          | Print relative path (`./...`)       |
| `getDbConnection()`                                          | Return PDO instance                 |
| `excel($table)`, `word($table)`, `csv($table)`, `tableExport($table)` | Export entire table                 |

------

## 📁 Export Data (Excel, Word, CSV, SQL)

These functions immediately download a file:

php

```
excel('users');      // users.xls
word('products');    // products.doc
csv('orders');       // orders.csv
tableExport('logs'); // logs.sql (CREATE + INSERT)
```



They use the current database connection and respect the table structure.

------

## ⚠️ Error Handling

All errors return JSON with appropriate HTTP status codes:

- `Errors::_403_()` – Forbidden
- `Errors::_404_()` – Route not found
- `Errors::_405_()` – Method not allowed (CORS)
- `Errors::_500_()` – Internal server error

Modify messages in `app/errors/Errors.php`.

------

## 🛡️ Security Headers

Sent in `bootstrap.php`:

- `X-Frame-Options: SAMEORIGIN`
- `X-Content-Type-Options: nosniff`
- `X-XSS-Protection: 1; mode=block`
- `Referrer-Policy: no-referrer-when-downgrade`

Additional protections:

- Input sanitization (`htmlspecialchars` recursively)
- Prepared statements (PDO)
- `.env` isolation
- CORS whitelist

------

## 🚀 Running the Application

1. Set your web server document root to the `public` folder.
2. Enable URL rewriting (mod_rewrite for Apache).
3. Access the app: `http://localhost/`

If `WEB = on` and `PROJECTNAME = myapi`, the base URL becomes `http://localhost/myapi/`.

The default route (`/`) returns:

json

```
{ "msg": "Welcome To My API" }
```



------

## 📄 License

MIT – free for personal and commercial projects.