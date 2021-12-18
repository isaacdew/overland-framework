<?php

namespace Overland\Tests\Unit;

use Overland\Core\Interfaces\Collection;
use PHPUnit\Framework\TestCase;

/**
 * @covers Collection
 */
class CollectionTest extends TestCase {
    protected $collection;

    public function setUp(): void {
        $this->collection = new Collection([10, 15, 12]);
    }

    public function test_it_implements_array_access() {
        $this->assertEquals(12, $this->collection[2]);

        $this->collection[2] = 13;

        $this->assertEquals(13, $this->collection[2]);

        $count = $this->collection->count();
        unset($this->collection[0]);

        $this->assertCount($count - 1, $this->collection);
    }

    public function test_it_implements_iterator() {
        $this->assertIsIterable($this->collection);
    }

    public function test_can_push_new_items() {
        $this->collection->push(365);

        $this->assertContains(365, $this->collection);
    }
}
