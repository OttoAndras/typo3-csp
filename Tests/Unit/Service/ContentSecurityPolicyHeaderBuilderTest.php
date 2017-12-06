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

namespace AndrasOtto\Csp\Tests\Unit\Service;


use AndrasOtto\Csp\Constants\Directives;
use AndrasOtto\Csp\Constants\HashTypes;
use AndrasOtto\Csp\Exceptions\InvalidDirectiveException;
use AndrasOtto\Csp\Exceptions\UnsupportedHashAlgorithmException;
use AndrasOtto\Csp\Service\ContentSecurityPolicyHeaderBuilder;
use AndrasOtto\Csp\Exceptions\InvalidClassException;
use AndrasOtto\Csp\Tests\Unit\AbstractUnitTest;
use TYPO3\CMS\Backend\FrontendBackendUserAuthentication;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class ContentSecurityPolicyHeaderBuilderTest extends AbstractUnitTest
{

    /** @var ContentSecurityPolicyHeaderBuilder */
    protected $subject = null;

    /**
     * Setup global
     */
    public function setUp()
    {
        parent::setUp();
        $this->subject = new ContentSecurityPolicyHeaderBuilder();
    }

    /**
     * @test
     */
    public function addNonceFillsTheDirectivesCorrectly() {
        $this->subject->addNonce(Directives::SCRIPT_SRC, 'test');
        $header = $this->subject->getHeader();
        $this->assertEquals("script-src 'nonce-test';", $header['value']);
    }

    /**
     * @test
     */
    public function addSourceFillsTheDirectivesCorrectly() {
        $this->subject->addSourceExpression(Directives::SCRIPT_SRC, 'test');
        $header = $this->subject->getHeader();
        $this->assertEquals('script-src test;', $header['value']);
    }

    /**
     * @test
     */
    public function addHashFillsTheDirectivesCorrectly() {
        $this->subject->addHash(HashTypes::SHA_384, 'test');
        $header = $this->subject->getHeader();
        $this->assertEquals("script-src 'sha384-dGVzdA==';", $header['value']);
    }


    /**
     * @test
     */
    public function checkDirectivesThrowsAnExceptionForWrongDirective() {
        $this->setExpectedException(InvalidDirectiveException::class);
        $this->subject->addSourceExpression('test', 'test');
    }

    /**
     * @test
     */
    public function addHashThrowsAnExceptionForWrongHash() {
        $this->setExpectedException(UnsupportedHashAlgorithmException::class,
            'Unsupported hash algorithm detected \'test\'');
        $this->subject->addHash('test', 'test');
    }


    /**
     * @test
     */
    public function resetDirectiveClearsTheSelectedDirective() {
        $this->subject->addHash(HashTypes::SHA_384, 'test');
        $this->subject->resetDirective(Directives::SCRIPT_SRC);
        $header = $this->subject->getHeader();
        $this->assertEquals("", $header['value']);
    }

    /**
     * @test
     */
    public function useReportOnlyChangesHeaderName() {
        $this->subject->addHash(HashTypes::SHA_384, 'test');
        $this->subject->useReportingMode();
        $header = $this->subject->getHeader();
        $this->assertEquals("Content-Security-Policy-Report-Only", $header['name']);
    }


    public function tearDown()
    {
        parent::tearDown();
    }
}