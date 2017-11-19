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

namespace AndrasOtto\Csp\Tests\Unit\Utility;

use AndrasOtto\Csp\Resource\Rendering\VimeoRenderer;
use AndrasOtto\Csp\Service\ContentSecurityPolicyManager;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\YouTubeHelper;
use TYPO3\CMS\Core\Tests\UnitTestCase;

class VimeoRendererTest extends UnitTestCase
{

    /** @var VimeoRenderer  */
    protected $subject = null;
    /**
     * Setup global
     */
    public function setUp()
    {
        parent::setUp();
        $this->subject = $this->getMock(VimeoRenderer::class, ['getOnlineMediaHelper']);
        ContentSecurityPolicyManager::resetBuilder();
    }

    /**
     * @test
     */
    public function getPriorityReturnsTen() {
        $this->assertEquals(10, $this->subject->getPriority());
    }

    /**
     * @test
     */
    public function rendersIframe() {
        $onlineMediaHelper = $this->getMock(YouTubeHelper::class, ['getOnlineMediaId'], ['youtube']);
        $onlineMediaHelper->expects($this->once())->method('getOnlineMediaId')->willReturn('test');
        $this->subject->expects($this->once())->method('getOnlineMediaHelper')->willReturn($onlineMediaHelper);

        $file = $this->getMock(File::class, [], [], '', false);

        $fileReference = $this->getMock(FileReference::class, ['getOriginalFile', 'getProperty'], [], '', false);
        $fileReference->expects($this->once())->method('getProperty')->willReturn('');
        $fileReference->expects($this->once())->method('getOriginalFile')->willReturn($file);
        $this->subject->render($fileReference, 100, 100);
        $header = ContentSecurityPolicyManager::getBuilder()->getHeader();
        $this->assertEquals('frame-src player.vimeo.com; child-src player.vimeo.com;', $header['value']);
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->subject);
    }
}