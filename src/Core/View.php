<?php

namespace Overland\Core;

class View
{
    public static function make($path, $params = [])
    {
        extract($params);
        header('Content-Type: text/html');
        ob_start();
        include($path);
        echo ob_get_clean();
        exit;
    }
}
