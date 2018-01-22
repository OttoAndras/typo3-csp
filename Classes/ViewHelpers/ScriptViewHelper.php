<?php
namespace AndrasOtto\Csp\ViewHelpers;

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
use AndrasOtto\Csp\Constants\HashTypes;
use AndrasOtto\Csp\Domain\Model\Script;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use TYPO3\CMS\Fluid\Core\ViewHelper\TagBuilder;

/**
 * Renders a script tag
 *
 * Class ScriptViewHelper
 * @package AndrasOtto\Csp\ViewHelpers
 */
class ScriptViewHelper extends AbstractTagBasedViewHelper
{

    protected $tagName = 'script';

    public function setTagBuilder() {
        if(!$this->tag) {
            $this->tag = GeneralUtility::makeInstance(TagBuilder::class);
        }
    }

    /**
     * Initialize arguments.
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerUniversalTagAttributes();
        $this->registerArgument('hashMethod', 'string', 'Specifies the hash method for the CSP header', false, 'sha256');
    }

    /**
     * Renders the script tag
     *
     * @return string
     */
    public function render()
    {
        if($scriptText = $this->renderChildren()) {
            $hashMethod = $this->arguments['hashMethod'] ?? HashTypes::SHA_256;

            $script = new Script($scriptText, $hashMethod);
            $this->tag->setContent($script->getScript());
            $script->generateHtmlTag();
        }
        $this->tag->forceClosingTag(true);
        return $this->tag->render();
    }
}
