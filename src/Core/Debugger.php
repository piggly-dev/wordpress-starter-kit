<?php
namespace Piggly\Wordpress\Core;

use Piggly\Wordpress\Core;
use Psr\Log\LoggerInterface;

/**
 * The Debugger class manages the 
 * LoggerInterface in a smart and simple way.
 * Preventing you to care about logging.
 *
 * @package \Piggly\Wordpress
 * @subpackage \Piggly\Wordpress\Core
 * @version 1.0.0
 * @since 1.0.0
 * @category Debug
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2021 Piggly Lab <dev@piggly.com.br>
 */
class Debugger
{
	/**
	 * Main application logger.
	 * 
	 * @var LoggerInterface
	 * @since 1.0.0
	 */
	protected $logger;

	/**
	 * Is debugging?
	 * 
	 * @var bool
	 * @since 1.0.0
	 */
	protected $debug = false;

	/**
	 * Prevents to construct.
	 * 
	 * @since 1.0.0
	 * @return void
	 */
	protected function __construct () {}

	/**
	 * Prevents to clone.
	 * 
	 * @since 1.0.0
	 * @return void
	 */
	protected function __clone () {}

	/**
	 * Prevents to wakeup.
	 * 
	 * @since 1.0.0
	 * @return void
	 */
	protected function __wakeup () {}

	/**
	 * Returns a singleton instance of this class.
	 * 
	 * @since 1.0.0
	 * @return self
	 */
	protected static function instance ()
	{
		// Static instance
		static $instance;

		// If is null, creates a new instance
		if ( is_null ( $instance ) )
		{ $instance = new self(); }

		// Returns the static instance
		return $instance;
	}

	/**
	 * Return if is debugging.
	 * 
	 * @since 1.0.0
	 * @return bool
	 */
	public static function debugging () : bool
	{ return self::instance()->isDebugging(); }

	/**
	 * Get logger or null.
	 * 
	 * @since 1.0.0
	 * @return LoggerInterface|null
	 */
	public static function logger () : ?LoggerInterface
	{ return self::instance()->getLogger(); }

	/**
	 * Change current debug logger.
	 *
	 * @param LoggerInterface $logger
	 * @since 1.0.0
	 * @return void
	 */
	public static function changeLogger ( LoggerInterface $logger )
	{ self::instance()->setLogger($logger); }

	/**
	 * Change debug state.
	 * 
	 * @param bool $debug
	 * @since 1.0.0
	 * @return void
	 */
	public static function changeState ( bool $debug = true )
	{ return self::instance()->setDebugging($debug); }

	/**
	 * System is unusable.
	 * 
	 * You can call it even log is not set and
	 * even it is not at debug mode. The log will
	 * be recorded only when it is debugging and
	 * logger is set.
	 * 
	 * @param string $message
	 * @param mixed[] $context
	 * @since 1.0.0
	 * @return void
	 */
	public static function emergency ( string $message, array $context = [] )
	{ static::callLogger('emergency', $message, $context); }

	/**
	 * Action must be taken immediately.
	 *
	 * Example: Entire website down, database
	 * unavailable, etc. This should trigger
	 * the SMS alerts and wake you up.
	 * 
	 * You can call it even log is not set and
	 * even it is not at debug mode. The log will
	 * be recorded only when it is debugging and
	 * logger is set.
	 * 
	 * @param string $message
	 * @param mixed[] $context
	 * @since 1.0.0
	 * @return void
	 */
	public static function alert ( string $message, array $context = [] )
	{ static::callLogger('alert', $message, $context); }

	/**
	 * Critical conditions.
	 *
	 * Example: Application component unavailable,
	 * unexpected exception.
	 * 
	 * You can call it even log is not set and
	 * even it is not at debug mode. The log will
	 * be recorded only when it is debugging and
	 * logger is set.
	 * 
	 * @param string $message
	 * @param mixed[] $context
	 * @since 1.0.0
	 * @return void
	 */
	public static function critical ( string $message, array $context = [] )
	{ static::callLogger('critical', $message, $context); }

	/**
	 * Runtime errors that do not require immediate
	 * action but should typically be logged and monitored.
	 * 
	 * You can call it even log is not set and
	 * even it is not at debug mode. The log will
	 * be recorded only when it is debugging and
	 * logger is set.
	 * 
	 * @param string $message
	 * @param mixed[] $context
	 * @since 1.0.0
	 * @return void
	 */
	public static function error ( string $message, array $context = [] )
	{ static::callLogger('error', $message, $context); }

	/**
	 * Exceptional occurrences that are not errors.
	 * 
	 * You can call it even log is not set and
	 * even it is not at debug mode. The log will
	 * be recorded only when it is debugging and
	 * logger is set.
	 * 
	 * @param string $message
	 * @param mixed[] $context
	 * @since 1.0.0
	 * @return void
	 */
	public static function warning ( string $message, array $context = [] )
	{ static::callLogger('warning', $message, $context); }

	/**
	 * Interesting events.
	 * 
	 * You can call it even log is not set and
	 * even it is not at debug mode. The log will
	 * be recorded only when it is debugging and
	 * logger is set.
	 * 
	 * @param string $message
	 * @param mixed[] $context
	 * @since 1.0.0
	 * @return void
	 */
	public static function info ( string $message, array $context = [] )
	{ static::callLogger('info', $message, $context); }

	/**
	 * Detailed debug information.
	 * 
	 * You can call it even log is not set and
	 * even it is not at debug mode. The log will
	 * be recorded only when it is debugging and
	 * logger is set.
	 * 
	 * @param string $message
	 * @param mixed[] $context
	 * @since 1.0.0
	 * @return void
	 */
	public static function debug ( string $message, array $context = [] )
	{ static::callLogger('debug', $message, $context); }

	/**
	 * Normal but significant events.
	 * 
	 * You can call it even log is not set and
	 * even it is not at debug mode. The log will
	 * be recorded only when it is debugging and
	 * logger is set.
	 * 
	 * @param string $message
	 * @param mixed[] $context
	 * @since 1.0.0
	 * @return void
	 */
	public static function notice ( string $message, array $context = [] )
	{ static::callLogger('notice', $message, $context); }

	/**
	 * Prepare logger before call it, validating
	 * if it is debugging and logger is set.
	 *
	 * @param string $level
	 * @param string $message
	 * @param array $context
	 * @since 1.0.0
	 * @return void
	 */
	protected static function callLogger ( string $level, string $message, array $context = [] )
	{
		
		$instance = self::instance();
		$logger = $instance->getLogger();
		
		$context = array_merge(array( 'source' => Core::getPlugin()->getName() ), $context);
		if ( !is_null($logger) ) $logger->{$level}($message, $context);
	}

	/**
	 * Set current logger.
	 *
	 * @param LoggerInterface $logger
	 * @since 1.0.0
	 * @return void
	 */
	public function setLogger ( LoggerInterface $logger )
	{ $this->logger = $logger; return $this; }

	/**
	 * Get logger if is debugging.
	 * 
	 * @since 1.0.0
	 * @return LoggerInterface|null
	 */
	public function getLogger () 
	{
		if ( $this->debug ) 
		{ return $this->logger ?? null; }

		return null;
	}

	/**
	 * Set new debug state.
	 * 
	 * @param bool $debug
	 * @since 1.0.0
	 * @return self
	 */
	public function setDebugging ( bool $debug = true )
	{ $this->debug = $debug; return $this; }

	/**
	 * Get the debug state.
	 * 
	 * @since 1.0.0
	 * @return bool
	 */
	public function isDebugging () : bool
	{ return $this->debug || false; }
}