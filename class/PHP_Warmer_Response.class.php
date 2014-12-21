<?php
/**
 * Defines a response from the PHP Warmer class
 *
 * Class PHP_Warmer_Response
 */
class PHP_Warmer_Response
{
    var $message;
    var $status;
    var $log;
    var $visited_urls = array();
    var $duration;
    var $count;

    function __construct($message = '', $status = 'OK')
    {
        $this->set_message($message, $status);
        $this->log = array();
        $this->visited_urls = array();
    }

    function log($entry)
    {
        $this->log[] = $entry;
    }

    function display()
    {
        header('Content-Type: application/json');
        echo (json_encode(array(
            'status' => $this->status,
            'message' => $this->message,
            'count' => $this->count,
            'duration' => $this->duration,
            'log' => $this->log,
            'visited_urls' => $this->visited_urls,
        )));
    }

    function set_message($message, $status = 'OK')
    {
        $this->message = $message;
        $this->status = $status;
    }

    function set_count($count)
    {
        $this->count = $count;
    }

    function set_visited_url($url)
    {
        $this->visited_urls[] = $url;
    }

    function set_duration($duration)
    {
        $this->duration = $duration;
    }
}