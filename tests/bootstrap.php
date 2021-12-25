<?php

if(!defined('OVERLAND_PLUGIN_ROOT')) {
    define('OVERLAND_PLUGIN_ROOT', 'tests/');
}

define('WP_TESTS_CONFIG_FILE_PATH', dirname(__FILE__) . '/wp-test-config.php');
define('WP_TESTS_PHPUNIT_POLYFILLS_PATH', dirname(__DIR__) . '/vendor/yoast/phpunit-polyfills/phpunitpolyfills-autoload.php');

require_once dirname(__DIR__) . '/vendor/wordpress/wordpress/tests/phpunit/includes/bootstrap.php';
require_once dirname(__DIR__) . '/vendor/autoload.php';

