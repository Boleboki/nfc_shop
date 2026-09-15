# 🛒 NFC Shop

A modern e-commerce web application featuring a full online store interface for customers and an admin dashboard for managing products, orders, and user accounts.

---

## 📌 Features

**Customer Side:**
* **Home Page**: Overview of the store, featured items, and announcements.
* **Product Catalog**: Page displaying available items for browsing and selection.
* **Shopping Cart**: Manage selected items and proceed to order placement.
* **About Us Page**: Information about the brand, mission, and store details.
* **Contact Page**: Get in touch via contact forms or store details.

**Admin Dashboard:**
* **User Management**: Add and manage admin or user accounts.
* **Product Management**: Add new products, update prices, edit details, and adjust stock levels.
* **Order Management**: Track, review, and process incoming customer orders.

---

## ⚙️ Prerequisites

* **XAMPP** (v3.3.0 or higher) with **Apache** and **MySQL** services.
* **PHP** (enabled via XAMPP).
* A web browser (Chrome, Firefox, Edge, etc.).

---

## 🚀 Installation & Setup

1. **Clone or Download the Repository:**
   Place the project folder inside your XAMPP `htdocs` directory (e.g., `C:\xampp\htdocs\nfc_shop`).

2. **Start Local Server:**
   * Open the **XAMPP Control Panel** (v3.3.0).
   * Start both **Apache** and **MySQL** modules.

3. **Database Configuration:**
   * Open your browser and go to `http://localhost/phpmyadmin`.
   * Create a new database (e.g., `nfc_shop`).
   * Import the project's SQL file into the newly created database.
   * Update your application's database configuration file (e.g., `config.php` or `.env`) with your local database credentials:
     * **Host:** `localhost`
     * **User:** `root`
     * **Password:** *(leave empty by default in XAMPP)*
     * **Database Name:** `nfc_shop`

4. **Run the Application:**
   * Open your browser and navigate to `http://localhost/nfc_shop`.
