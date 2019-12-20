# Swark

A collection of must have template operators and workflow events for eZ publish legacy.

The code is based on the Seeds Consulting version which was hosted on project.ez.no.
It has been extended with new template operators and a simplified way to register new template operators,
and has additionally been transformed into a proper Composer package.

[![Latest Stable Version](https://img.shields.io/packagist/v/aplia/swark.svg?style=flat-square)](https://packagist.org/packages/aplia/swark)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.3-8892BF.svg?style=flat-square)](https://php.net/)

## Installation

Install with Composer:

```console
composer require aplia/swark
```

## Documentation

An overview of all the template operators and workflow events, as well as detailed instructions
for creating new operators can be read at https://swark.readthedocs.io/

## Creating new operators

The gist of creating a new template operator is registering it in `swark.ini` and then creating
a PHP class which inherits from `SwarkOperator`.

More details can be found in the documentation.

For instance to expose `phpinfo` one would do:

`swark.ini`:
```ini
OperatorMap[phpinfo]=MyProject\PhpInfoOperator
```

`MyProject/PhpInfoOperator.php`:
```php
<?php
namespace MyProject;

use SwarkOperator;

class PhpInfoOperator extends SwarkOperator
{
    function __construct()
    {
        parent::__construct('phpinfo', 'what=');
    }

    static function execute($operatorValue, $namedParameters)
    {
        if ($namedParameters['what']) {
            $constants = array('INFO_GENERAL' => 1, 'INFO_ALL' => -1);
            $what = $namedParameters['what'];
            if (in_array($what, $constants)) {
                phpinfo($constants[$what]);
                return;
            }
        }

        phpinfo();
    }
}
```

Then use it in a template with:

```eztemplate
{phpinfo('INFO_GENERAL')}
```

# Contributors

This code was originally written by Jan Kudlicka and has been extended by developers at Aplia AS.
A detailed list of contributors can be found at https://github.com/Aplia/swark/graphs/contributors

# License

GNU General Public License v2
