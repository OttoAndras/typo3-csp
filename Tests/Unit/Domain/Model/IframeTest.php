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


use AndrasOtto\Csp\Domain\Model\Iframe;
use AndrasOtto\Csp\Exceptions\InvalidValueException;
use TYPO3\CMS\Core\Tests\UnitTestCase;

class IframeTest extends UnitTestCase
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
    public function generateIframeWithSrcOnly() {
        $iframe = new Iframe('http://test.de');
        $this->assertEquals('<iframe src="http://test.de"></iframe>',  $iframe->generateHtmlTag());
    }

    /**
     * @test
     */
    public function missingSrcThrowsException() {
        $this->setExpectedException(InvalidValueException::class,
            'Src must be set',
            1505656675);
        new Iframe('');
    }

    /**
     * @test
     */
    public function wrongHostInSrcThrowsException() {
        $this->setExpectedException(InvalidValueException::class,
            'Host cannot be extracted from the src value "test.de"',
            1505632671);
        new Iframe('test.de');
    }

    /**
     * @test
     */
    public function classSetCorrectlyIfProvided() {
        $iframe = new Iframe('http://test.de', 'class');
        $this->assertEquals('<iframe src="http://test.de" class="class"></iframe>',  $iframe->generateHtmlTag());
    }

    /**
     * @test
     */
    public function nameSetCorrectlyIfProvided() {
        $iframe = new Iframe('http://test.de', '', 'test');
        $this->assertEquals('<iframe src="http://test.de" name="test"></iframe>',  $iframe->generateHtmlTag());
    }

    /**
     * @test
     */
    public function notAllowedSandboxValueThrowsException() {
        $this->setExpectedException(InvalidValueException::class,
            'Not allowed value "test" for the attribute sandbox.',
            1505656673);
        new Iframe('http://test.de', '', '', 0, 0, 'test');
    }

    /**
     * @test
     */
    public function oneSanBoxValueSetCorrectlyIfProvided() {
        $iframe = new Iframe('http://test.de', '', '', 0, 0, 'allow-forms');
        $this->assertEquals('<iframe src="http://test.de" sandbox="allow-forms"></iframe>',  $iframe->generateHtmlTag());
    }

    /**
     * @test
     */
    public function multipleSanBoxValueSetCorrectlyIfProvided() {
        $iframe = new Iframe('http://test.de', '', '', 0, 0,
            'allow-forms, allow-popups,     allow-scripts');
        $this->assertEquals('<iframe src="http://test.de" sandbox="allow-forms allow-popups allow-scripts"></iframe>',
             $iframe->generateHtmlTag());
    }

    /**
     * @test
     */
    public function negativeIntegerIgnoredAsWidth() {
        $this->setExpectedException(InvalidValueException::class,
            'Width should be a positive integer or zero, "-100" given',
            1505632672);
        new Iframe('http://test.de', '', '', -100);
    }

    /**
     * @test
     */
    public function negativeIntegerIgnoredAsHeight() {
        $this->setExpectedException(InvalidValueException::class,
            'Height should be a positive integer or zero, "-100" given',
            1505632672);
        new Iframe('http://test.de', '', '', 0, -100);
    }

    /**
     * @test
     */
    public function notIntegerIgnoredAsWidth() {
        $iframe = new Iframe('http://test.de', '', '', 'hundred');
        $this->assertEquals('<iframe src="http://test.de"></iframe>',  $iframe->generateHtmlTag());
    }

    /**
     * @test
     */
    public function notIntegerIgnoredAsHeight() {
        $iframe = new Iframe('http://test.de', '', '', 0, 'hundred');
        $this->assertEquals('<iframe src="http://test.de"></iframe>',  $iframe->generateHtmlTag());
    }

    /**
     * @test
     */
    public function correctIntegerAcceptedAsWidth() {
        $iframe = new Iframe('http://test.de', '', '', '150');
        $this->assertEquals('<iframe src="http://test.de" width="150"></iframe>',  $iframe->generateHtmlTag());
    }

    /**
     * @test
     */
    public function correctIntegerAcceptedAsHeight() {
        $iframe = new Iframe('http://test.de', '', '', 0, '111');
        $this->assertEquals('<iframe src="http://test.de" height="111"></iframe>',  $iframe->generateHtmlTag());
    }

    /**
     * @test
     */
    public function allowPaymentRequestCanSetCorrectly() {
        $iframe = new Iframe('http://test.de', '', '', 0, 0, '', true);
        $this->assertEquals('<iframe src="http://test.de" allowpaymentrequest="allowpaymentrequest"></iframe>',
             $iframe->generateHtmlTag());
    }

    /**
     * @test
     */
    public function allowFullScreenCanSetCorrectly() {
        $iframe = new Iframe('http://test.de', '', '', 0, 0, '', 0, true);
        $this->assertEquals('<iframe src="http://test.de" allowfullscreen="allowfullscreen"></iframe>',
             $iframe->generateHtmlTag());
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}