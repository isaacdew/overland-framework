<?php

namespace Overland\Tests\Unit;

use Overland\Core\View;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Overland\Core\View
 */
class ViewTest extends TestCase
{
    public function test_it_renders_view()
    {
        ob_start();
        View::make(OVERLAND_PLUGIN_ROOT . '/stubs/view.php', ['name' => 'Isaac'], true);

        $view = ob_get_clean();

        $this->assertEquals('<span>Hello, Isaac</span>', $view);
    }
}