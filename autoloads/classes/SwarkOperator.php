<?php
//
// Swark - extension for eZ Publish
// Author: Jan Kudlicka <jk@seeds.no>
// Copyright (C) 2008 Seeds Consulting AS, http://www.seeds.no/
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of version 2.0 of the GNU General
// Public License as published by the Free Software Foundation.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
// MA 02110-1301, USA.
//

use Swark\Exceptions\SwarkParameterNotFound;

class SwarkOperator
{
    private $OperatorName = false;
    private $Parameters;

    function __construct( $operatorName = false )
    {
        $this->OperatorName = $operatorName;

        $parameters = func_get_args();
        array_shift( $parameters );
        $this->Parameters = array();
        foreach ( $parameters as $idx => $parameter )
        {
            // Parameter is either a string with name or name=default-string
            // Or an associative array with ['name' => <name>] or ['name' => <name>, 'default' => <default>]
            // The associative array may also have a 'type' set, this is then used to verify parameters when called
            if (is_array($parameter)) {
                if (!isset($parameter['name'])) {
                    throw new Exception("Operator $operatorName: Parameter at index $idx was defined with an array, but did not define a 'name'");
                }
                $parameterName = $parameter['name'];
                if (array_key_exists('default', $parameter)) {
                    $defaultValue = $parameter['default'];
                    $parameterOptions = array( 'type' => $defaultValue === null ? null : gettype($defaultValue), 'required' => false, 'default' => $defaultValue );
                } else {
                    $parameterOptions = array( 'type' => 'mixed', 'required' => true);
                }
                if (isset($parameter['type'])) {
                    $parameterOptions['type'] = $parameter['type'];
                }
            } else {
                $parameterArray = explode( '=', $parameter, 2 );
                $parameterName = $parameterArray[0];
                if ( count( $parameterArray ) == 2 ) {
                    $defaultValue = $parameterArray[1];
                    $parameterOptions = array('type' => 'mixed', 'required' => false, 'default' => $defaultValue);
                } else {
                    $parameterOptions = array('type' => 'mixed', 'required' => true);
                }
            }
            $this->Parameters[$parameterName] = $parameterOptions;
        }
    }

    function operatorList()
    {
        return array( $this->OperatorName );
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array( $this->OperatorName => $this->Parameters );
    }

    static function execute( $operatorValue, $namedParameters )
    {
        return $operatorValue;
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace,
                     $currentNamespace, &$operatorValue, $namedParameters )
    {
        $operatorValue = call_user_func( array( get_class( $this ), 'execute' ), $operatorValue, $namedParameters );
    }

    function operatorTemplateHints()
    {
        return array( $this->OperatorName => array( 'parameters' => true,
                                                    'input' => true,
                                                    'output' => true,
                                                    'element-transformation' => true,
                                                    'transform-parameters' => true,
                                                    'input-as-parameter' => 'always',
                                                    'element-transformation-func' => 'transformation' ) );
    }

    function operators()
    {
        return SwarkOperators::operators();
    }

    function transformation( $operatorName, $node, $tpl, $resourceData,
                             $element, $lastElement, $elementList, $elementTree, $parameters )
    {
        $operators = $this->operators();
        $operatorInfo = $operators[$operatorName];
        $namedParametersVarName = '$namedParameters' . md5( mt_rand() );

        $code = "unset( $namedParametersVarName );\n$namedParametersVarName = array();\n";
        $index = 2;
        foreach( $this->Parameters as $parameterName => $parameter )
        {
            if ( isset( $parameters[$index-1] ) )
            {
                if ($parameter['type'] !== null && $parameter['type'] !== 'mixed') {
                    $code .= "if (gettype(%$index%) !== " . var_export($parameter['type'], true) . ") { throw new Exception(\"eZ tpl operator '{$this->OperatorName}': Expected type {$parameter['type']} for parameter '$parameterName', got: \" . gettype(%$index%)); };\n";
                }
                $code .= "{$namedParametersVarName}['$parameterName'] = %$index%;\n";
            }
            else
            {
                if ($parameter['required']) {
                    throw new SwarkParameterNotFound("Parameter $parameterName is required");
                }
                $code .= "{$namedParametersVarName}['$parameterName'] = " . var_export( isset($parameter['default']) ? $parameter['default'] : null, true ) . ";\n";
            }
            $index++;
        }
        $code .= "%output% = ${operatorInfo['class']}::execute( %1%, $namedParametersVarName );\n";
        $code .= "unset( $namedParametersVarName );\n";

        return array( eZTemplateNodeTool::createCodePieceElement( $code, $parameters ) );
    }
}
