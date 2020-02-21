<?php
namespace Swark\TemplateOperators;

use Symfony\Component\VarDumper\VarDumper;

/**
 * Dumps variable using dump() but returns the result back to the template output
 */
class DebugDump extends \SwarkOperator
{
    static $handler;

    function __construct()
    {
        parent::__construct('dump', 'value=');
    }

    static function execute($input, $namedParameters)
    {
        $value = $input === null ? $namedParameters['value'] : $input;

        ob_start();
        VarDumper::dump($value);
        $output = ob_get_clean();
        return $output;
    }
}
