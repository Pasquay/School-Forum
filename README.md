setting up the webdev shit
1. clone the repository
2. download the dependencies
	a. download composer
	b. download MySQL workbench
3. install the dependencies
  - Run the ff commands on terminal
	a. "composer install"
	b. "npm install"
		-if nay vulnerability, run "npm audit fix"
4. configure environment
  - Run the ff commands on terminal
	a. "cp .env.example .env"
	b. "php artisan key:generate"
5. Set up database
   a. MySQL Workbench
	- Run and setup MySQL workbench
	- Create a user 
		-username = root 
		-password = wtvr you want
	- Create a new schema 'school_forum'
   b. .env file
	- open it and edit the section with these lines
		DB_CONNECTION=
		DB_HOST=
		DB_PORT=
		DB_DATABASE=
		DB_USERNAME=
		DB_PASSWORD=
	- add the following values
		DB_CONNECTION=mysql
		DB_HOST=127.0.0.1
		DB_PORT=(3306 or 3307 if the first fails)
		DB_DATABASE=school_forum
		DB_USERNAME=(same as the user you made in a)
		DB_PASSWORD=(same as the user you made in a)
6. Create database tables
   - Run the ff in terminal:
	- "php artisan migrate"
7. Start the webserver (Do this everytime you open the project on VS Code
   - Run the ff in terminal:
	- "php artisan serve"
   - Access the site on http://localhost:8000