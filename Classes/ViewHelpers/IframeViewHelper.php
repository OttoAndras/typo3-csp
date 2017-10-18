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
use AndrasOtto\Csp\Utility\IframeUtility;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Renders an Iframe tag
 *
 * Class IframeViewHelper
 * @package AndrasOtto\Csp\ViewHelpers
 */
class IframeViewHelper extends AbstractViewHelper
{

    /**
     * Renders an Iframe tag
     *
     * @param string $src
     * @param string $class
     * @param string $name
     * @param int $width
     * @param int $height
     * @param string $sandbox
     * @param bool $allowFullScreen
     * @param bool $allowPaymentRequest
     * @param string $dataAttributes
     * @return string
     * @throws InvalidArgumentValueException
     */
    public function render($src,
                           $class = '',
                           $name = '',
                           $width = 0,
                           $height = 0,
                           $sandbox = '',
                           $allowFullScreen = false,
                           $allowPaymentRequest = false,
                           $dataAttributes = '')
    {

        return static::renderStatic(
            [
                'src' => $src,
                'class' => $class,
                'name' => $name,
                'width' => $width,
                'height' => $height,
                'sandbox' => $sandbox,
                'allowFullScreen' => $allowFullScreen,
                'allowPaymentRequest' => $allowPaymentRequest,
                'dataAttributes' => $dataAttributes,
            ],
            $this->buildRenderChildrenClosure(),
            $this->renderingContext
        );
    }

    /**
     * Renders an Iframe tag from the given arguments
     * Possible argument values: src, class, name, width, height, sandbox
     *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $output = IframeUtility::generateIframeTagFromConfigArray($arguments);

        return $output;
    }
}
