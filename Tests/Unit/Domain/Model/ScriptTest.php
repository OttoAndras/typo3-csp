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

namespace AndrasOtto\Csp\Tests\Unit\Model;


use AndrasOtto\Csp\Constants\HashTypes;
use AndrasOtto\Csp\Domain\Model\Script;
use AndrasOtto\Csp\Exceptions\InvalidValueException;
use AndrasOtto\Csp\Service\ContentSecurityPolicyManager;
use AndrasOtto\Csp\Tests\Unit\AbstractUnitTest;

class ScriptTest extends AbstractUnitTest
{

    /**
     * Setup global
     */
    public function setUp()
    {
        ContentSecurityPolicyManager::resetBuilder();
        parent::setUp();

    }

    /**
     * @test
     */
    public function generateScriptsCorrectly() {
        $script = new Script('    
        alert("fine");    
        ');
        $this->assertEquals('<script>alert("fine");</script>',  $script->generateHtmlTag());
    }

    /**
     * @test
     */
    public function generateScriptsWithNoTrimScriptSet() {
        $script = new Script('    
        alert("fine");    
        ', HashTypes::SHA_256, false);

        $this->assertEquals('<script>    
        alert("fine");    
        </script>',  $script->generateHtmlTag());
    }

    /**
     * @test
     */
    public function notAllowedSandboxValueThrowsException() {
        $this->setExpectedException(InvalidValueException::class,
            'Only the values "sha256" and "sha512" are supported, "test" given',
            1505745612);
        new Script('alert("fine")', 'test');
    }

    /**
     * @test
     */
    public function sha256AddedCorrectly() {
        $script = new Script('var foo = "314"');
        $script->generateHtmlTag();
        $headers = ContentSecurityPolicyManager::extractHeaders();
        $this->assertEquals(
            'Content-Security-Policy: script-src \'sha256-gPMJwWBMWDx0Cm7ZygJKZIU2vZpiYvzUQjl5Rh37hKs=\';',
            $headers);

    }

    /**
     * @test
     */
    public function sha512AddedCorrectly() {
        $script = new Script('var foo = "314"', HashTypes::SHA_512);
        $script->generateHtmlTag();
        $headers = ContentSecurityPolicyManager::extractHeaders();
        $this->assertEquals(
            'Content-Security-Policy: script-src \'sha512-gqJ6LLaGT566XoMMIbnXj8qX7PZLJBPRQ+iLa0i6dp9SKcBVf8+PeiGsq1mGb/07i6lDr1CvTL0d7EoRnBGNVg==\';',
            $headers);

    }

    /**
     * @test
     */
    public function testNonceModeForScript() {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['csp'] = 'a:1:{s:12:"scriptMethod";s:1:"1";}';
        ContentSecurityPolicyManager::reloadConfig();
        $nonce = ContentSecurityPolicyManager::getNonce();
        $script = new Script('var foo = "314"', HashTypes::SHA_512);
        $script->generateHtmlTag();
        $headers = ContentSecurityPolicyManager::extractHeaders();
        $this->assertEquals(
            'Content-Security-Policy: script-src \'nonce-'. $nonce .'\';',
            $headers);

        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['csp'] = '';
        ContentSecurityPolicyManager::reloadConfig();
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}