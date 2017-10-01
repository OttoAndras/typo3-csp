<?php
namespace AndrasOtto\Csp\ViewHelpers;

/*                                                                        *
 * This script is backported from the TYPO3 Flow package "TYPO3.Fluid".   *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 *  of the License, or (at your option) any later version.                *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use AndrasOtto\Csp\Utility\IframeUtility;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Declares new variables which are aliases of other variables.
 * Takes a "map"-Parameter which is an associative array which defines the shorthand mapping.
 *
 * The variables are only declared inside the <f:alias>...</f:alias>-tag. After the
 * closing tag, all declared variables are removed again.
 *
 * = Examples =
 *
 * <code title="Single alias">
 * <f:alias map="{x: 'foo'}">{x}</f:alias>
 * </code>
 * <output>
 * foo
 * </output>
 *
 * <code title="Multiple mappings">
 * <f:alias map="{x: foo.bar.baz, y: foo.bar.baz.name}">
 *   {x.name} or {y}
 * </f:alias>
 * </code>
 * <output>
 * [name] or [name]
 * depending on {foo.bar.baz}
 * </output>
 *
 * @api
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
     * @return string
     * @throws InvalidArgumentValueException
     */
    public function render($src, $class = '', $name = '', $width = 0, $height = 0, $sandbox = '')
    {
        if(!$src) {
            throw new InvalidArgumentValueException('Src must be provided.', 1505632669);
        }

        return static::renderStatic(
            [
                'src' => $src,
                'class' => $class,
                'name' => $name,
                'width' => $width,
                'height' => $height,
                'sandbox' => $sandbox,
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
