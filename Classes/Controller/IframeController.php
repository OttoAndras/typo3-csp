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

namespace AndrasOtto\Csp\Controller;


use AndrasOtto\Csp\Utility\IframeUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class IframeController extends ActionController
{

    /**
     * Renders an Iframe tag as a string.
     * There is no view for this action!
     *
     * @return string
     */
    public function renderAction() {
        $iframe = '';

        if(isset($this->settings['iframe'])
            && is_array($this->settings['iframe'])) {
            $iframe = IframeUtility::generateIframeTagFromConfigArray($this->settings['iframe']);
        }

        return $iframe;
    }

}