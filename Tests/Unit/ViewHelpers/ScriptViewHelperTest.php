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

use AndrasOtto\Csp\Utility\ScriptUtility;
use AndrasOtto\Csp\ViewHelpers\ScriptViewHelper;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;

class ScriptViewHelperTest extends UnitTestCase
{

    /** @var ScriptViewHelper  */
    protected $subject = null;
    /**
     * Setup global
     */
    public function setUp()
    {
        parent::setUp();
        $this->subject = GeneralUtility::makeInstance(ScriptViewHelper::class);
        $renderingContext = GeneralUtility::makeInstance(RenderingContext::class);
        $this->subject->setRenderingContext($renderingContext);
    }

    /**
     * @test
     */
    public function throwsExceptionWithWrongHashMethod() {
        $this->setExpectedException(InvalidArgumentValueException::class);
        $this->subject->render('test');
    }

    /**
     * @test
     */
    public function rendersEmptyScriptTag() {

        $closure = function() {
            return '';
        };

        $this->subject->setRenderChildrenClosure($closure);

        $scriptMarkup = $this->subject->render(
            ScriptUtility::SHA_256
        );

        $this->assertEquals(
            "",
            $scriptMarkup);
    }


    /**
     * @test
     */
    public function rendersScriptTagCorrectly() {

        $closure = function() {
            return '
                alert(\'test\');
            ';
        };

        $this->subject->setRenderChildrenClosure($closure);

        $scriptMarkup = $this->subject->render(
            ScriptUtility::SHA_256
            );

        $this->assertEquals(
            "<script>alert('test');</script>",
            $scriptMarkup);
    }


    public function tearDown()
    {
        parent::tearDown();
        unset($this->subject);
    }
}