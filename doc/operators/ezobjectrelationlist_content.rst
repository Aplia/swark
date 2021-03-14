ezobjectrelationlist_content
-------------------

Summary
~~~~~~~
Alternative for getting ezobjectrelationlist content as a list of nodes, instead of an array which does not have the nodes.

The order of the returned nodes are the same as `$attribute.content.relation_list` order.

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

Returns an array of nodes, or an empty array if there is no content: `array(eZContentObjectTreeNode)|eZContentObjectTreeNode`.
