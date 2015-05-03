<?php
// TODO: Move to PSR-4
include 'src/LoggerInterface.php';
include 'src/UrlCollectionInterface.php';
include 'src/ArrayLogger.php';
include 'src/ArrayUrlCollection.php';
include 'src/Response.php';
include 'src/SitemapUrlCollection.php';
include 'src/Timer.php';
include 'src/Warmer.php';

$logger = new Khromov\Cache_Warmer\ArrayLogger();

$collection = new Khromov\Cache_Warmer\SitemapUrlCollection(
    array(
        'url' => 'http://fto.se/sitemap_index.xml'
    ),
    $logger
);

// Initialize warmer with the configuration
$warmer = new Khromov\Cache_Warmer\Warmer(array(
    'api_key' => '9f316c95a356aab49cf5e4fcf3418295',
    'sitemap' => 'http://fto.se/sitemap_index.xml',
    'time_limit' => 0,
    'sleep_time' => 0
    ),
    $collection);

$warmer->run();