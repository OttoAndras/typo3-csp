<?php
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