<?php

use Overland\Core\App;
use PHPUnit\Framework\TestCase;

/**
 * @covers App
 */
class AppTest extends TestCase {
    public function test_it_can_create_singleton() {
        $app = new App();
        
        $callback = fn($app) =>  10 + 10; 

        $app->singleton('mySingleton', $callback);

        $this->assertEquals($callback('nothing'), $app['mySingleton']);
    }
}
