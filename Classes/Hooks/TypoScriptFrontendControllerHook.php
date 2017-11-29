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


use AndrasOtto\Csp\Service\ContentSecurityPolicyManager;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class TypoScriptFrontendControllerHook
{
    /**
     * Renders the content security headers
     * 
     * @param $pObjArray
     */
    public function contentPostProcAll($pObjArray)
    {
        if(isset($pObjArray['pObj'])) {
            /** @var TypoScriptFrontendController $typoScriptFrontendController */
            $typoScriptFrontendController = $pObjArray['pObj'];
            $enabled = boolval($typoScriptFrontendController->config['config']['csp.']['enabled'] ?? false);

            if($enabled) {

                ContentSecurityPolicyManager::addTypoScriptSettings($typoScriptFrontendController);

                $headers = ContentSecurityPolicyManager::extractHeaders();

                if ($headers && isset($typoScriptFrontendController->config['config'])) {

                    if(!isset($typoScriptFrontendController->config['config']['additionalHeaders.'])) {
                        $typoScriptFrontendController->config['config']['additionalHeaders.'] = [];
                    }

                    $typoScriptFrontendController->config['config']['additionalHeaders.'][81247]['header'] = $headers;
                }
            }
        }        
    }
}