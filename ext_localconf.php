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


if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['csp_header_cache'])) {

    $defaultLifetime = (isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_page']['options']['defaultLifetime'])
        ? $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_page']['options']['defaultLifetime'] : 86400);

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['csp_header_cache'] = [
        'backend' => \TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend::class,
        'frontend' => \TYPO3\CMS\Core\Cache\Frontend\StringFrontend::class,
        'groups' => ['pages', 'all'],
        'options' => [
            'defaultLifetime' => $defaultLifetime
        ]
    ];
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'][] =
    \AndrasOtto\Csp\Hooks\TypoScriptFrontendControllerHook::class . '->contentPostProcAll';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem']['csp'] =
    'EXT:csp/Classes/Hooks/PageLayoutView.php:AndrasOtto\Csp\Hooks\PageLayoutView';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][\AndrasOtto\Csp\Evaluation\DataAttributeEvaluation::class] = '';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_adminpanel.php']['extendAdminPanel'][] =
    \AndrasOtto\Csp\Hooks\AdminPanelViewHook::class;

$GLOBALS['TYPO3_CONF_VARS']['FE']['ContentObjects'] = array_merge($GLOBALS['TYPO3_CONF_VARS']['FE']['ContentObjects'], [
    "SCRIPT" => \AndrasOtto\Csp\ContentObject\ScriptContentObject::class
]);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'AndrasOtto.csp',
    'IframePlugin',
    [
        'Iframe' => 'render'
    ],
    []
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    'mod {
     wizards.newContentElement.wizardItems.special {
       elements {
         csp_iframe {
           icon = ' . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('csp') . 'Resources/Public/Backend/Images/csp.png
           title = LLL:EXT:csp/Resources/Private/Language/newContentElements.xlf:iframe.title
           description = LLL:EXT:csp/Resources/Private/Language/newContentElements.xlf:iframe.description
           tt_content_defValues {
             CType = list
             list_type = csp_iframeplugin
           }
         }
       }
       show := addToList(csp_iframe)
     }
   }'
);