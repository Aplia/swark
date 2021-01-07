ezobjectrelationlist_content
-------------------

Summary
~~~~~~~
Alternative for getting ezobjectrelationlist content as a list of nodes, instead of an array which does not have the nodes.

Will return the node directly, if only one is related.

Usage
~~~~~
::

    field|ezobjectrelationlist_content

Parameters
~~~~~~~~~~
.. list-table::
    :header-rows: 1

    * - Name
      - Description
      - Required
      - Default

    * - `field`
      - eZObjectRelationList field from data_map. Do note that we do not pass in the content, and instead the entire field.
      - Yes
      -

Examples
~~~~~~~~
::

    {$data_map.my_ezobjectrelationlist_field|ezobjectrelationlist_content}

Returns array of nodes, or the node directly: `array(eZContentObjectTreeNode)|eZContentObjectTreeNode`.
