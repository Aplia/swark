dump
----

Summary
~~~~~~~
Dumps content of variable or expression using Symfony dump() function.

Usage
~~~~~
::

    input|debug( [ value ] )

Parameters
~~~~~~~~~~
    =========== =============================================================== ======== ==============
    Name        Description                                                     Required Default
    =========== =============================================================== ======== ==============
    value       The value to dump if input is not set                           No       
    =========== =============================================================== ======== ==============

Examples
~~~~~~~~
::

    {$node|dump}

Displays the content of the $node variable.

::

    {dump($node)}

Same as above but variable passed as parameter.
