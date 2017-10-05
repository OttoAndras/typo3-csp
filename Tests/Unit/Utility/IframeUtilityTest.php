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


use AndrasOtto\Csp\Utility\IframeUtility;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException;

class IframeUtilityTest extends UnitTestCase
{

    /**
     * Setup global
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function generateIframeTagFromConfigArrayMapsPropertiesCorrectly(){
        $conf = [
            'src' => 'https://test.de',
            'class' => 'test-class multiple',
            'name' => 'conf-test',
            'sandbox' => 'allow-forms, allow-popups',
            'allowFullScreen' => true,
            'allowPaymentRequest' => true,
            'width' => 150,
            'height' => 160
        ];

        $iframeMarkup = IframeUtility::generateIframeTagFromConfigArray($conf);
        $this->assertEquals(
            '<iframe src="https://test.de" name="conf-test" class="test-class multiple" width="150" height="160" sandbox="allow-forms allow-popups" allowfullscreen="allowfullscreen" allowpaymentrequest="allowpaymentrequest"></iframe>',
            $iframeMarkup);
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}