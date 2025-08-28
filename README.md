# School Forum - Setup Instructions

## 1. Clone the Repository

```sh
git clone <repository-url>
```

## 2. Download Dependencies

- [Composer](https://getcomposer.org/download/)
- [MySQL Workbench](https://dev.mysql.com/downloads/workbench/)

## 3. Install Dependencies

Run the following commands in your terminal:

```sh
composer install
npm install
# If there are vulnerabilities:
npm audit fix
```

## 4. Configure Environment

```sh
cp .env.example .env
php artisan key:generate
```

## 5. Set Up Database

### a. MySQL Workbench

- Run and set up MySQL Workbench.
- Create a user:
  - **Username:** `root`
  - **Password:** (choose your own)
- Create a new schema: `school_forum`

### b. Edit `.env` File

Update the following lines in your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306 # or 3307 if 3306 fails
DB_DATABASE=school_forum
DB_USERNAME=<your MySQL username>
DB_PASSWORD=<your MySQL password>
```

## 6. Create Database Tables

Run the migration command:

```sh
php artisan migrate
```

## 7. Start the Webserver

Run the following command every time you open the project in VS Code:

```sh
php artisan serve
```

Access the site at: [http://localhost:8000](http://localhost:8000)