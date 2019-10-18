# Riddle.sch

A riddle collection page for the Schönherz Halls of Residence

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

What things you need to install the software and how to install them

* PHP 7.2
* Composer
* MySql

### Installing

```
cp .env.example .env
nano .env # To edit .env variables
php composer.phar install
php artisan key:generate
php artisan migrate
```

Then you could start the server:

```
php artisan serve
```

And open `http://127.0.0.1:8000` in your browser.

### Integrations

Get an auth.sch client pointing to `http://127.0.0.1:8000/auth/login`

## Running the tests

Explain how to run the automated tests for this system

### Break down into end to end tests

Explain what these tests test and why

```
Give an example
```

### And coding style tests

Explain what these tests test and why

```
Give an example
```

## Deployment

Add additional notes about how to deploy this on a live system

## Built With

* [Laravel](https://laravel.com/) - The web framework used

## Contributing

Just submit pull requests to us.

## Versioning

We're using continuous delivery.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Hat tip to anyone whose code was used
* Inspiration
* etc
