<?php

namespace Overland\Core;

class View
{
    public static function make($path, $params = [], $testing = false)
    {
        extract($params);
        if(! $testing) {
            header('Content-Type: text/html');
        }
        
        ob_start();
        include($path);
        echo ob_get_clean();

        if(! $testing) {
            exit;
        }
    }
}
