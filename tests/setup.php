<?php

if(!defined('OVERLAND_PLUGIN_ROOT')) {
    define('OVERLAND_PLUGIN_ROOT', 'tests/');
}
if(!function_exists('add_action')) {
    function add_action($action, $function, $arguments = 0, $priority = 0) {}
}
if(!function_exists('add_filter')) {
    function add_filter($filter, $function, $arguments = 0, $priority = 0) {}
}

require_once dirname(__DIR__) . '/vendor/autoload.php';
