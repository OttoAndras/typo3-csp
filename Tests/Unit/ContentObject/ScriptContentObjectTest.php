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

namespace AndrasOtto\Csp\Tests\Unit\ContentObject;


use AndrasOtto\Csp\Constants\HashTypes;
use AndrasOtto\Csp\ContentObject\ScriptContentObject;
use AndrasOtto\Csp\Domain\Model\Script;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class ScriptContentObjectTest extends UnitTestCase
{

    /** @var ScriptContentObject  */
    protected $subject = null;

    /**
     * Setup global
     */
    public function setUp()
    {
        parent::setUp();

        /** @var ContentObjectRenderer $cObj */
        $cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);

        $this->subject = new ScriptContentObject($cObj);
    }

    /**
     * Sets test values for the conf variable
     *
     * @param string $hashMethod
     * @param int $trimScript
     * @return array
     */
    private function setUpConfArray($hashMethod = HashTypes::SHA_512, $trimScript = 0) {
        $conf = [
            'value' => '  alert("ok");  ',
            'hashMethod' => $hashMethod,
            'trimScript' => $trimScript
        ];

        return $conf;
    }

    /**
     * @test
     */
    public function generateScriptTag() {
        $conf = $this->setUpConfArray();
        $scriptTag = $this->subject->render($conf);

        $this->assertEquals('<script>  alert("ok");  </script>', $scriptTag);
    }

    /**
     * @test
     */
    public function trimScriptTrimsTheTextLeftAndRight() {
        $conf = $this->setUpConfArray(HashTypes::SHA_256, 1);
        $scriptTag = $this->subject->render($conf);

        $this->assertEquals('<script>alert("ok");</script>', $scriptTag);
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->subject);
    }
}