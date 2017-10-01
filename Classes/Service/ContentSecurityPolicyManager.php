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

namespace AndrasOtto\Csp\Service;


use Phpcsp\Security\ContentSecurityPolicyHeaderBuilder;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class ContentSecurityPolicyManager implements SingletonInterface
{

    const DIRECTIVE_POSTFIX = "-src";

   static protected $enabledPreSets = [
        'googleAnalytics',
        'googleMaps',
        'googleFonts',
        'youTube',
        'vimeo',
        'jQuery'
    ];

    /** @var  ContentSecurityPolicyHeaderBuilder */
    static private $headerBuilder = null;

    /**
     * Returns a ContentSecurityPolicyHeaer Builder instance
     *
     * @return ContentSecurityPolicyHeaderBuilder
     */
    static public function getBuilder() {
        if(!self::$headerBuilder) {
            self::$headerBuilder = new ContentSecurityPolicyHeaderBuilder();
        }
        return self::$headerBuilder;
    }


    /**
     * @param TypoScriptFrontendController $tsfe
     */
    static public function addTypoScriptSettings($tsfe) {

        $enabled = $tsfe->config['config']['csp.']['enabled'] ?? false;

        if($enabled) {

            $builder = self::getBuilder();

            $config = $tsfe->config['config']['csp.'];

            if(isset($config['additionalDomains.'])) {
                foreach ($config['additionalDomains.'] as $directive => $preSets) {
                    foreach ($preSets as $preSet) {
                        $builder->addSourceExpression(rtrim($directive, '.') . self::DIRECTIVE_POSTFIX, $preSet);
                    }
                }
            }

            foreach (self::$enabledPreSets as $enabledPreSet) {
                $preSetEnabed = $config[$enabledPreSet . '.']['enabled'] ?? false;
                if($preSetEnabed
                    && isset($config[$enabledPreSet . '.']['rules.'])) {

                    foreach ($config[$enabledPreSet . '.']['rules.'] as $directive => $value) {
                        $builder->addSourceExpression($directive . self::DIRECTIVE_POSTFIX, $value);
                    }
                }
            }
        }
    }

    /**
     * @return string
     */
    static public function extractHeaders() {
        $responseHeader = '';
        $headers = self::getBuilder()->getHeaders('include');
        if(count($headers) > 0) {
            $name = $headers[0]['name'] ?? '';
            $value = $headers[0]['value'] ?? '';
            
            if($name) {
                $responseHeader = $name . ': ' . $value;
            }
        }
        
        return $responseHeader;
    }
}