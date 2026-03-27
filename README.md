# 🌐 PHP Laravel 12 Localization To Vue

A modern **Laravel 12 + Vue 3 + Inertia.js project** demonstrating how to implement **Localization (Multi-Language Support)** using JSON translation files.

This project shows how to switch languages dynamically (English & Gujarati) and use the translated content inside **Vue components through Inertia shared data**.

---

# 🛠️ Tech Stack

| Technology       | Description        |
| ---------------- | ------------------ |
| Framework        | Laravel 12         |
| Frontend         | Vue 3 + Inertia.js |
| Styling          | Tailwind CSS       |
| Authentication   | Laravel Breeze     |
| Language Support | JSON Localization  |
| Server           | Apache (XAMPP)     |
| PHP Version      | 8.2+               |

---

# 🚀 Step 1: Create Project & Install Authentication

Run the following commands in your terminal.

```bash
# Create a new Laravel 12 project
composer create-project laravel/laravel PHP_Laravel12_Localization_To_Vue

cd PHP_Laravel12_Localization_To_Vue

# Install Breeze with Vue + Inertia
composer require laravel/breeze --dev
php artisan breeze:install vue

# Run database migration
php artisan migrate

# Install frontend dependencies
npm install
```

---

# 🌍 Step 2: Localization Files (JSON)

Inside the project root create a **lang folder** and add these JSON files.

⚠️ Important: Ensure the files are saved in **UTF-8 encoding** in VS Code.

---

## 📄 lang/en.json (English)

```json
{
    "shop_name": "SANCHELA JEWELS",
    "welcome_msg": "Welcome to Sanchela Jewels",
    "description": "Manage your premium jewelry collection easily.",
    "login": "Login",
    "register": "Register",
    "dashboard_title": "Jewelry Dashboard",
    "gold_stock": "Gold Stock",
    "silver_stock": "Silver Stock",
    "diamond_stock": "Diamond Stock",
    "recent_items": "Recent Jewelry Added"
}
```

---

## 📄 lang/gu.json (Gujarati)

```json
{
    "shop_name": "સંચેલા જ્વેલર્સ",
    "welcome_msg": "સંચેલા જ્વેલર્સમાં તમારું સ્વાગત છે",
    "description": "તમારા પ્રીમિયમ જ્વેલરી કલેક્શનને સરળતાથી મેનેજ કરો.",
    "login": "લોગિન",
    "register": "રજીસ્ટર",
    "dashboard_title": "જ્વેલરી ડેશબોર્ડ",
    "gold_stock": "સોનાનો સ્ટોક",
    "silver_stock": "ચાંદીનો સ્ટોક",
    "diamond_stock": "હીરાનો સ્ટોક",
    "recent_items": "તાજેતરમાં ઉમેરાયેલ વસ્તુઓ"
}
```

---

# ⚙️ Step 3: Middleware & Backend Logic

## 1️⃣ Create SetLocale Middleware

Run command:

```bash
php artisan make:middleware SetLocale
```

Update file:

📄 `app/Http/Middleware/SetLocale.php`

```php
public function handle($request, $next)
{
    if (session()->has('locale')) {
        app()->setLocale(session('locale'));
    }

    return $next($request);
}
```

---

## 2️⃣ Register Middleware

Open:

📄 `bootstrap/app.php`

Add middleware inside **web group**.

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \App\Http\Middleware\SetLocale::class,
    ]);
})
```

---

## 3️⃣ Share Localization Data with Inertia

Open:

📄 `app/Http/Middleware/HandleInertiaRequests.php`

Inside the `share()` method add:

```php
'locale' => app()->getLocale(),

'language' => function () {
    $locale = app()->getLocale();
    $path = base_path("lang/$locale.json");

    return file_exists($path)
        ? json_decode(file_get_contents($path), true)
        : [];
},
```

This makes the translation data available inside **Vue components**.

---

# 🔁 Step 4: Language Switching Route

Open:

📄 `routes/web.php`

Add the following route:

```php
Route::get('language/{locale}', function ($locale) {

    if (in_array($locale, ['en', 'gu'])) {
        session()->put('locale', $locale);
    }

    return redirect()->back();
});
```

Now language can be switched using URLs like:

```
/language/en
/language/gu
```

---

# ▶️ How to Run the Project

Run the following commands:

```bash
composer install
npm install

php artisan migrate
php artisan optimize:clear

php artisan serve
npm run dev
```

Then open in browser:

```
http://127.0.0.1:8000
```

---

# 📂 Project Highlights

This project demonstrates:

* Laravel JSON Localization
* Multi-Language Switching
* Session Based Language Selection
* Laravel Breeze Authentication
* Vue 3 + Inertia Integration
* Dynamic Translation Sharing with Vue

---



# Output
<img width="1053" height="517" alt="image" src="https://github.com/user-attachments/assets/ada2c680-51e8-4e04-8b49-0ca073ea1616" />
<img width="1153" height="563" alt="image" src="https://github.com/user-attachments/assets/e36a941d-0a11-4c77-9f97-b606e4bfa4a3" />

