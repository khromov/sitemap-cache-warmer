<?php
if(PHP_SAPI === 'cli' && $argc>1){
	parse_str(implode('&',array_slice($argv, 1)), $_GET);
}
// Set configuration
$config = include 'config.php';

// Include library
include 'class/PHP_Warmer_Timer.class.php';
include 'class/PHP_Warmer_Response.class.php';
include 'class/PHP_Warmer.class.php';

// Initialize warmer with the configuration
$warmer = new PHP_Warmer($config);
$warmer->run();