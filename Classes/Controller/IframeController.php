<?php
/**
 * Created by PhpStorm.
 * User: ottoa
 * Date: 18/09/2017
 * Time: 21:42
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