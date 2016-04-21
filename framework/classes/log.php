<?php
/**
 * Class for Log support
 *
 */
class Log {
    /**
     * Create the log file if it doesn't exist.
     */
    public function __construct() {
        if(!file_exists(LOG_PATH . 'log.txt'))
            file_put_contents(LOG_PATH . 'log.txt', '[START OF LOG - CREATED ON ' . date('Y-m-d H:i:s') . ']' . PHP_EOL, FILE_APPEND);
    }
    /**
     * Add an error message to the log
     */
    public function error($message) {
        file_put_contents(LOG_PATH . 'log.txt', '[ERROR - ' .  date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL, FILE_APPEND);
    }
    /**
     * Add an warning message to the log
     */
    public function warning($message) {
        file_put_contents(LOG_PATH . 'log.txt', '[WARNING - ' .  date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL, FILE_APPEND);
    }
}