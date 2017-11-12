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


use AndrasOtto\Csp\Constants\HashTypes;
use AndrasOtto\Csp\Domain\Model\Script;
use AndrasOtto\Csp\Hooks\AdminPanelViewHook;
use AndrasOtto\Csp\Service\ContentSecurityPolicyManager;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Frontend\View\AdminPanelView;

class ScriptTest extends UnitTestCase
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
    public function changeScriptDirectiveForAdminPanel() {
        ContentSecurityPolicyManager::resetBuilder();
        $script = new Script('var foo = "314"', HashTypes::SHA_512);
        $script->generateHtmlTag();

        $adminPanelHook = new AdminPanelViewHook();

        $adminViewMock = $this->getMock(AdminPanelView::class);

        $adminPanelHook->extendAdminPanel('', $adminViewMock);

        $headers = ContentSecurityPolicyManager::extractHeaders();

        $this->assertEquals(
            'Content-Security-Policy: script-src \'self\' \'unsafe-inline\' \'unsafe-eval\';',
            $headers);

    }
}