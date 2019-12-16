<?php
namespace Swark\TemplateOperators;

use eZSys;
use eZTemplateDesignResource;
use eZTemplate;
use SwarkOperator;
use Swark\Exceptions\TemplateResourceNotFound;

/**
 * A template operator which allows for embedding files found in the 'design' folders
 * of any extension. This is similar to the `ezdesign` operator but will embed
 * the contents of the file.
 * 
 * The file is specified using the relative path from a design folder and the
 * operator will find the first file that matches according to the design order.
 * For instance `javascript/code.js` could be resolved to `extension/site/design/site/javscript.code.js`.
 * 
 * If the parameter `element` is `true` then it will create the HTML element
 * for embedding if the file format is known. Currently supports:
 * 
 * - .js - Adds a <script> tag
 * - .css - Adds a <style> tag
 */
class EmbedDesignFile extends SwarkOperator
{
    function __construct()
    {
        parent::__construct('embed_design_file', 'path', 'element=');
    }

    /**
     * Locates the file in the design folders and returns the file path.
     *
     * @param eZTemplate $tpl Template instance
     * @param string $designPath Relative path to file, e.g. 'javascript/code.js'
     * @return void
     */
    static public function findDesignFile($tpl, $designPath)
    {
        $sys = eZSys::instance();

        $bases = eZTemplateDesignResource::allDesignBases();
        $triedFiles = array();
        $fileInfo = eZTemplateDesignResource::fileMatch($bases, false, $designPath, $triedFiles);

        if ( !$fileInfo )
            throw new TemplateResourceNotFound($designPath);

        $filePath = $fileInfo['path'];

        return $filePath;
    }

    static public function execute($operatorValue, $namedParameters)
    {
        $tpl = eZTemplate::factory();
        $designPath = $namedParameters['path'];
        list($path, $text) = self::loadFile($tpl, $designPath);
        if ($namedParameters['element']) {
            if ($path) {
                if (substr($path, -3) == ".js") {
                    $text = "<script type=\"text/javascript\">$text</script>";
                } else if (substr($path, -4) == ".css") {
                    $text = "<style>$text</style>";
                }
            }
        }
        return $text;
    }

    /**
     * Load file content and return an array with  file path and content.
     *
     * @param eZTemplate $tpl Template instance
     * @param string $designPath Relative path to file, e.g. 'javascript/code.js'
     * @throws TemplateResourceNotFound if the template resource could not be found
     * @return array
     */
    static public function loadFile($tpl, $designPath)
    {
        try {
            $path = self::findDesignFile($tpl, $designPath);
        } catch (TemplateResourceNotFound $e) {
            // starter_logger("site")->error("template_embed_file: Failed to find template resource: $designPath");
            \eZDebug::writeError("template_embed_file: Failed to find template resource: $designPath");
            return array(null, '');
        }
        return array($path, file_get_contents($path));
    }
}
