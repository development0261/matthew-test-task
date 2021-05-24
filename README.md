# Contact App
## Installation
Clone the repository
```sh
git clone https://github.com/development0261/matthew-test-task.git
```
Switch to the repo folder
```sh
cd matthew-test-task
```
Install all the dependencies using composer
```sh
composer install
```
Install all the dependencies using npm
```sh
npm install
```
Run the database migrations (Set the database connection in .env before migrating)
```sh
php artisan migrate
```
Start the local development server
```sh
php artisan serve
```
## Database seeding
Run the database seeder and you're done
```sh
php artisan db:seed
```
## Features
- User can see contact list.
- User can create new contact without page refresh.
- User can update existing contact without page refresh.
- User can delete existing contact without page refresh.

