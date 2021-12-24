<?php

namespace Overland\Tests\Unit;

use PHPUnit\Framework\TestCase;

use Overland\Core\Controller;
use Overland\Core\Response;
use Overland\Tests\Traits\DatabaseTransactions;

/**
 * @covers \Overland\Core\Controller
 * @uses \Overland\Core\Validator
 * @uses \Overland\Core\Response
 */
class ControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    public function setUp(): void
    {

        $randomUsername = 'testuser' . rand(5, 999);

        $this->user = wp_create_user($randomUsername, 'password');
    }

    public function test_it_can_authorize_requests()
    {
        wp_set_current_user($this->user);

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
    public function test_it_can_validate_data()
    {
        $controller = new FakeController();

        $output = $controller->validationTest();

        $this->assertArrayNotHasKey('test', $output);
        $this->assertArrayHasKey('data', $output);
    }

    public function test_can()
    {
        wp_set_current_user($this->user);
        
        $controller = new FakeController();
        
        $this->assertFalse($controller->returnCan());
        
        add_filter('user_has_cap', function($allcaps) {
            $allcaps['test_code'] = true;
            return $allcaps;
        });

        $this->assertTrue($controller->returnCan());
    }

    public function test_response_sets_status() {
        $controller = new FakeController();

        $this->assertInstanceOf(Response::class, $controller->returnResponse());

        $this->assertEquals(403, http_response_code());
    }
}

class FakeController extends Controller
{
    public function test()
    {
        $this->authorize('edit_posts');
    }

    public function validationTest()
    {
        return $this->validate([
            'data' => 1,
            'test' => 'string'
        ], [
            'data' => [
                'type' => 'integer'
            ]
        ]);
    }

    public function returnCan()
    {
        return $this->can('test_code');
    }

    public function returnResponse() {
        return $this->response(403)->test();
    }
}
