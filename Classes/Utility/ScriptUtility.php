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


use AndrasOtto\Csp\Service\ContentSecurityPolicyManager;

class ScriptUtility
{

    const SHA_256 = 'sha256';
    const SHA_512 = 'sha512';

    static public $allowedMethods = [
        self::SHA_256,
        self::SHA_512
    ];

    /**
     * Prepares a string for the output
     * It means, that a hash value will be registered through the policy builder.
     *
     * @param string $script The javascript code
     * @param string $method The sha algorithm sha256 or sha512. Default sha256
     * @param bool $trimScript Trims the string value of the script parameter. Default true
     * @return string
     */
    static public function prepareScript($script, $method = self::SHA_256, $trimScript = true) {

        if($trimScript) {
            $script = trim($script);
        }
        if($script) {
            $hash = hash($method, $script, true);

            ContentSecurityPolicyManager::getBuilder()->addHash($method,
                $hash);
        }
        return $script;
    }

    /**
     * Returns a prepared script tag.
     *
     * @param $script
     * @param string $method
     * @param bool $trimScript
     * @see prepareScript
     * @return string
     */
    static public function getValidScriptTag($script,  $method = self::SHA_256, $trimScript = true) {
        $scriptTagOutput = '';

        $script = self::prepareScript($script, $method, $trimScript);

        if($script) {
            $scriptTagOutput = '<script>' . $script . '</script>';
        }

        return $scriptTagOutput;
    }
}