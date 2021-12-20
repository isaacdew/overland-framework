<?php

namespace Overland\Tests\Unit;

use PHPUnit\Framework\TestCase;

use Overland\Core\Controller;
use Overland\Tests\Traits\DatabaseTransactions;

/**
 * @covers \Overland\Core\Controller
 * @uses \Overland\Core\Validator
 */
class ControllerTest extends TestCase {
    use DatabaseTransactions;
    /**
     * @covers \Overland\Core\Controller::can
     * @covers \Overland\Core\Controller::authorize
     * @covers \Overland\Core\Controller::response
     */
    public function test_it_can_authorize_requests() {
        $user = wp_create_user('testuser', 'password');

        wp_set_current_user($user);

        $controller = $this->getMockBuilder(FakeController::class)
            ->onlyMethods(['can', 'response'])
            ->getMock();

        $controller->expects($this->once())->method('can');
        $controller->expects($this->once())->method('response');

        $controller->test();
    }

    /**
     * @covers \Overland\Core\Controller::validate
     */
    public function test_it_can_validate_data() {
        $controller = new FakeController();

        $output = $controller->validationTest();

        $this->assertArrayNotHasKey('test', $output);
        $this->assertArrayHasKey('data', $output);
    }
}

class FakeController extends Controller {
    public function test() {
        $this->authorize('edit_posts');
    }

    public function validationTest() {
        return $this->validate([
            'data' => 1,
            'test' => 'string'
        ], [
            'data' => [
                'type' => 'integer'
            ]
        ]);
    }
}
