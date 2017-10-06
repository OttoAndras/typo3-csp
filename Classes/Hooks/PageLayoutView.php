<?php
/**
 * Created by PhpStorm.
 * User: ottoa
 * Date: 05/10/2017
 * Time: 15:10
 */

namespace AndrasOtto\Csp\Hooks;


use TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PageLayoutView implements PageLayoutViewDrawItemHookInterface  {

    /**
     * Preprocesses the preview rendering of a content element.
     *
     * @param \TYPO3\CMS\Backend\View\PageLayoutView $parentObject Calling parent object
     * @param boolean $drawItem Whether to draw the item using the default functionalities
     * @param string $headerContent Header content
     * @param string $itemContent Item content
     * @param array $row Record row of tt_content
     * @return void
     */
    public function preProcess(\TYPO3\CMS\Backend\View\PageLayoutView &$parentObject, &$drawItem, &$headerContent, &$itemContent, array &$row) {

        //Make any action only in case of the correct iframe plugin
        if ($row['list_type'] !== 'csp_iframeplugin') {
            return;
        }
        $drawItem = false;

        $flexform = GeneralUtility::xml2array($row['pi_flexform']);

        if(isset($flexform['data']['main']['lDEF'])) {
            $mainSettings = $flexform['data']['main']['lDEF'];

            $attributes = ['src', 'name', 'class', 'sandbox', 'allowFullScreen', 'allowPaymentRequest'];

            $itemContent .= $this->addAttribute($attributes, $mainSettings);
        }

        if(isset($flexform['data']['style']['lDEF'])) {
            $styleSettings = $flexform['data']['style']['lDEF'];

            $attributes = ['class', 'width', 'height'];

            $itemContent .= $this->addAttribute($attributes, $styleSettings);
        }

        $headerContent = '<b>Iframe</b><br>';
    }

    /**
     * Searches the attributes values in the settings array and adds them to the output for BE
     *
     * @param array $attributes
     * @param array $settings
     * @return string
     */
    private function addAttribute($attributes, $settings) {
        $content = '';

        foreach ($attributes as $attribute) {
            if(isset($settings['settings.iframe.'. $attribute]['vDEF'])) {
                $value = htmlspecialchars($settings['settings.iframe.' . $attribute]['vDEF']);
                $content .= "<br><b>" . $attribute . ": </b><i>" . $value . "</i>";
            }
        }

        return $content;
    }
}