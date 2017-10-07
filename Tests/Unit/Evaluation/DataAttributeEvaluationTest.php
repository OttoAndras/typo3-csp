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

namespace AndrasOtto\Csp\Tests\Unit\Evaluation;


use AndrasOtto\Csp\Evaluation\DataAttributeEvaluation;
use TYPO3\CMS\Core\Tests\UnitTestCase;

class DataAttributeEvaluationTest extends UnitTestCase
{

    /** @var DataAttributeEvaluation  */
    protected $subject = null;

    /**
     * Setup global
     */
    public function setUp()
    {
        parent::setUp();
        $this->subject = new DataAttributeEvaluation();
    }

    /**
     * @test
     */
    public function renderFieldJSReturnsSimpleValue() {
        $this->assertEquals('return value;',
            $this->subject->returnFieldJS());
    }

    /**
     * @test
     */
    public function correctSingleValueIsAccepted() {
        $set = true;
        $this->subject->evaluateFieldValue('attr: test', '', $set);
        $this->assertEquals(true, $set);
    }

    /**
     * @test
     */
    public function correctMultiValueIsAccepted() {
        $set = true;
        $this->subject->evaluateFieldValue('attr1: test; attr2: test test test; attr2', '', $set);
        $this->assertEquals(true, $set);
    }

    /**
     * @test
     */
    public function invalidValueIsNotAccepted() {
        $set = true;
        $this->subject->evaluateFieldValue('aa<attr1>bb: test', '', $set);
        $this->assertEquals(false, $set);
    }

    /**
     * @test
     */
    public function invalidMultiValueIsNotAccepted() {
        $set = true;
        $this->subject->evaluateFieldValue('attr: test; attr2: test; aa<attr1>bb: test', '', $set);
        $this->assertEquals(false, $set);
    }

    /**
     * @test
     */
    public function trickyEmptyConfigNotSet() {
        $set = true;
        $this->subject->evaluateFieldValue(';  ;       ;', '', $set);
        $this->assertEquals(false, $set);
    }

    /**
     * @test
     */
    public function emptyValueSet() {
        $set = true;
        $this->subject->evaluateFieldValue('', '', $set);
        $this->assertEquals(true, $set);
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->subject);
    }
}