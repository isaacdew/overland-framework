<?php

namespace Overland\Tests\Unit;

use Overland\Core\Validator;
use PHPUnit\Framework\TestCase;

/**
 * @covers Overland\Core\Validator
 */
class ValidatorTest extends TestCase
{
    public function test_it_reuires_required_params()
    {
        $validator = $this->getMockBuilder(Validator::class)
            ->setConstructorArgs([[
                'test' => ''
            ], [
                'test' => [
                    'required' => true,
                ]
            ]])
            ->onlyMethods(['showErrors'])
            ->getMock();

        $validator->expects($this->once())->method('showErrors');

        $validator->validate();
    }

    public function test_type_validation()
    {
        $validator = $this->getMockBuilder(Validator::class)
            ->setConstructorArgs([[
                'test' => 'string'
            ], [
                'test' => [
                    'type' => 'integer',
                ]
            ]])
            ->onlyMethods(['showErrors'])
            ->getMock();

        $validator->expects($this->once())->method('showErrors');

        $validator->validate();
    }
}
