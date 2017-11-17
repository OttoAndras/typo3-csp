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

namespace AndrasOtto\Csp\Tests\Unit\Hook;


use AndrasOtto\Csp\Hooks\TypoScriptFrontendControllerHook;
use AndrasOtto\Csp\Tests\Unit\AbstractUnitTest;

class TypoScriptFrontendControllerHookTest extends AbstractUnitTest
{

    /** @var TypoScriptFrontendControllerHook  */
    protected $subject = null;

    /**
     * Setup global
     */
    public function setUp()
    {
        parent::setUp();
        $this->subject = new TypoScriptFrontendControllerHook();
    }

    public function tearDown()
    {
        unset($this->subject);
        parent::tearDown();
    }

    /**
     * @test
     */
    public function contentPostProcAllCanBeCalled() {
        $tsfe = $this->setUpFakeTsfe(1);
        $this->subject->contentPostProcAll(['pObj' => $tsfe]);
    }
}