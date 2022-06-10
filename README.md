<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Why this project?
In this project I am building a clinic management system using laravel and blades. 
In the branch APIs, I am turning the application into APIs and using it with Vue.

## How to start the project
```sh
$ composer i
```

```sh
$ php artisan migrate
```

```sh
$ php artisan db:seed
```

```sh
$ php artisan serve
```

## Incase of problem in starting the project
In case you have found any problem while starting the project including the message 'Application is in production'
- Change the .env.example file in the root of the project to .env
- if .env does not exist https://stackoverflow.com/questions/58638265/env-file-does-not-exists-in-laravel
- Make sure you have created Database in your local enviroment 
- use this command to clear cache of configuration
```sh 
$ php artisan cache:clear
```
