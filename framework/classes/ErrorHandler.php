<?php

class ErrorHandler
{
	public static function init() {
		ini_set('display_errors', 'Off');
		set_error_handler('ErrorHandler::handler');
		register_shutdown_function('ErrorHandler::fatalHandler');
		ob_start();
	}
	
	/**
	 * Error handler
	 */
	public static function handler($code, $message, $file, $line) {

		switch ($code) {
			case E_NOTICE:
			case E_USER_NOTICE:
				return;
			case E_WARNING:
			case E_USER_WARNING:
				Log::warning(strip_tags("Warning: {$message} in {$file} on line {$line}"));
				return;
			case E_STRICT:
				Log::warning(strip_tags("Strict Standards: {$message} in {$file} on line {$line}"));
				return;
			case E_ERROR:
			case E_USER_ERROR:
				$errortype = "Fatal error";
				break;
			case E_PARSE:
				$errortype = "Parse error";
				break;
			default:
				$errortype = "Unknown error";
				break;
		}
		ErrorHandler::makeError("<b>{$errortype}:</b> {$message} in <b>{$file}</b> on line <b>{$line}</b>", 'normal');
	}

	public static function fatalHandler() {
		$error = error_get_last();

		if( $error !== NULL) {
			$errno = $error["type"];
			$errfile = $error["file"];
			$errline = $error["line"];
			$errstr = $error["message"];
			self::handler($errno, $errstr, $errfile, $errline);
		}
	}
	
	/**
	 * Make an error (class=normal is normal (not custom) error)
	 */
	public static function makeError($error, $class = "", $function = "")
	{
		if (empty($class) && empty($function)) {
			$error_debug = debug_backtrace();
			$class = $error_debug[1]['class'];
			$function = $error_debug[1]['function'];
		}
		if ($class == "normal")
			Log::error(strip_tags($error));
		else
			Log::error(strip_tags("Fatal error: {$error} (class '{$class}' function '{$function}')"));

		ob_end_clean();
		header("HTTP/1.0 500 Internal Server Error");
		View::init()
			->set('error', $error)
			->set('class', $class)
			->set('function', $function)
			->make('full_pages/error');
		die();
	}
}