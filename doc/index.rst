=======
 Swark
=======

:Authors:
    Jan Kudlicka, Seeds Consulting AS, http://www.seeds.no/
    Aplia AS, https://www.aplia.no

.. contents:: Table of Contents
   :depth: 2

The Swark extension implements a set of highly needed template operators and workflow
event types which are sorely missing from eZ publish legacy.

Operators
=========

.. include:: operators/add_view_parameters.rst

.. raw:: html

   <hr />

.. include:: operators/array_search.rst

.. raw:: html

   <hr />

.. include:: operators/arsort.rst

.. raw:: html

   <hr />

.. include:: operators/asort.rst

.. raw:: html

   <hr />

.. include:: operators/charset.rst

.. raw:: html

   <hr />

.. include:: operators/clear_object_cache.rst

.. raw:: html

   <hr />

.. include:: operators/cookie.rst

.. raw:: html

   <hr />

.. include:: operators/current_layout.rst

.. raw:: html

   <hr />

.. include:: operators/current_siteaccess.rst

.. raw:: html

   <hr />

.. include:: operators/debug_attributes.rst

.. raw:: html

   <hr />

.. include:: operators/debug.rst

.. raw:: html

   <hr />

.. include:: operators/is_post_request.rst

.. raw:: html

   <hr />

.. include:: operators/json_encode.rst

.. raw:: html

   <hr />

.. include:: operators/krsort.rst

.. raw:: html

   <hr />

.. include:: operators/ksort.rst

.. raw:: html

   <hr />

.. include:: operators/ltrim.rst

.. raw:: html

   <hr />

.. include:: operators/modify_view_parameter.rst

.. raw:: html

   <hr />

.. include:: operators/preg_match.rst

.. raw:: html

   <hr />

.. include:: operators/preg_replace.rst

.. raw:: html

   <hr />

.. include:: operators/range.rst

.. raw:: html

   <hr />

.. include:: operators/redirect.rst

.. raw:: html

   <hr />

.. include:: operators/remove_array_element.rst

.. raw:: html

   <hr />

.. include:: operators/return.rst

.. raw:: html

   <hr />

.. include:: operators/rsort.rst

.. raw:: html

   <hr />

.. include:: operators/rtrim.rst

.. raw:: html

   <hr />

.. include:: operators/serialize.rst

.. raw:: html

   <hr />

.. include:: operators/server.rst

.. raw:: html

   <hr />

.. include:: operators/set_array_element.rst

.. raw:: html

   <hr />

.. include:: operators/shortenw.rst

.. raw:: html

   <hr />

.. include:: operators/shuffle.rst

.. raw:: html

   <hr />

.. include:: operators/sort.rst

.. raw:: html

   <hr />

.. include:: operators/split_by_length.rst

.. raw:: html

   <hr />

.. include:: operators/strpos.rst

.. raw:: html

   <hr />

.. include:: operators/str_replace.rst

.. raw:: html

   <hr />

.. include:: operators/strrpos.rst

.. raw:: html

   <hr />

.. include:: operators/substr.rst

.. raw:: html

   <hr />

.. include:: operators/unserialize.rst

.. raw:: html

   <hr />

.. include:: operators/uri_path_segment.rst

.. raw:: html

   <hr />

.. include:: operators/user_id_by_login.rst

.. raw:: html

   <hr />

.. include:: operators/variable_names.rst

.. raw:: html

   <hr />

.. include:: operators/embed_design_file.rst


Workflow event types
====================

.. include:: eventtypes/autopriority.rst

.. raw:: html

   <hr />

.. include:: eventtypes/defertocron.rst

Custom operators
================

Swark also makes it easier to create custom operators.

Operators are detected from the INI file `swark.ini`. Adding a new operator only requires defining a new
entry in the INI file under `[Operators]`, this maps the template operator name to a PHP class that
implements the operator.

For instance to expose the `phpinfo()` function we could do.

.. code-block:: ini

    [Operators]
    OperatorMap[phpinfo]=MyProject\PhpInfoOperator

Then create the PHP file and extend `SwarkOperator`, the base class will take care of all the
cruft needed to define a template operator. The class must be accessible from the autoload system
in PHP.

.. code-block:: php

    <?php
    namespace MyProject;

    use SwarkOperator;

    class PhpInfoOperator extends SwarkOperator
    {
        // ...
    }

The operator class then needs a **constructor** to initialize its operator name and its parameters (`namedParameters`),
and a function to **execute**.

Constructor
-----------

The constructore defines the name of the template operator, this must match the name as specified in `swark.ini`. It also
defines any parameters that it supports. Each parameter is a name with an optional default value.

For instance for our `phpinfo` operator we have one parameter which is empty by default, this matches the `$what` parameter
for the `phpinfo()` function.

.. code-block:: php

    <?php
    class PhpInfoOperator extends SwarkOperator
    {
        function __construct()
        {
            parent::__construct('phpinfo', 'what=');
        }
    }


Execute
-------

The execute function takes in two parameters `$operatorValue` and `$namedParameters`.
`$operatorvalue` corresponds to the value that is piped to the operator, and `$namedParameters` is
the value(s) supplied as parameters using the names defined in the constructor.
Any values returned from `execute` will be the return value from the template operator.

The `phpinfo` implementation is then as follows.

.. code-block:: php

    <?php
    class PhpInfoOperator extends SwarkOperator
    {
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

An using it in an eZ template::

    {phpinfo('INFO_GENERAL')}
