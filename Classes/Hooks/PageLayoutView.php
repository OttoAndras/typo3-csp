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


use AndrasOtto\Csp\Domain\Model\DataAttribute;
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

        $flexform = $this->convertFlexFormToArray($row['pi_flexform']);

        if(isset($flexform['data']['main']['lDEF'])) {
            $mainSettings = $flexform['data']['main']['lDEF'];

            $attributes = ['src', 'name', 'class', 'sandbox', 'allowFullScreen', 'allowPaymentRequest', 'dataAttributes'];

            $itemContent .= $this->addAttributes($attributes, $mainSettings);

            try {
                DataAttribute::generateAttributesFromString($mainSettings['settings.iframe.dataAttributes']['vDEF']);
            } catch (\Exception $e){
                $itemContent .= '<br><span class="form-group has-error"><label class="t3js-formengine-label"></label><b>Error: </b>' . $e->getMessage() . '</span>';
            }
        }



        if(isset($flexform['data']['style']['lDEF'])) {
            $styleSettings = $flexform['data']['style']['lDEF'];

            $attributes = ['class', 'width', 'height'];

            $itemContent .= $this->addAttributes($attributes, $styleSettings);
        }

        $headerContent = '<b>Iframe</b><br>';
    }

    /**
     * Converts a flexform xml to an array
     *
     * @param string $flexForm
     * @return mixed
     */
    protected function convertFlexFormToArray($flexForm) {
        return GeneralUtility::xml2array($flexForm);
    }

    /**
     * Searches the attributes values in the settings array and adds them to the output for BE
     *
     * @param array $attributes
     * @param array $settings
     * @return string
     */
    private function addAttributes($attributes, $settings) {
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