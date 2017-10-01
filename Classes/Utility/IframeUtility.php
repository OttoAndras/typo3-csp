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

namespace AndrasOtto\Csp\Utility;


use AndrasOtto\Csp\Service\ContentSecurityPolicyManager;
use Phpcsp\Security\ContentSecurityPolicyHeaderBuilder;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException;

class IframeUtility
{

    /**
     * Generates HTML5 Iframe object.
     *
     * @param string $src
     * @param string $name
     * @param string $class
     * @param int $width
     * @param int $height
     * @param string $sandbox
     * @param bool $allowPaymentRequest
     * @param bool $allowFullScreen
     * @throws InvalidArgumentValueException
     * @return string
     */
    static public function generateIframeTag($src,
                                             $class = '',
                                             $name = '',
                                             $width = 0,
                                             $height = 0,
                                             $sandbox = '',
                                             $allowPaymentRequest = false,
                                             $allowFullScreen = false) {
        $attributes = [];

        if(!$src) {
            throw new InvalidArgumentValueException('Src must be provided.', 1505632669);
        }

        $host = parse_url($src, PHP_URL_HOST);

        if(!$host) {
            throw new InvalidArgumentValueException('Host could not be extracted from src', 1505632673);
        }

        ContentSecurityPolicyManager::getBuilder()->addSourceExpression(
            ContentSecurityPolicyHeaderBuilder::DIRECTIVE_FRAME_SRC, $host);

        if($name) {
            $attributes['name'] = $name;
        }

        if(intval($width) && $width > 0) {
            $attributes['width'] = $width;
        }

        if(intval($height) && $height > 0) {
            $attributes['height'] = $height;
        }

        if($sandbox) {
            $attributes['sandbox'] = preg_replace('/,/', ' ', $sandbox);
        }

        if($class) {
            $attributes['class'] = $class;
        }

        if($allowPaymentRequest) {
            $attributes['allowpaymentrequest'] = 'allowpaymentrequest';
        }

        if($allowFullScreen) {
            $attributes['allowfullscreen'] = 'allowfullscreen';
        }

        $iframe = sprintf('<iframe src="%s" ', $src);

        foreach ($attributes as $attributeName => $value) {
            $iframe .= sprintf('%s="%s" ', $attributeName, $value);
        }

        $iframe .= '></iframe>';

        return $iframe;
    }

    /**
     * @param array $conf A onfig array with the possible values of src|class|name|width|height|sandbox
     * @return string
     */
    static public function generateIframeTagFromConfigArray($conf){
        $src = $conf['src'] ?? '';
        $class = $conf['class'] ?? '';
        $name = $conf['name'] ?? '';
        $width = $conf['width'] ?? 0;
        $height = $conf['height'] ?? 0;
        $sandbox = $conf['sandbox'] ?? '';

        return self::generateIframeTag($src, $class, $name, $width, $height, $sandbox);
    }
}