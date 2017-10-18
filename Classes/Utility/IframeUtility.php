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


use AndrasOtto\Csp\Domain\Model\Iframe;

class IframeUtility
{

    /**
     * Accepted properties.
     * Source: https://developer.mozilla.org/en-US/docs/Web/HTML/Element/iframe
     *
     * @var array
     */
    static protected $acceptedSandboxValues = [
        'allow-forms',
        'allow-modals',
        'allow-orientation-lock',
        'allow-pointer-lock',
        'allow-popups',
        'allow-popups-to-escape-sandbox',
        'allow-presentation',
        'allow-same-origin',
        'allow-scripts',
        'allow-top-navigation',
        'allow-top-navigation-by-user-activation'
    ];

    /**
     * @param array $conf A config array with the possible values of src|class|name|width|height|sandbox
     * @return string
     */
    static public function generateIframeTagFromConfigArray($conf){
        $src = isset($conf['src']) ? $conf['src'] : '';
        $class = isset($conf['class']) ? $conf['class'] : '';
        $name = isset($conf['name']) ? $conf['name'] : '';
        $width = isset($conf['width']) ? $conf['width'] : 0;
        $height = isset($conf['height']) ? $conf['height'] : 0;
        $sandbox = isset($conf['sandbox']) ? $conf['sandbox'] : '';
        $allowFullScreen = isset($conf['allowFullScreen']) ? $conf['allowFullScreen'] : '';
        $allowPaymentRequest = isset($conf['allowPaymentRequest']) ? $conf['allowPaymentRequest'] : '';
        $dataAttributes = isset($conf['dataAttributes']) ? $conf['dataAttributes'] : '';


        $iframe = new Iframe($src, $class, $name, $width, $height, $sandbox, $allowFullScreen, $allowPaymentRequest, $dataAttributes);

        return $iframe->generateHtmlTag();
    }
}