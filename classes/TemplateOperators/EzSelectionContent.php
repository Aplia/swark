<?php
namespace Swark\TemplateOperators;

/**
 * Alternative for getting object attribute content from ezselection attribute. The default content always returns
 * an array of values, which only contains id's, whereas this operator returns an array of [<id> => <name>].
 *
 * In the case of single select, returns the name directly. Bypass this to return id-name-array with the named
 * parameter 'with_id_as_key' to true. This parameter is irrelevant for multiselects.
 *
 * Examples:
 *      {$data_map.my_ezselection_single_field|selection_content} => '<name for selected value>'
 *      {$data_map.my_ezselection_single_field|selection_content(true)} => array(<id> => <name>)
 *      {$data_map.my_ezselection_multiple_field|selection_content} => array(<id> => <name>, [...])
 *
 */
class EzSelectionContent extends \SwarkOperator
{
    function __construct()
    {
        parent::__construct('ezselection_content', 'with_id_as_key=');
    }

    static function execute($ezSelectionField, $namedParameters)
    {
        $classContent = $ezSelectionField->attribute('contentclass_attribute')->attribute('content');

        $optionsById = [];
        foreach ($classContent['options'] as $option) {
            $optionsById[$option['id']] = $option['name'];
        }

        $return = array();
        $selectedOptions = $ezSelectionField->attribute('content');
        if ($selectedOptions !== [0 => '']) { // attribute content not unset
            foreach ($selectedOptions as $optionId) {
                $return[$optionId] = $optionsById[$optionId];
            }
        }

        if ($classContent['is_multiselect'] || $namedParameters['with_id_as_key']) {
            return $return;
        } else {
            if (count($return) == 1) { // presuming single select always has one value
                return reset($return);
            }
        }

        return null;
    }
}