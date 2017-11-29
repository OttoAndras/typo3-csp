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

use AndrasOtto\Csp\Utility\ScriptUtility;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Fluid\Core\ViewHelper\Facets\CompilableInterface;

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
class ScriptViewHelper extends AbstractViewHelper implements CompilableInterface
{

    /**
     * Declare aliases
     *
     * @param string $hashMethod the values sha256|sha512. It defines the hash algorithm
     * @return string Rendered string
     * @throws InvalidArgumentValueException
     * @api
     */
    public function render($hashMethod = 'sha256')
    {
        return static::renderStatic(
            ['hashMethod' => $hashMethod],
            $this->buildRenderChildrenClosure(),
            $this->renderingContext
        );
    }

    /**
     * Declare aliases
     *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $output = $renderChildrenClosure();
        $hashMethod = $arguments['hashMethod'] ?? '';

        if($hashMethod) {
            $output = ScriptUtility::getValidScriptTag($output, $hashMethod, true);
        }

        return $output;
    }
}
