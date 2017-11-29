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

namespace AndrasOtto\Csp\Domain\Model;


use AndrasOtto\Csp\Constants\Directives;
use AndrasOtto\Csp\Constants\HashTypes;
use AndrasOtto\Csp\Exceptions\InvalidValueException;
use AndrasOtto\Csp\Service\ContentSecurityPolicyManager;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Script extends AbstractEntity
{
    protected $allowedHashMethods = [
        HashTypes::SHA_256,
        HashTypes::SHA_384,
        HashTypes::SHA_512
    ];

    /**
     * @var string
     */
    protected $hashMethod = HashTypes::SHA_256;

    /**
     * @var string
     */
    protected $trimScript = true;

    /**
     * @var string
     */
    protected $script = '';

    /**
     * Script constructor.
     * @param string $script
     * @param string $hashMethod
     * @param bool $trimScript
     */
    public function __construct($script, $hashMethod = HashTypes::SHA_256, $trimScript = true)
    {
        $this->trimScript = boolval($trimScript);
        $this->script = $this->trimScript($script);
        $this->ensureHashMethodIsAllowed($hashMethod);
    }

    /**
     * Checks the hash method against the allowedHashMethods array
     * Sets the value if it is allowed
     *
     * @param string $hashMethod
     * @throws InvalidValueException
     */
    protected function ensureHashMethodIsAllowed($hashMethod){
        if(in_array($hashMethod, $this->allowedHashMethods)) {
            $this->hashMethod = $hashMethod;
        } else {
            throw new InvalidValueException(
                sprintf('Only the values "sha256", "sha384" and "sha512" are supported, "%s" given', $hashMethod)
                , 1505745612);
        }
    }

    /**
     * If $this->trimScript is true, minifies the script to a one line script.
     *
     * @param $script
     * @return mixed|string
     */
    protected function trimScript($script){

        if($this->trimScript) {
            $script = preg_replace("/\t/", "", $script);
            $script = preg_replace("/\n|\r\n/", "\n", $script);
            $temp = explode("\n", $script);
            $temp = array_map('trim', $temp);
            $script = implode("", $temp);
            unset($temp);
        }
        return $script;
    }

    /**
     * Calculates the hash value of the script
     *
     * @param string $script The javascript code
     * @param string $method The sha algorithm sha256 or sha512. Default sha256
     * @return string
     */
    protected function calculateScriptHash($script, $method = HashTypes::SHA_256) {
        return hash($method, $script, true);
    }

    /**
     * Returns an iframe tag as as string
     *
     * @return string
     * @throws InvalidValueException
     */
    public function generateHtmlTag(){
        $scriptTagOutput = '';

        if($this->script) {

            $scriptTagOpen = '<script>';
            $scriptTagEnd = '</script>';

            //Two supported methods are nonce and hash, it can be set in the extension configuration
            if(ContentSecurityPolicyManager::isNonceModeEnabled()) {
                $nonce = ContentSecurityPolicyManager::getNonce();

                //registers the nonce value to the script directive
                ContentSecurityPolicyManager::getBuilder()->addNonce(Directives::SCRIPT_SRC,
                    $nonce);

                $scriptTagOpen = sprintf('<script nonce="%s">', $nonce);
            } else {
                $hash = $this->calculateScriptHash($this->script, $this->hashMethod);

                //registers the hash value to the script directive
                ContentSecurityPolicyManager::getBuilder()->addHash($this->hashMethod,
                    $hash);
            }

            $scriptTagOutput = $scriptTagOpen . $this->script . $scriptTagEnd;
        }

        return $scriptTagOutput;
    }
}