json_encode
-----------

Summary
~~~~~~~
Returns the JSON representation of input.

Usage
~~~~~
::

    input|json_encode

    json_encode(input)

Parameters
~~~~~~~~~~
None.

Examples
~~~~~~~~
::

    {json_encode( 3.1415 )}

Returns ["3.1415"].

::

    {json_encode( 3.1415 )}

Returns [{"a":1,"b":2},"Test",false,"1.234500"].
