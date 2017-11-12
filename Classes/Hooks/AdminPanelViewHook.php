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


namespace AndrasOtto\Csp\Hooks;


use AndrasOtto\Csp\Constants\Directives;
use AndrasOtto\Csp\Service\ContentSecurityPolicyHeaderBuilderInterface;
use AndrasOtto\Csp\Service\ContentSecurityPolicyManager;
use TYPO3\CMS\Frontend\View\AdminPanelView;
use TYPO3\CMS\Frontend\View\AdminPanelViewHookInterface;

class AdminPanelViewHook implements AdminPanelViewHookInterface
{
    /**
     * If the admin panel we need to set the unsafe-inline and unsafe-eval values
     * to enable the javascript code of the panel.
     *
     * @param string $moduleContent Content of the admin panel
     * @param AdminPanelView $obj The adminPanel object
     * @return string Returns content of admin panel
     */
    public function extendAdminPanel($moduleContent, AdminPanelView $obj){
        /** @var ContentSecurityPolicyHeaderBuilderInterface $builder */
        $builder = ContentSecurityPolicyManager::getBuilder();

        $builder->resetDirective(Directives::SCRIPT_SRC);
        $builder->addSourceExpression(Directives::SCRIPT_SRC, 'self');
        $builder->addSourceExpression(Directives::SCRIPT_SRC, 'unsafe-inline');
        $builder->addSourceExpression(Directives::SCRIPT_SRC, 'unsafe-eval');

        if(!headers_sent()) {
            header(ContentSecurityPolicyManager::extractHeaders());
        }

        return '';
    }
}