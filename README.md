# Install Project 
1. Clone the project

# Install Vue Project 
1. cd to admin 
2. npm install
3. npm run dev

4. go to admin/src/grapql/client.js 
to change port of you api 
- const httpLink = createHttpLink({
    uri: 'http://localhost:8000/graphql',
});


# Install Laravel 11 Project 

1. cd to purchaseStor 
2. composer install
3. cp .env.example .env
4. php artisan key:generate
5. php artisan migrate
6. create database in your local
7. php artisan db:seed
8. php artisan serve

go to App\Http\Middleware\Cors.php
to allow url can access this api 

