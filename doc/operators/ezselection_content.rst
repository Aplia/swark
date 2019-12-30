ezselection_content
-------------------

Summary
~~~~~~~
Alternative for getting object attribute content from ezselection attribute. The default content always returns an array of values, which only contains id's, whereas this operator returns an array of [<id> => <name>].

In the case of single select, returns the name directly. Bypass this to return id-name-array with the named parameter 'with_id_as_key' to true. This parameter is irrelevant for multiselects.

Usage
~~~~~
::

    field|ezselection_content([true])


Parameters
~~~~~~~~~~
    =============== =============================================================== ======== =======
    Name            Description                                                     Required Default
    =============== =============================================================== ======== =======
    field           eZSelection field from data_map. Do note that we do not pass in Yes
                    the content, and instead the entire field.
    with_id_as_key  Whether to include the id of the selection as key. This is      No
                    implicit for multiselect.
    =============== =============================================================== ======== =======

.. csv-table::
    :header: "Name", "Description", "Required", "Default"

    "field", "eZSelection field from data_map. Do note that we do not pass in the content, and instead the entire field.", "Yes", ""
    "with_id_as_key", "Whether to include the id of the selection as key. This is implicit for multiselect.", "No", ""

Examples
~~~~~~~~
::

    {$data_map.my_ezselection_single_field|ezselection_content}

Returns name string for selected value: '<name>'.
::

    {$data_map.my_ezselection_single_field|ezselection_content(true)}

Returns array of the one selected, with identifier as key: array(<id> => <name>).
::

    {$data_map.my_ezselection_multiple_field|ezselection_content}

Returns array of selected values: array(<id> => <name>, [...]).
