<?php
namespace Khromov\Cache_Warmer;

/**
 * Class PHP_Warmer
 */
class Warmer
{
    var $config;
    var $response;
    var $timer;
    var $sleep_time;
    var $collection;

    /**
     * @param $config
     */
    function __construct($config, $collection)
    {
        $this->config = array_merge(
           array(
               'time_limit' => 0,
               'sleep_time' => 0
           ), $config
        );

        $this->collection = $collection;

        $this->sleep_time = (int)$this->get_parameter('sleep', 0);
        $this->response = new Response();
    }

    /**
     * Main function
     */
    function run()
    {
        // Disable time limit
        set_time_limit($this->config('time_limit', 0));

        //Start timer
        $timer = new Timer();
        $timer->start();

        // Discover URL links
        $urls = $this->process_sitemap($sitemap_url);

        // Visit links
        foreach($urls as $url)
        {
            $url_content = @file_get_contents($url);

            if(($this->sleep_time > 0))
                sleep($this->sleep_time);

            $this->response->set_visited_url($url);
        }

        //Stop timer
        $timer->stop();

        // Send timer data to response
        $this->response->set_duration($timer->duration());

        // Set amount of items parsed
        $this->response->set_count(sizeof($urls));

        // Done!
        if(sizeof($urls) > 0)
            $this->response->set_message("Processed sitemap: {$sitemap_url}");
        else
            $this->response->set_message("Processed sitemap: {$sitemap_url} - but no URL:s were found", 'ERROR');

        $this->response->display();
    }

    /**
     * Get a config key
     *
     * @param $key
     * @param null $default
     * @return null
     */
    function config($key, $default = null)
    {
        return isset($this->config[$key]) ? $this->config[$key] : $default;
    }

    /**
     * Get a $_GET parameter
     *
     * @param $key
     * @param string $default_value
     * @return mixed
     */
    function get_parameter($key,  $default_value = '')
    {
        return isset($_GET[$key]) ? $_GET[$key] : $default_value;
    }
}