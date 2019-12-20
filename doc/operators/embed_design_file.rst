embed_design_file
-----------------

Summary
~~~~~~~
Embeds a file from the design folders.
Embeds a file located in any design folders on the site. This is similar to
the `ezdesign` operator but will return the contents of the file instead
of the path.

This can for instance be used to embed javascript code from a file.
The javascript file can then be separate from the template code
and can be opened like a normal javascript file in an editor.
Another typical use case is to embed handlebar templates or inline CSS.

Usage
~~~~~
::

    embed_design_file( <file path> )

Parameters
~~~~~~~~~~
    =========== =============================================================== ======== ==============
    Name        Description                                                     Required Default
    =========== =============================================================== ======== ==============
    file path   Path to design file relative from design folder                 Yes                    
    =========== =============================================================== ======== ==============

Examples
~~~~~~~~

Pass the relative file path to the operator, for instance `javascript/code.js`
could then be resolved to `extension/site/design/site/javscript.code.js` if the
folder `extension/site/design/site` contains this file::

    {embed_design_file('javascript/code.js')}

If the second parameter is used and set to true then the returned value
will contain an HTML element around the file contents if the file type
is known, currently only Javascript and CSS files are supported::

    {embed_design_file('javascript/code.js', true())}
