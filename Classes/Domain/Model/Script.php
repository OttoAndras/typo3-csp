<?php
/**
 * Created by PhpStorm.
 * User: ottoa
 * Date: 05/10/2017
 * Time: 16:40
 */

namespace AndrasOtto\Csp\Domain\Model;


use AndrasOtto\Csp\Exceptions\InvalidValueException;
use AndrasOtto\Csp\Service\ContentSecurityPolicyHeaderBuilder;
use AndrasOtto\Csp\Service\ContentSecurityPolicyManager;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Script extends AbstractEntity
{
    const SHA_256 = 'sha256';
    const SHA_512 = 'sha512';

    protected $allowedHashMethods = [
        self::SHA_256,
        self::SHA_512
    ];

    /**
     * @var string
     */
    protected $hashMethod = self::SHA_256;

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
    public function __construct($script, $hashMethod = self::SHA_256, $trimScript = true)
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
                sprintf('Only the values "sha256" and "sha512" are supported, "%s" given', $hashMethod)
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
    protected function calculateScriptHash($script, $method = self::SHA_256) {
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

        $hash = $this->calculateScriptHash($this->script, $this->hashMethod);

        //registers the hash value to the script directive
        ContentSecurityPolicyManager::getBuilder()->addHash($this->hashMethod,
            $hash);

        if($this->script) {
            $scriptTagOutput = '<script>' . $this->script . '</script>';
        }

        return $scriptTagOutput;
    }
}