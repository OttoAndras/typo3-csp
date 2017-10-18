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
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class TypoScriptFrontendControllerHook
{

    protected function sendHeaders($headers) {
        header($headers, true);
    }

    /**
     * Renders and caches (if cache enabled) the content security headers
     * 
     * @param $pObjArray
     */
    public function contentPostProcOutput($pObjArray)
    {
        if(isset($pObjArray['pObj'])) {
            /** @var TypoScriptFrontendController $typoScriptFrontendController */
            $typoScriptFrontendController = $pObjArray['pObj'];
            $enabled = isset($typoScriptFrontendController->config['config']['csp.']['enabled'])
                ? boolval($typoScriptFrontendController->config['config']['csp.']['enabled'])
                : false;

            if($enabled) {
                $cacheIdentifier = $typoScriptFrontendController->newHash;

                /** @var ObjectManager $objectManager */
                $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
                /** @var CacheManager $cacheManager */
                $cacheManager = $objectManager->get(CacheManager::class);
                /** @var FrontendInterface $cspHeaderCache */
                $cspHeaderCache = $cacheManager->getCache('csp_header_cache');

                ContentSecurityPolicyManager::addTypoScriptSettings($typoScriptFrontendController);

                $headers = ContentSecurityPolicyManager::extractHeaders();

                if (!$typoScriptFrontendController->no_cache) {

                    if ($cspHeaderCache->has($cacheIdentifier)) {
                        $headers = $cspHeaderCache->get($cacheIdentifier);
                    } else {

                        if ($headers) {
                            $cspHeaderCache->set($cacheIdentifier, $headers, [
                                'csp',
                                'pageId_' . $typoScriptFrontendController->page['uid']
                            ]);
                        }
                    }
                }
                if ($headers) {
                    $this->sendHeaders($headers);
                }
            }
        }        
    }
}