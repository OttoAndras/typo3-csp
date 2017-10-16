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

use AndrasOtto\Csp\Exceptions\InvalidValueException;
use AndrasOtto\Csp\ViewHelpers\IframeViewHelper;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContext;

class IframeViewHelperTest extends UnitTestCase
{

    /** @var IframeViewHelper  */
    protected $subject = null;
    /**
     * Setup global
     */
    public function setUp()
    {
        parent::setUp();
        $this->subject = GeneralUtility::makeInstance(IframeViewHelper::class);
        $renderingContext = GeneralUtility::makeInstance(RenderingContext::class);
        $this->subject->setRenderingContext($renderingContext);
    }

    /**
     * @test
     */
    public function throwsExceptionWithEmptySrc() {
        $this->setExpectedException(InvalidValueException::class);
        $this->subject->render('');
    }

    /**
     * @test
     */
    public function rendersIframeTagCorrectly() {
        $iframeMarkup = $this->subject->render(
            'https://test.de',
            'test-class multiple',
            'conf-test',
            150,
            160,
            'allow-forms, allow-popups',
            1,
            1
            );
        $this->assertEquals(
            '<iframe src="https://test.de" name="conf-test" class="test-class multiple" width="150" height="160" sandbox="allow-forms allow-popups" allowfullscreen="allowfullscreen" allowpaymentrequest="allowpaymentrequest"></iframe>',
            $iframeMarkup);
    }


    public function tearDown()
    {
        parent::tearDown();
        unset($this->subject);
    }
}