<?php
// Set configuration
$config = include 'config.php';

// Include library
include 'src/PHP_Warmer_Timer.class.php';
include 'src/PHP_Warmer_Response.class.php';
include 'src/PHP_Warmer.class.php';

// Initialize warmer with the configuration
$warmer = new Khromov\Cache_Warmer\Warmer(array(
    'api_key' => '9f316c95a356aab49cf5e4fcf3418295',
    'sitemap' => 'http://fto.se/sitemap_index.xml'
));
$warmer->run();