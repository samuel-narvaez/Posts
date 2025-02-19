<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


## About Laravel

For image handling in the project, a package called Intervention was used.
https://image.intervention.io/v3

As a recommendation, ensure that the GD extension ``(extension=gd)`` is enabled in php.ini for Intervention Image to function properly.


## About Vue

Vue was developed using version 3 with the Composition API, along with tools such as SweetAlert, Toast, and TailwindCSS. For handling requests, we used Axios.

## Steps to Run the Project

1. Clone repository

```
git clone https://github.com/samuel-narvaez/Posts.git
```
2. install dependencies

```
 npm install
 composer install
```

3. Execution of migrations
```
php artisan migrate
```

4. Run Laravel

```
php artisan serve
```

5. Run Vue
```
npm run dev
```