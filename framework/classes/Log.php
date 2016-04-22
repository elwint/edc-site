<?php
/**
 * Class for Log support and exception handler
 *
 */
class Log {
	/**
	 * Create the log file if it doesn't exist.
	 */
	public static function init() {
		if(!file_exists(LOG_PATH . 'log.txt'))
			file_put_contents(LOG_PATH . 'log.txt', '[START OF LOG - CREATED ON ' . date('Y-m-d H:i:s') . ']' . PHP_EOL, FILE_APPEND);
	}
	/**
	 * Add an error message to the log
	 */
	public static function error($message) {
		file_put_contents(LOG_PATH . 'log.txt', '[ERROR - ' .  date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL, FILE_APPEND);
	}
	/**
	 * Add an warning message to the log
	 */
	public static function warning($message) {
		file_put_contents(LOG_PATH . 'log.txt', '[WARNING - ' .  date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL, FILE_APPEND);
	}

	/**
	 * Exception handler
	 */
	public static function makeException($error, $class="", $function="") {
		if (empty($class) && empty($function)) {
			$error_debug = debug_backtrace();
			$class = $error_debug[1]['class'];
			$function = $error_debug[1]['function'];
		}
		self::error(strip_tags("Fatal error: {$error} (class '{$class}' function '{$function}')."));
		ob_end_clean();
		View::init()
			->set('error', $error)
			->set('class', $class)
			->set('function', $function)
			->make('full_pages/exception');
		die();
	}
}