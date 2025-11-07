# 🏭 Warehouse Management System (WMS)

The **Warehouse Management System (WMS)** is built using **Laravel** to help manage inventory, stock levels, and warehouse operations efficiently.  
This project supports processes such as goods receiving, goods dispatch, stock control, and tracking item movements in real-time.

---

## 🚀 Key Features

-   📦 **Inventory Management** – Add, update, and delete items with automatic stock tracking.
-   🏷️ **Categories & Suppliers** – Organize items by category and supplier.
-   📥 **Inbound Transactions** – Record every incoming item.
-   📤 **Outbound Transactions** – Manage outgoing goods.
-   📊 **Reports & Analytics** – Generate inventory, transaction, and performance reports.
-   👥 **User & Role Management** – Admin, Operator, and Viewer roles.
-   🔐 **Authentication & Authorization** – Secure login and role-based access using Laravel middleware.
-   ⚙️ **RESTful API (optional)** – For integration with external systems.

---

## 🧱 Tech Stack

| Component         | Technology                                  |
| ----------------- | ------------------------------------------- |
| Backend Framework | [Laravel 11](https://laravel.com/)          |
| Database          | MySQL / MariaDB                             |
| Dashboard         | Laravel [Filament](https://filamentphp.com) |
| Deployment        | GitHub Actions + FTP / Docker               |
| ORM               | Eloquent ORM                                |
| Others            | Spatie Laravel Permission, Filament Admin   |

---

## 📦 Installation

### 1️⃣ Clone the Repository

```bash
git clone https://github.com/username/wms-laravel.git
cd wms-laravel
```

### 2️⃣ Install Dependencies

```bash
composer install
npm install && npm run build
```

### 3️⃣ Configure Environment

Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

Then update your environment variables:

```env
APP_NAME="Warehouse Management System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wms_db
DB_USERNAME=root
DB_PASSWORD=

# Optional: Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@email.com
MAIL_PASSWORD=yourpassword
MAIL_ENCRYPTION=tls
```

### 4️⃣ Generate Key & Run Migrations

```bash
php artisan key:generate
php artisan migrate --seed
```

### 5️⃣ Run the Server

```bash
php artisan serve
```

Access it at: [http://localhost:8000](http://localhost:8000)

---

## 🤝 Contributing

1. Fork this repository
2. Create your feature branch:
    ```bash
    git checkout -b feature/your-feature-name
    ```
3. Commit your changes using conventional commits:
    ```bash
    git commit -m "feat: add outbound transaction module"
    ```
4. Push your branch:
    ```bash
    git push origin feature/your-feature-name
    ```
5. Open a **Pull Request**

---

## 🧾 License

This project is licensed under the [MIT License](LICENSE).

---

## 👨‍💻 Developer

**Aiman Yusuf Wicaksono**  
Web Developer | Laravel & Filament Enthusiast  
🌐 [aimanyusuf.me](#)  
📧 aimanyusufdev@gmail.com  
💼 [Fiverr - AimanYusuf](https://www.fiverr.com/sellers/aiman_yusuf/edit?utm_medium=shared&utm_source=copy_link&utm_campaign=seller_profile_self_view&utm_term=pdv99XG)
