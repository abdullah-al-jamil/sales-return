# 🧾 Sales Return Management System

This project is a **Sales Return Management System** built using **PHP (Laravel)**, **jQuery**, and **MySQL**. It allows users to manage invoices and process sales returns efficiently while keeping stock updated automatically.

---

## 🚀 Features

- Create **sales returns** from existing invoices  
- View and manage **invoice items**  
- Track **stock updates** after returns  
- Maintain **item list and inventory**  
- Automatic stock adjustment using transactions  
- Simple and user-friendly interface  

---

## 🛠️ Technologies Used

- Backend: Laravel (PHP Framework)  
- Frontend: jQuery  
- Database: MySQL  
- Language: PHP  

---

## ⚙️ Installation Guide

Follow the steps below to run the project locally:

```bash
# Clone the repository
git clone https://github.com/abdullah-al-jamil/sales-return.git

# Go to project directory
cd sales-return

# Rename environment file
cp .env.example .env

# Configure database credentials inside .env

# Install dependencies
composer install

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed the database
php artisan db:seed

# Start the development server
php artisan serve
