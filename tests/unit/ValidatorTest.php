<?php

namespace Overland\Tests\Unit;

use Overland\Core\Validator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Overland\Core\Validator
 */
class ValidatorTest extends TestCase
{
    /**
     * @covers \Overland\Core\Validator::showErrors
     * @covers \Overland\Core\Validator::validate
     */
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

    /**
     * @covers \Overland\Core\Validator::showErrors
     * @covers \Overland\Core\Validator::validate
     */
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

    /**
     * @covers \Overland\Core\Validator::make
     */
    public function test_can_create_validator_using_make()
    {
        $validator = Validator::make([
            'test' => 'string'
        ], [
            'test' => [
                'type' => 'string'
            ]
        ]);

        $validator->validate();

        $this->assertInstanceOf(Validator::class, $validator);
    }
}
