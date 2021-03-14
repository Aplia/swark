<?php
namespace Swark\TemplateOperators;

/**
 * Alternative for getting ezobjectrelationlist content as a list of nodes, instead of an array which does not have the nodes.
 *
 * The order of the returned nodes are the same as `$attribute.content.relation_list` order.
 *
 * Examples:
 * ```eztpl
 *      {$data_map.my_ezobjectrelationlist_field|ezobjectrelationlist_content} => array(Node)
 * ```
 *
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
        $nodes = [];
        $sorted = [];

        if ($relationList) {
            $nodeIds = array_map(
                function($node) {return $node['node_id'];},
                $relationList
            );

            $nodes = \eZContentObjectTreeNode::fetch($nodeIds);
            if ($nodes && !is_array($nodes)) {
                $nodes = [$nodes];
            }

            // \eZContentObjectTreeNode::fetch does not return the nodes in the order they were passed in.
            if ($nodes) {
                foreach ($nodes as $node) {
                    $sorted[array_search($node->NodeID, $nodeIds)] = $node;
                }
                ksort($sorted);
            }
        }

        return $sorted;
    }
}