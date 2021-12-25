<?php

namespace Overland\Tests\Unit;

use Overland\Core\Response;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @covers \Overland\Core\Response
 */
class ResponseTest extends TestCase
{
    public function test_status_sets_http_status()
    {
        Response::create()->status(500)->test();

        $this->assertEquals(500, http_response_code());
    }

    public function test_it_can_output_body_as_json()
    {
        ob_start();
        Response::create([ 'key' => 'value' ])->json()->test();
        $body =  ob_get_clean();

        $this->assertEquals('{"key":"value"}', $body);
    }
}
