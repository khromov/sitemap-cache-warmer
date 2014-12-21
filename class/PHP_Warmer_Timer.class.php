<?php
/**
 * Timer class
 *
 * Class PHP_Warmer_Timer
 */
class PHP_Warmer_Timer
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