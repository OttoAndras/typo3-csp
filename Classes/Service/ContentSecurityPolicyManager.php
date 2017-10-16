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


use AndrasOtto\Csp\Exceptions\InvalidClassException;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class ContentSecurityPolicyManager implements SingletonInterface
{

    const DIRECTIVE_POSTFIX = "-src";


    /** @var  ContentSecurityPolicyHeaderBuilder */
    static private $headerBuilder = null;

    /**
     * Returns a ContentSecurityPolicyHeaer Builder instance
     *
     * @return ContentSecurityPolicyHeaderBuilder
     */
    static public function getBuilder() {
        if(!self::$headerBuilder) {
            self::$headerBuilder = self::createNewBuilderInstance();
        }
        return self::$headerBuilder;
    }

    /**
     * Creates a ContentSecurityPolicyHeaderBuilderInterface instance through a reference
     * in the $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['csp']['ContentSecurityPolicyHeaderBuilder']
     *
     * @return ContentSecurityPolicyHeaderBuilderInterface
     * @throws InvalidClassException
     */
    static private function createNewBuilderInstance() {
        $className = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['csp']['ContentSecurityPolicyHeaderBuilder']
            ?? ContentSecurityPolicyHeaderBuilder::class;

        /** @var ContentSecurityPolicyHeaderBuilderInterface $instance */
        $instance = GeneralUtility::makeInstance($className);

        if(!($instance instanceof ContentSecurityPolicyHeaderBuilderInterface)) {
            throw new InvalidClassException(
                sprintf('The class "%s" must implement the interface ContentSecurityPolicyHeaderBuilderInterface',
                    $className),
                1505944587
            );
        }

        return $instance;
    }

    /**
     * Resets the header builder to a new instance
     *
     * @return void
     */
    static public function resetBuilder() {
        self::$headerBuilder = self::createNewBuilderInstance();
    }

    /**
     * @param TypoScriptFrontendController $tsfe
     */
    static public function addTypoScriptSettings($tsfe) {

        $enabled = $tsfe->config['config']['csp.']['enabled'] ?? false;

        if($enabled) {

            $builder = self::getBuilder();

            $config = $tsfe->tmpl->setup['plugin.']['tx_csp.']['settings.'];

            if(isset($config['additionalSources.'])) {
                foreach ($config['additionalSources.'] as $directive => $sources) {
                    foreach ($sources as $source) {
                        $builder->addSourceExpression(rtrim($directive, '.') . self::DIRECTIVE_POSTFIX, $source);
                    }
                }
            }
            if(isset($config['presets.'])
                && is_array($config['presets.'])) {

                foreach ($config['presets.'] as $preSet) {
                    $preSetEnabled = $preSet['enabled'] ?? false;
                    if ($preSetEnabled
                        && isset($preSet['rules.'])
                    ) {

                        foreach ($preSet['rules.'] as $directive => $source) {
                            $builder->addSourceExpression($directive . self::DIRECTIVE_POSTFIX, $source);
                        }
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