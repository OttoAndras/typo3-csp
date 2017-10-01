<?php

$pluginSignature = 'csp_iframeplugin';

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature,
    'FILE:EXT:csp/Configuration/FlexForms/flexform_iframe.xml'
);