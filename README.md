# Sanqua POS & Inventory

A web-based Point of Sale (POS) and inventory management system built with Laravel. It helps a small business record sales, manage product stock, and view basic reports.

## Features
- Product management (add, edit, delete, categories)
- Stock tracking (stock in, stock out, low stock info)
- Point of Sale: create transactions and calculate totals
- Sales history and transaction details
- User login and roles (e.g. admin, cashier)
- Basic sales and inventory reports

> Edit this list so it matches what your app really does.

## Tech Stack
- Laravel [version]
- PHP [version]
- MySQL
- Blade / Bootstrap / Tailwind [pilih yang dipakai]

## Screenshots
![Dashboard](screenshots/dashboard.png)
![POS Page](screenshots/pos.png)
![Inventory](screenshots/inventory.png)

## Installation
1. Clone the repository
```bash
   git clone https://github.com/[username]/[repo-name].git
   cd [repo-name]
```
2. Install dependencies
```bash
   composer install
   npm install && npm run build
```
3. Copy the environment file and generate the app key
```bash
   cp .env.example .env
   php artisan key:generate
```
4. Set your database in `.env`, then run migrations
```bash
   php artisan migrate --seed
```
5. Start the server
```bash
   php artisan serve
```

## Demo Login (optional)
- Admin: [email] / [password]
- Cashier: [email] / [password]

## My Role
This is a personal/learning project. I designed the database, built the features, and developed the interface.

## Author
[Your name] - [link to Upwork profile or email]