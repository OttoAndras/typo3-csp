<?php
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

namespace AndrasOtto\Csp\Utility;


use AndrasOtto\Csp\Domain\Model\Script;

class ScriptUtility
{

    /**
     * Returns a prepared script tag.
     *
     * @param $script
     * @param string $method
     * @param bool $trimScript
     * @return string
     */
    static public function getValidScriptTag($script,  $method = Script::SHA_256, $trimScript = true) {
        $scriptObj = new Script($script, $method, $trimScript);

        $scriptTag = $scriptObj->generateHtmlTag();

        unset($scriptObj);

        return $scriptTag;
    }
}