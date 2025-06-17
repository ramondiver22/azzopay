# Flutterwave-Laravel-v3
Laravel Package for Flutterwave v3 APIs

## Installation
To get the latest version of Flutterwave Rave for Laravel, simply use composer:
``` bash
composer require kenkioko/flutterwave-laravel-v3
```

For `Laravel => 5.5`, skip this step and go to configuration

Once Flutterwave Rave for Laravel is installed, you need to register the service provider. Open up config/app.php and add the following to the providers key.

``` PHP
'providers' => [
    /*
     * Package Service Providers...
     */
    ...
    Laravel\Flutterwave\RaveServiceProvider::class,
    ...
]
```

Also add this to the `aliases`
``` PHP
'aliases' => [
    ...
    'Rave' => Laravel\Flutterwave\Facades\Rave::class,
    ...
]
```

## Configuration
``` bash
php artisan vendor:publish --provider="Laravel\Flutterwave\RaveServiceProvider"
```
A configuration-file named `flutterwave.php` will be placed in your config directory

## Usage
Open your .env file and add your public key, secret key, environment variable and logo url like so:

```
FLUTTERWAVE_PUBLIC_KEY=FLWPUBK-xxxxxxxxxxxxxxxxxxxxx-X
FLUTTERWAVE_SECRET_KEY=FLWSECK-xxxxxxxxxxxxxxxxxxxxx-X
FLUTTERWAVE_ENCRYPTION_KEY=FLWSECK_TESTxxxxxxxxxxxx
FLUTTERWAVE_ENV=staging
```

- **FLUTTERWAVE_PUBLIC_KEY** - This is the api public key gotten from your dashboard (compulsory)

- **FLUTTERWAVE_SECRET_KEY** - This is the api secret key gotten from your dashboard (compulsory)

- **FLUTTERWAVE_ENCRYPTION_KEY** - This is the encryption key gotten from your dashboard (compulsory)

- **FLUTTERWAVE_ENV** - This can be `staging` or `live`. Staging and live API keys can be from your dashboard (compulsory)
