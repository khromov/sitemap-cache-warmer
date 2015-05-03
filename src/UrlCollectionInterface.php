<?php
namespace Khromov\Cache_Warmer;

/**
 * Models a simple URL Collection.
 *
 * Interface UrlCollection
 * @package Khromov\Cache_Warmer
 */
interface UrlCollectionInterface
{
    public function __construct($config, $logger);
    public function get_urls();
}