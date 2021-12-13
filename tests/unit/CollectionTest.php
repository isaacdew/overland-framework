<?php

namespace Overland\Tests;

use Overland\Core\Interfaces\Collection;
use PHPUnit\Framework\TestCase;

/**
 * @covers Collection
 */
class CollectionTest extends TestCase {
    public function test_it_implements_array_access() {
        $collection = new Collection([10, 15, 12]);

        $this->assertEquals(12, $collection[2]);
    }
}
