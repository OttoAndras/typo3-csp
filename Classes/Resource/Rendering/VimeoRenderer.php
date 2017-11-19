<?php
namespace AndrasOtto\Csp\Resource\Rendering;

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

use AndrasOtto\Csp\Domain\Model\Iframe;
use TYPO3\CMS\Core\Resource\FileInterface;

/**
 * Vimeo renderer class
 */
class VimeoRenderer extends \TYPO3\CMS\Core\Resource\Rendering\VimeoRenderer
{
    public function getPriority()
    {
        return 10;
    }

    /**
     * Render for given File(Reference) html output
     *
     * @param FileInterface $file
     * @param int|string $width TYPO3 known format; examples: 220, 200m or 200c
     * @param int|string $height TYPO3 known format; examples: 220, 200m or 200c
     * @param array $options
     * @param bool $usedPathsRelativeToCurrentScript See $file->getPublicUrl()
     * @return string
     */
    public function render(FileInterface $file, $width, $height, array $options = null, $usedPathsRelativeToCurrentScript = false)
    {
        $iframeTag = parent::render($file,$width, $height, $options, $usedPathsRelativeToCurrentScript);

        /** @var Iframe $iframe */
        $iframe = Iframe::parseSrcFromHtml($iframeTag);
        $iframe->registerSrcHost();

        return $iframeTag;
    }
}
