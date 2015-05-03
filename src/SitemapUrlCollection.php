<?php
namespace Khromov\Cache_Warmer;

class SitemapUrlCollection implements UrlCollectionInterface
{
    var $config;
    var $logger;

    /**
     * @param $config
     * @param $logger
     */
    function __construct($config, $logger)
    {
        $this->config = array_merge(
            array(
                'url' => false
            ),
            $config
        );

        $this->logger = $logger;
    }


    function get_urls()
    {

    }

    /**
     * Processes a sitemap
     *
     * @param $url
     * @return array
     */
    function process_sitemap($url)
    {
        // URL:s array
        $urls = array();

        // Grab sitemap and load into SimpleXML
        $sitemap_xml = @file_get_contents($url);

        if(($sitemap = @simplexml_load_string($sitemap_xml)) !== false)
        {
            // Process all sub-sitemaps
            if(count($sitemap->sitemap) > 0)
            {
                foreach($sitemap->sitemap as $sub_sitemap)
                {
                    $sub_sitemap_url = (string)$sub_sitemap->loc;

                    //TODO: Prevent infinite recursion somehow? Nesting level is usually 100 so it's not too bad.
                    $urls = array_merge($urls, $this->process_sitemap($sub_sitemap_url));
                    $this->logger->log("Processed sub-sitemap: {$sub_sitemap_url}");
                }
            }

            // Process all URL:s
            if(count($sitemap->url) > 0)
            {
                foreach($sitemap->url as $single_url)
                {
                    $urls[] = (string)$single_url->loc;
                }
            }

            return $urls;
        }
        else
        {
            $this->logger->log('Error when loading sitemap.', 'ERROR');
            return array();
        }
    }
}