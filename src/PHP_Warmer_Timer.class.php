<?php
namespace Khromov\Cache_Warmer;

/**
 * Timer class
 *
 * Class PHP_Warmer_Timer
 */
class Timer
{
    var $start_time;
    var $stop_time;

    function start()
    {
        $this->start_time = microtime(true);
    }

    function stop()
    {
        $this->stop_time = microtime(true);
    }

    function duration()
    {
        return ($this->stop_time - $this->start_time);
    }
}