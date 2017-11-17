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

namespace AndrasOtto\Csp\Tests\Unit\Utility;


use AndrasOtto\Csp\Service\ContentSecurityPolicyManager;
use AndrasOtto\Csp\Utility\ScriptUtility;
use TYPO3\CMS\Core\Tests\UnitTestCase;

class ScriptUtilityTest extends UnitTestCase
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
    public function scriptTagCorrectlyAttachedToScriptCode() {
        $preparedScript = ScriptUtility::getValidScriptTag('   alert("Hello!");    ');
        $this->assertEquals('<script>alert("Hello!");</script>', $preparedScript);

    }

    /**
     * @test
     */
    public function hashAddedCorrectly() {
        ScriptUtility::getValidScriptTag('var foo = "314"');
        $headers = ContentSecurityPolicyManager::extractHeaders();
        $this->assertEquals(
            'Content-Security-Policy: script-src \'sha256-gPMJwWBMWDx0Cm7ZygJKZIU2vZpiYvzUQjl5Rh37hKs=\';',
            $headers);

    }

    public function tearDown()
    {
        parent::tearDown();
    }
}