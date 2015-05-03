<?php
namespace Khromov\Cache_Warmer;

/**
 * A simple URL collection consisting of of an array of URLs which is
 * passed in through the $config variable of the constructor.
 *
 * Class SitemapCollection
 * @package Khromov\Cache_Warmer
 */
class ArrayUrlCollection implements UrlCollectionInterface
{
    var $urls;
    var $logger;

    /**
     * @param $config
     * @param $logger
     */
    function __construct($config, $logger)
    {
        $this->urls = $config;
        $this->logger = $logger;
    }

    function get_urls()
    {
        return $this->urls;
    }
}