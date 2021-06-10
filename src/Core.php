<?php
namespace Piggly\Wordpress;

use Piggly\Wordpress\Core\Debugger;
use Piggly\Wordpress\Core\i18n;
use Piggly\Wordpress\Core\Interfaces\Runnable;
use Piggly\Wordpress\Core\Scaffold\Initiable;
use Piggly\Wordpress\Settings\Manager;

/**
 * The Core class startup all plugin business
 * logic, it is the first startup point to plugin.
 *
 * @package \Piggly\Wordpress
 * @subpackage \Piggly\Wordpress
 * @version 1.0.0
 * @since 1.0.0
 * @category Core
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2021 Piggly Lab <dev@piggly.com.br>
 */
abstract class Core extends Initiable
{
	/**
	 * Startup plugin core with an activator,
	 * a desactivator and a upgrader.
	 *
	 * @param Runnable $activator Run at register_activation_hook()
	 * @param Runnable $desactivator Run at register_deactivation_hook()
	 * @param Runnable $upgrader Manage updates logic.
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct (
		Runnable $activator,
		Runnable $desactivator,
		Runnable $upgrader
	)
	{
		// Set if it is debbuging
		Debugger::changeState($this->isDebugging() ?? false);

		// Runnable classes
		$this->activator($activator);
		$this->desactivator($desactivator);
		$this->upgrader($upgrader);

		// Initiable classes
		$this->initiable(i18n::class);
	}

	/**
	 * Add a Runnable object as activator to
	 * register_activation_hook().
	 *
	 * @param Runnable $activator
	 * @since 1.0.0
	 * @return void
	 */
	public function activator ( Runnable $activator )
	{
		// Plugin activation
		register_activation_hook( 
			static::getPlugin()->getAbspath(), 
			array($activator, 'run')
		);
	}

	/**
	 * Add a Runnable object as desactivator to
	 * register_deactivation_hook().
	 *
	 * @param Runnable $desactivator
	 * @since 1.0.0
	 * @return void
	 */
	public function desactivator ( Runnable $desactivator )
	{
		// Plugin desactivation
		register_deactivation_hook( 
			static::getPlugin()->getAbspath(), 
			array($desactivator, 'run')
		);
	}

	/**
	 * Add a Runnable object as the upgrader
	 * to manage updates and similar actions.
	 *
	 * @param Runnable $upgrader
	 * @since 1.0.0
	 * @return void
	 */
	public function upgrader ( Runnable $upgrader )
	{ $upgrader->run(); }

	/**
	 * Init a initiable class.
	 *
	 * @param string $initiable
	 * @since 1.0.0
	 * @return void
	 */
	public function initiable ( string $initiable )
	{ $initiable::init(); }

	/**
	 * Return if plugin is debugging. You may set it
	 * from plugin settings by getting Manager class.
	 * 
	 * @since 1.0.0
	 * @return boolean
	 */
	abstract public function isDebugging () : bool;

	/**
	 * Get plugin runtime settings.
	 *
	 * @since 1.0.0
	 * @return Plugin
	 */
	abstract public static function getPlugin () : Plugin;

	/**
	 * Get core plugin settings.
	 *
	 * @since 1.0.0
	 * @return Manager
	 */
	abstract public static function getSettings () : Manager;
}