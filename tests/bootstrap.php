<?php
/**
 * Bootstrap the unit testing suite.
 */

// Load Composer autoloader.
require_once __DIR__ . '/../vendor/autoload.php';

include_once __DIR__ . '/stubs/WP_CLI_Command.php';

// Set up WP_Mock.
\WP_Mock::bootstrap();
