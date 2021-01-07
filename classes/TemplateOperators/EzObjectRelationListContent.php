<?php
namespace Swark\TemplateOperators;

/**
 * Alternative for getting ezobjectrelationlist content as a list of nodes, instead of an array which does not have the nodes.
 *
 * Examples:
 * ```eztpl
 *      {$data_map.my_ezobjectrelationlist_field|ezobjectrelationlist_content} => array(Node)
 * ```
 *
 * Will return the node directly, if only one is related.
 */
class EzObjectRelationListContent extends \SwarkOperator
{
    function __construct()
    {
        parent::__construct('ezobjectrelationlist_content');
    }

    static function execute($ezSelectionField, $namedParameters)
    {
        $classContent = $ezSelectionField->attribute('content');
        $relationList = $classContent['relation_list'];

        $nodeIds = array_map(function($node) {
            return $node['node_id'];
        }, $relationList);

        return \eZContentObjectTreeNode::fetch($nodeIds);
    }
}