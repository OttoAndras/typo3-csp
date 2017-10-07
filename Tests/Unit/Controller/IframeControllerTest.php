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

namespace AndrasOtto\Csp\Tests\Unit\Controller;


use AndrasOtto\Csp\Controller\IframeController;
use TYPO3\CMS\Core\Tests\UnitTestCase;

class IframeControllerTest extends UnitTestCase
{

    /** @var IframeController  */
    protected $subject = null;

    /**
     * Setup global
     */
    public function setUp()
    {
        parent::setUp();
        $this->subject = new IframeController();
    }

    /**
     * @param array $settings
     */
    protected function createMockWithSettings($settings = []) {
        $reflectionClass = new \ReflectionClass(IframeController::class);

        $reflectionProperty = $reflectionClass->getProperty('settings');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->subject, $settings);

    }

    /**
     * @test
     */
    public function renderActionExists() {
        $this->createMockWithSettings();
        $this->subject->renderAction();
    }

    /**
     * @test
     */
    public function returnsCorrectIframeTag() {
        $this->createMockWithSettings(['iframe' => ['src' => 'https://www.test.com']]);
        $iframeMarkup = $this->subject->renderAction();
        $this->assertEquals('<iframe src="https://www.test.com"></iframe>',
            $iframeMarkup);
    }


    public function tearDown()
    {
        parent::tearDown();
        unset($this->subject);
    }
}