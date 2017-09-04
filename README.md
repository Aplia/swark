# README #

## Setting up a new operator ##

This project makes possible a simpler setup of operators.

### Add the operator mapping ###
In general projects this might be directly in the <Project>Operators.php-file, or their corresponding .ini-file for operators. In the case of SwarkOperators, use the Swark.ini-file in settings.

Add an entry below `[Operators]`, mapping it to the file by name you set. Here, the name in brackets is what is used in templates, while the PascalCase-entry is the file name (without the `.php` file-extension)
```
OperatorMap[my_new_function]=SwarkMyNewFunctionOperator
```

### Add the file you declared ###
Under autoloads/classes, create the file corresponding to the mapping you created. 

```
<?php
class SwarkMyNewFunction extends SwarkOperator
{...}
?>
```

### Add some boilerplate ###
The operator needs a **constructor** to initialize its operator name and its parameters ('namedParameters'), and a function to **execute**.

#### Constructor ####
Here we set the operator name as specified in mapping, and initialize the input parameters of the operator to a default variable. We can also set its default value, for when the operator is called without parameters. (As in `'cake'|my_new_function`)

```
function __construct()
{
    parent::__construct( 'my_new_function', 'params=This is a default string');
}
```

If the operator is not using input parameters other than the piped object, you can omit the `params` from the constructor.

#### Execute ####
The execute function takes in two parameters `$operatorValue` and `$namedParameters`. `$operatorvalue` corresponds to the value that is piped to the operator, and `$namedParameters` is the value(s) supplied as parameters (E.g.: `my_new_function('cake')`).

```
static function execute( $operatorValue, $namedParameters )
{
	.
	.
	.
    return True; // or whatever
}
```

### TL;DR ###
1. Create the operator mapping in ini (or php)
```
OperatorMap[my_new_function]=SwarkMyNewFunctionOperator
```
2. Create the operator (under **autoloads/classes**
)
```
<?php
class SwarkMyNewFunction extends SwarkOperator
{
	function __construct()
	{
	    parent::__construct( 'my_new_function', 'params=This is a default string');
	}
	static function execute( $operatorValue, $namedParameters )
	{
		.
		.
		.
	    return True; // or whatever
	}
}
?>
```

### Who do I talk to? ###

* Jan Borsodi
* Rune Langseid