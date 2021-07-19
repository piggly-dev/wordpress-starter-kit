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
	 * Return if is debugging.
	 * 
	 * @since 1.0.0
	 * @return bool
	 */
	public function debugging () : bool
	{ return $this->isDebugging(); }

	/**
	 * Get logger or null.
	 * 
	 * @since 1.0.0
	 * @return LoggerInterface|null
	 */
	public function logger () : ?LoggerInterface
	{ return $this->getLogger(); }
  
	/**
	 * Change current debug logger.
	 *
	 * @param LoggerInterface $logger
	 * @since 1.0.0
	 * @return void
	 */
	public function changeLogger ( LoggerInterface $logger )
	{ $this->setLogger($logger); }

	/**
	 * Change debug state.
	 * 
	 * @param bool $debug
	 * @since 1.0.0
	 * @return void
	 */
	public function changeState ( bool $debug = true )
	{ return $this->setDebugging($debug); }

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
	public function emergency ( string $message, array $context = [] )
	{ $this->callLogger('emergency', $message, $context); }

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
	public function alert ( string $message, array $context = [] )
	{ $this->callLogger('alert', $message, $context); }

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
	public function critical ( string $message, array $context = [] )
	{ $this->callLogger('critical', $message, $context); }

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
	public function error ( string $message, array $context = [] )
	{ $this->callLogger('error', $message, $context); }

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
	public function warning ( string $message, array $context = [] )
	{ $this->callLogger('warning', $message, $context); }

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
	public function info ( string $message, array $context = [] )
	{ $this->callLogger('info', $message, $context); }

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
	public function debug ( string $message, array $context = [] )
	{ $this->callLogger('debug', $message, $context); }

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
	public function notice ( string $message, array $context = [] )
	{ $this->callLogger('notice', $message, $context); }

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
	protected function callLogger ( string $level, string $message, array $context = [] )
	{
		$logger = $this->getLogger();
		
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