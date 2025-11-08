# School Forum - Setup Instructions

## 1. Clone the Repository

```sh
git clone https://github.com/Pasquay/School-Forum.git
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
php artisan migrate --seed
```

## 7. Start the Webserver

Run the following command every time you open the project in VS Code:

```sh
php artisan serve
```

## 8. Set up Google OAuth

1. Go to [Google Cloud Console](https://console.google.com) and create your project
2. Go to [Credentials](https://console.cloud.google.com/apis/credentials)
3. Create a OAuth client ID
4. Add the redirect URL
  - [https://your-domain.com/user/link-google/callback](https://your-domain.com/user/link-google/callback)
  - For local development add [http://localhost:8000/user/link-google/callback](http://localhost:8000/user/link-google/callback)
5. Add these to the .env file
```
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI=your-redirect-URL
```
6. Configure services.php by adding the ff:
```
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],
```

Access the site at: [http://localhost:8000](http://localhost:8000)
