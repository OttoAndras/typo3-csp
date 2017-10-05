<?php
namespace AndrasOtto\Csp\ContentObject;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use AndrasOtto\Csp\Utility\ScriptUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\TextContentObject;

/**
 * Contains TEXT class object.
 */
class ScriptContentObject extends TextContentObject
{

    /**
     * Default constructor.
     *
     * @param ContentObjectRenderer $cObj
     */
    public function __construct(ContentObjectRenderer $cObj)
    {
        parent::__construct($cObj);
    }

    /**
     * Rendering the cObject, TEXT
     *
     * @param array $conf Array of TypoScript properties
     * @return string Output
     */
    public function render($conf = [])
    {
        $content = parent::render($conf);

        $hashMethod = ScriptUtility::SHA_256;
        $trimScript = true;

        if(isset($conf['hashMethod'])
            && in_array($conf['hashMethod'], ScriptUtility::$allowedMethods)) {
            $hashMethod = $conf['hashMethod'];
        }

        if(isset($conf['trimScript'])) {
            $trimScript = boolval($conf['trimScript']);
        }

        $content = ScriptUtility::getValidScriptTag($content, $hashMethod, $trimScript);

        return $content;
    }
}
