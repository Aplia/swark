# Swark

A collection of must have template operators and workflow events for eZ publish legacy.

The code is based on the Seeds Consulting version which was hosted on project.ez.no.
It has been extended with new template operators and a simplified way to register new template operators,
and has additionally been transformed into a proper Composer package.

## Installation

Install with Composer:

```console
composer require aplia/swark
```

## Template operators

See doc/swark.rst for an overview of operators.

## Workflow event types

See doc/swark.rst for an overview of types.

## Creating new operators

In addition to new template operators this package also makes easier to add new custom ones in your
own project or library.

### Define the template operator

Operators are detected from the INI file `swark.ini`. Adding a new operator only requires defining a new
entry in the INI file under `[Operators]`, this maps the template operator name to a PHP class that
implements the operator.

For instance to expose the `phpinfo()` function we could do:

```ini
OperatorMap[phpinfo]=MyProject\PhpInfoOperator
```

The class must be accessible from the autoload system in PHP.

Then create the PHP file and extend `SwarkOperator`, the base class will take care of all the
cruft needed to define a template operator.

```php
<?php
namespace MyProject;

use SwarkOperator;

class PhpInfoOperator extends SwarkOperator
{
	...
}
```

### Boilerplate
The operator needs a **constructor** to initialize its operator name and its parameters (`namedParameters`), and a function to **execute**.

#### Constructor
The constructore defines the name of the template operator, this must match the name as specified in `swark.ini`. It also
defines any parameters that it supports. Each parameter is a name with an optional default value.

For instance for our `phpinfo` operator we have one parameter which is empty by default, this matches the `$what` parameter
for the `phpinfo()` function.

```php
function __construct()
{
    parent::__construct('phpinfo', 'what=');
}
```

#### Execute
The execute function takes in two parameters `$operatorValue` and `$namedParameters`.
`$operatorvalue` corresponds to the value that is piped to the operator, and `$namedParameters` is
the value(s) supplied as parameters using the names defined in the constructor.

Example usage in an eZ template:

```eztemplate
{phpinfo('INFO_GENERAL')}
```

```php
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
```

Any values returned from `execute` will be the return value from the template operator.

# Contributors

This code was originally written by Jan Kudlicka and has been extended by developers at Aplia AS.
A detailed list of contributors can be found at https://github.com/Aplia/swark/graphs/contributors

# License

GNU General Public License v2
