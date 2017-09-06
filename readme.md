# Get Stuff Done

Team based project To-Do List application. Allowing easy management of task across multiple teams and projects  

## Getting Started

### Prerequisites

Get stuff done is built on Laravel 5.4 and so has the same system requirements.

* PHP >= 5.6.4
* penSSL PHP Extension
* PDO PHP Extension
* Mbstring PHP Extension
* Tokenizer PHP Extension
* XML PHP Extension
* npm
* composer

### Installing

A step by step series of examples that tell you how the application up and running in a development env

Clone project

```
git clone https://github.com/jamesllewellyn/laravel-tasks.git
```

Install Composer

```
composer install
```

Create laravel application key

```
php artisan key:generate
```

Create database and setup your .env file to match your desired database credentials

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=databasename
DB_USERNAME=username
DB_PASSWORD=password
```

Run migration to create database tables

```
php artisan migrate
```
if you use the local driver link storage to public

```
php artisan storage:link 
```

Install javascript dependencies

```
npm install
```

###Further configuration

####Email

Enter your mail credentials in .env
```
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=null
```
####Pusher

Enter your pusher credentials in .env
```
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
```
####Queue

Set up laravel queue worker to process job table tasks

Set QUEUE_DRIVER to database in .env file

```
QUEUE_DRIVER=database
```
Set laravel worker running 

```
php artisan queue:work
```

## Built With

* [Laravel 5.4](https://laravel.com/docs/5.4)
* [Vue.js v2](https://vuejs.org/v2/guide/)
* [npm](https://www.npmjs.com/)
* [Composer](https://getcomposer.org/)

## Contributing

Pull request are welcome 

## Authors

* **James Llewellyn** - *Initial work* - [James Llewellyn](https://github.com/jamesllewellyn)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

