# Tnapf/Env
A package for handling environment variables in a simple way.

## Installation
```bash
composer require tnapf/env
```

## Usage

### Creating without .env

```php
use Tnapf\Env\Env;

$env = new Env();

$env->devMode = true;

// or

$env['devMode'] = true;

####################################
#   Somewhere else in the script   #
####################################

Env::get()->devMode; // true

// or

Env::get()['devMode']; // true
```

### Creating with .env file
```php
use Tnapf\Env\Env;

$env = Env::createFromFile(__DIR__ . '/.env');
```

### Creating with string .env
```php
use Tnapf\Env\Env;

$env = Env::createFromString('devMode=true');
```

## Getting autocomplete
Create a class that extends Tnapf\Env and add PHP DocBlocks for the properties.

```php
use Tnapf\Env\Env as TnapfEnv;

/**
 * @property bool $devMode
 * @property string $databaseHost
 * @property string $databaseUser
 * @property string $databasePassword
 * @property string $databaseName
 */
class Env extends TnapfEnv
{
}
```

Then use this class instead of `Tnapf\Env\Env`