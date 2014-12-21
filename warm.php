<?php
// Set configuration
$config = include 'config.php';

// Include library
include 'class/PHP_Warmer_Timer.class.php';
include 'class/PHP_Warmer_Response.class.php';
include 'class/PHP_Warmer.class.php';

// Initialize warmer with the configuration
$warmer = new PHP_Warmer($config);
$warmer->run();