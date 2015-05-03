<?php
namespace Khromov\Cache_Warmer;

/**
 * Models a simple URL Collection.
 *
 * Interface UrlCollection
 * @package Khromov\Cache_Warmer
 */
interface LoggerInterface
{
    /**
     * @param string $message
     * @param string $severity
     * @return void
     */
    public function log($message, $severity = 'INFO');

    /**
     * @param string $row_break
     * @return void
     */
    public function print_log($row_break = "\n");

    /**
     * @return array
     */
    public function return_log();
}