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


use AndrasOtto\Csp\Service\ContentSecurityPolicyHeaderBuilderInterface;
use AndrasOtto\Csp\Service\ContentSecurityPolicyManager;
use AndrasOtto\Csp\Exceptions\InvalidClassException;
use AndrasOtto\Csp\Tests\Unit\AbstractUnitTest;
use TYPO3\CMS\Backend\FrontendBackendUserAuthentication;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class ContentSecurityPolicyManagerTest extends AbstractUnitTest
{

    /**
     * Setup global
     */
    public function setUp()
    {
        parent::setUp();
        ContentSecurityPolicyManager::resetBuilder();
    }

    /**
     * @return TypoScriptFrontendController
     */
    private function setUpFakeBeUserAuthentication($admPanelActive) {
        $beUser = new FrontendBackendUserAuthentication();

        $beUser->extAdminConfig = ['hide' => false];
        $beUser->extAdmEnabled = true;

        $GLOBALS['TSFE']->config['config']['admPanel'] = $admPanelActive;

        $GLOBALS['BE_USER'] = $beUser;
    }

    /**
     * @test
     */
    public function contentSecurityPolicyBuilderInstanceCreated() {
        $builder = ContentSecurityPolicyManager::getBuilder();
        $this->assertInstanceOf(ContentSecurityPolicyHeaderBuilderInterface::class, $builder);
    }

    /**
     * @test
     */
    public function invalidClassExceptionIfBuilderInterfaceNotImplemented() {
        $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['csp']['ContentSecurityPolicyHeaderBuilder'] =
           "AndrasOtto\\Csp\\Tests\\Unit\\Service\\ContentSecurityPolicyManagerTest";

        $this->setExpectedException(InvalidClassException::class,
            'The class "AndrasOtto\\Csp\\Tests\\Unit\\Service\\ContentSecurityPolicyManagerTest" must implement the interface ContentSecurityPolicyHeaderBuilderInterface',
            1505944587);
        ContentSecurityPolicyManager::resetBuilder();
    }

    /**
     * @test
     */
    public function sameBuilderClassUsed() {
        $builder1 = ContentSecurityPolicyManager::getBuilder();
        $builder2 = ContentSecurityPolicyManager::getBuilder();
        $this->assertSame($builder2, $builder1);
    }

    /**
     * @test
     */
    public function resetBuilderCreatesNewBuilder () {
        $builder1 = ContentSecurityPolicyManager::getBuilder();
        ContentSecurityPolicyManager::resetBuilder();
        $builder2 = ContentSecurityPolicyManager::getBuilder();
        $this->assertNotSame($builder2, $builder1);
    }

    /**
     * @test
     */
    public function extractHeadersReturnsEmptyStringByDefault() {
        ContentSecurityPolicyManager::resetBuilder();
        $headers = ContentSecurityPolicyManager::extractHeaders();

        $this->assertSame('', $headers);
    }

    /**
     * @test
     */
    public function addTypoScriptSettingsDoesNothingIfDisabled() {
        $tsfe = $this->setUpFakeTsfe();

        ContentSecurityPolicyManager::addTypoScriptSettings($tsfe);
        $headers = ContentSecurityPolicyManager::extractHeaders();

        $this->assertSame('', $headers);
    }

    /**
     * @test
     */
    public function addTypoScriptSettingsAddsCorrectPresets() {
        $tsfe = $this->setUpFakeTsfe();
        $this->setUpFakeBeUserAuthentication(false);

        $tsfe->config['config']['csp.']['enabled'] = 1;

        ContentSecurityPolicyManager::addTypoScriptSettings($tsfe);
        $headers = ContentSecurityPolicyManager::extractHeaders();

        $this->assertSame(
            'Content-Security-Policy: script-src www.google-analytics.com stats.g.doubleclick.net '
            . 'https://stats.g.doubleclick.net; img-src www.google-analytics.com '
            . 'stats.g.doubleclick.net https://stats.g.doubleclick.net;',
            $headers);
    }

    /**
     * @test
     */
    public function addTypoScriptSettingsAddsAdditionalDomains() {
        $tsfe = $this->setUpFakeTsfe();
        $this->setUpFakeBeUserAuthentication(false);
        $tsfe->config['config']['csp.']['enabled'] = 1;

        $tsfe->tmpl->setup['plugin.']['tx_csp.']['settings.']['additionalSources.'] = [
            'script' => [
                '0' => 'self',
                '10' => 'www.test.de'
            ]
        ];

        ContentSecurityPolicyManager::addTypoScriptSettings($tsfe);
        $headers = ContentSecurityPolicyManager::extractHeaders();

        $this->assertSame(
            'Content-Security-Policy: script-src \'self\' www.test.de www.google-analytics.com stats.g.doubleclick.net '
            . 'https://stats.g.doubleclick.net; img-src www.google-analytics.com '
            . 'stats.g.doubleclick.net https://stats.g.doubleclick.net;',
            $headers);
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}