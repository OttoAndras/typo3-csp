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

if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

/**
 * Static TypoScript
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'csp',
    'Configuration/TypoScript',
    'Main CSP Settings'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'AndrasOtto.csp',
    'IframePlugin',
    'LLL:EXT:csp/Resources/Private/Language/backend.xlf:plugin.iframe.title',
    'LLL:EXT:csp/Resources/Private/Language/backend.xlf:plugin.iframe.description'
);

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['csp']['ContentSecurityPolicyHeaderBuilder'] =
    \AndrasOtto\Csp\Service\ContentSecurityPolicyHeaderBuilder::class;