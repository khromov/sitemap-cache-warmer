<?php
namespace Khromov\Cache_Warmer;

/**
 * Logs data in an array and prints it via echo.
 *
 * Class ArrayLogger
 * @package Khromov\Cache_Warmer
 */
class ArrayLogger implements LoggerInterface
{
    var $log;

    function log($message, $severity = 'INFO')
    {
        $this->log[] = array(
            'message' => $message,
            'severity' => $severity
        );
    }

    function print_log($row_break = "\n")
    {
        foreach($this->log as $single_row)
        {
            echo $this->format_row($single_row) . $row_break;
        }
    }

    function return_log()
    {
        $ret = array();

        foreach($this->log as $single_row)
        {
            $ret[] = $this->format_row($single_row);
        }

        return $ret;
    }

    private function format_row($row)
    {
        return "{$row['severity']}: {$row['message']}";
    }
}