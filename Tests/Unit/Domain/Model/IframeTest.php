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

    protected $iframe = null;
    /**
     * Setup global$
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function generateIframeWithSrcOnly() {
        $this->iframe = new Iframe('http://test.de');
        $this->assertEquals('<iframe src="http://test.de"></iframe>',  $this->iframe->generateHtmlTag());
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
        $this->iframe = new Iframe('http://test.de', 'class');
        $this->assertEquals('<iframe src="http://test.de" class="class"></iframe>',  $this->iframe->generateHtmlTag());
    }

    /**
     * @test
     */
    public function nameSetCorrectlyIfProvided() {
        $this->iframe = new Iframe('http://test.de', '', 'test');
        $this->assertEquals('<iframe src="http://test.de" name="test"></iframe>',  $this->iframe->generateHtmlTag());
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
        $this->iframe = new Iframe('http://test.de', '', '', 0, 0, 'allow-forms');
        $this->assertEquals('<iframe src="http://test.de" sandbox="allow-forms"></iframe>',  $this->iframe->generateHtmlTag());
    }

    /**
     * @test
     */
    public function multipleSanBoxValueSetCorrectlyIfProvided() {
        $this->iframe = new Iframe('http://test.de', '', '', 0, 0,
            'allow-forms, allow-popups,     allow-scripts');
        $this->assertEquals('<iframe src="http://test.de" sandbox="allow-forms allow-popups allow-scripts"></iframe>',
             $this->iframe->generateHtmlTag());
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
        $this->iframe = new Iframe('http://test.de', '', '', 'hundred');
        $this->assertEquals('<iframe src="http://test.de"></iframe>',  $this->iframe->generateHtmlTag());
    }

    /**
     * @test
     */
    public function notIntegerIgnoredAsHeight() {
        $this->iframe = new Iframe('http://test.de', '', '', 0, 'hundred');
        $this->assertEquals('<iframe src="http://test.de"></iframe>',  $this->iframe->generateHtmlTag());
    }

    /**
     * @test
     */
    public function correctIntegerAcceptedAsWidth() {
        $this->iframe = new Iframe('http://test.de', '', '', '150');
        $this->assertEquals('<iframe src="http://test.de" width="150"></iframe>',  $this->iframe->generateHtmlTag());
    }

    /**
     * @test
     */
    public function correctIntegerAcceptedAsHeight() {
        $this->iframe = new Iframe('http://test.de', '', '', 0, '111');
        $this->assertEquals('<iframe src="http://test.de" height="111"></iframe>',  $this->iframe->generateHtmlTag());
    }

    /**
     * @test
     */
    public function allowPaymentRequestCanSetCorrectly() {
        $this->iframe = new Iframe('http://test.de', '', '', 0, 0, '', true);
        $this->assertEquals('<iframe src="http://test.de" allowpaymentrequest="allowpaymentrequest"></iframe>',
             $this->iframe->generateHtmlTag());
    }

    /**
     * @test
     */
    public function allowFullScreenCanSetCorrectly() {
        $this->iframe = new Iframe('http://test.de', '', '', 0, 0, '', 0, true);
        $this->assertEquals('<iframe src="http://test.de" allowfullscreen="allowfullscreen"></iframe>',
             $this->iframe->generateHtmlTag());
    }

    /**
     * @test
     */
    public function allowPaymentRequestCanBeSet() {
        $this->iframe = new Iframe('http://test.de');
        $this->iframe->setAllowPaymentRequest("1");
        $this->assertEquals(true,
            $this->iframe->isAllowPaymentRequest());
    }


    /**
     * @test
     */
    public function allowFullScreenCanBeSet() {
        $this->iframe = new Iframe('http://test.de');
        $this->iframe->setAllowFullScreen(true);
        $this->assertEquals(true,
            $this->iframe->isAllowFullScreen());
    }

    /**
     * @test
     */
    public function srcCanBeChanged() {
        $this->iframe = new Iframe('https://www.test.de');
        $this->iframe->setSrc('http://www.test.de');
        $this->assertEquals('http://www.test.de', $this->iframe->getSrc());
    }

    /**
     * @test
     */
    public function srcCannotBeChangedToAnInvalidValue() {
        $this->setExpectedException(InvalidValueException::class,
            'Host cannot be extracted from the src value "test"',
            1505632671);
        $this->iframe = new Iframe('http://test.de');
        $this->iframe->setSrc('test');
    }

    /**
     * @test
     */
    public function sandboxCanBeChanged() {
        $this->iframe = new Iframe('https://www.test.de');
        $this->iframe->setSandbox('allow-popups');
        $this->assertEquals(1, count($this->iframe->getSandbox()));
    }

    /**
     * @test
     */
    public function sandboxCannotChangedToInvalidValues() {
        $this->setExpectedException(InvalidValueException::class,
            'Not allowed value "test" for the attribute sandbox.',
            1505656673);
        $this->iframe = new Iframe('http://test.de');
        $this->iframe->setSandbox('allow-popups,test');
    }

    /**
     * @test
     */
    public function heightCanBeChanged() {
        $this->iframe = new Iframe('https://www.test.de');
        $this->iframe->setHeight(11);
        $this->assertEquals(11, $this->iframe->getHeight());
    }

    /**
     * @test
     */
    public function heightCannotBeChangedToAnInvalidValue() {
        $this->setExpectedException(InvalidValueException::class,
            'Height should be a positive integer or zero, "-11" given',
            1505632672);
        $this->iframe = new Iframe('https://www.test.de');
        $this->iframe->setHeight(-11);
    }

    /**
     * @test
     */
    public function widthCanBeChanged() {
        $this->iframe = new Iframe('https://www.test.de');
        $this->iframe->setWidth(11);
        $this->assertEquals(11, $this->iframe->getWidth());
    }

    /**
     * @test
     */
    public function widthCannotBeChangedToAnInvalidValue() {
        $this->setExpectedException(InvalidValueException::class,
            'Width should be a positive integer or zero, "-13" given',
            1505632672);
        $this->iframe = new Iframe('https://www.test.de');
        $this->iframe->setWidth(-13);
    }

    /**
     * @test
     */
    public function classCanBeChanged() {

        $this->iframe = new Iframe('https://www.test.de', "test1");
        $this->iframe->setClass("test2");
        $this->assertEquals('test2', $this->iframe->getClass());
    }

    /**
     * @test
     */
    public function nameCanBeChanged() {

        $this->iframe = new Iframe('https://www.test.de', "", "test1");
        $this->iframe->setName("test2");
        $this->assertEquals('test2', $this->iframe->getName());
    }

    /**
     * @test
     */
    public function dataAttributesCanBeAdded() {

        $this->iframe = new Iframe('http://test.de', '', '', 0, 0, '', 0, false, 'test1: 1; data-test2: 2');
        $this->assertEquals('<iframe src="http://test.de" data-test1="1" data-test2="2"></iframe>',
            $this->iframe->generateHtmlTag());
    }

    /**
     * @test
     */
    public function dataAttributesCanBeChanged() {

        $this->iframe = new Iframe('http://test.de', '', '', 0, 0, '', 0, false, '');
        $this->iframe->setDataAttributes('data-test2: 2');
        $this->assertEquals('1',
            count($this->iframe->getDataAttributes()));
    }


    public function tearDown()
    {
        parent::tearDown();
        unset($this->iframe);
    }
}