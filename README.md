# 🧩 Axxis OroCommerce Project

This project is based on **OroCommerce 6.1.0**, running in a **Docker** environment.  
It includes a custom bundle (`AxxisStockBundle`) that displays product stock levels via a public endpoint.

## 🧱 Prerequisites

Before getting started, make sure you have the following installed:

- **Git** → [https://git-scm.com/downloads](https://git-scm.com/downloads)
- **Docker & Docker Compose** → [https://docs.docker.com/get-docker/](https://docs.docker.com/get-docker/)

If you’re new to Docker, follow this quick setup guide:

### 🐳 Docker Installation Guide

#### On Windows or macOS
1. Go to [Docker Desktop Download](https://docs.docker.com/desktop/).
2. Download and install **Docker Desktop** for your system.
3. Start Docker Desktop and ensure it’s running.

#### On Linux (Ubuntu/Debian)
1. Go to [Docker](https://docs.docker.com/engine/install/ubuntu/#install-using-the-repository)
2. Follow the instructions
3. Ensure it’s running.
---

## 🚀 Getting Started

Clone the repository and navigate to the project directory:

```bash
git clone https://github.com/ArthurAlmerio/axxis-test.git
cd axxis-test
```

---

## 🐳 Starting Docker Services

Start all containers:

```bash
docker compose up -d
```

Wait until all services (`php`, `pgsql`, `redis`, `mailcatcher`) are up and healthy.

---

## 🧰 Accessing the PHP Container

Enter the PHP container to execute Oro commands:

```bash
docker compose exec php bash
```

---

## ⚙️ Installing Dependencies

Inside the PHP container, install all dependencies:

```bash
composer install
```

---

## 🏗️ Installing OroCommerce

Run the full Oro installation command:

```bash
php bin/console oro:install   --env=dev   --timeout=600   --language=en   --formatting-code=en_US   --organization-name='AAXIS Test'   --user-name=admin   --user-email=admin@example.com   --user-firstname=Admin   --user-lastname=User   --user-password=admin   --application-url='http://localhost'   --sample-data=n
```

📝 **Note:**  
This command initializes the database, creates the admin user, and runs all required migrations.

---

## 📦 Running the Custom Migration

Run the custom migration that creates initial products and stock levels:

```bash
php bin/console axxis:product:create
```

This command will create products with SKUs `STOCK-001`, `STOCK-002`, etc., each with an initial stock quantity.

---

## 🌍 Running the Application Locally

Start the local development server:

```bash
php -S 0.0.0.0:8000 -t public
```

---

## 🧾 Accessing Product Endpoints

Once the server is running, you can access products via your browser:

- Product 1: [http://localhost:8000/stock/product/stock-001](http://localhost:8000/stock/product/stock-001)
- Product 2: [http://localhost:8000/stock/product/stock-002](http://localhost:8000/stock/product/stock-002)

---

## 🧩 Project Structure

```
src/
 └── Axxis/
     └── Bundle/
         └── StockBundle/
             ├── Controller/
             │   └── StockController.php
             ├── Resources/
             │   └── config/
             │    │  └── oro/
             │    │      └── bundles.yml 
             │    │      └── routing.yml 
             │    │  └── services.yml 
             │   └── views/
             │       └── stock/
             │           └── index.html.twig
             └── AxxisStockBundle.php
```

---

## 💡 Tips

- To **reset the database**, stop all containers and remove PostgreSQL volumes:
  ```bash
  docker compose down -v
  ```
- Then restart the stack and reinstall using:
  ```bash
  docker compose up -d
  ```
- Default database credentials are defined in `docker-compose.yml`:
  ```
  POSTGRES_USER=oro_db_user
  POSTGRES_PASSWORD=oro_db_pass
  POSTGRES_DB=oro_db
  ```
---


