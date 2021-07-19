<?php
namespace Piggly\Wordpress\Core\Scaffold;

use Piggly\Wordpress\Plugin;

/**
 * Every business login must be at a different
 * class, to easy manager these classes, Initiable
 * class will give them a shortcut to create a
 * new instance and run the startup method.
 *
 * @package \Piggly\Wordpress
 * @subpackage \Piggly\Wordpress\Core\Scaffold
 * @version 1.0.0
 * @since 1.0.0
 * @category Scaffold
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2021 Piggly Lab <dev@piggly.com.br>
 */
abstract class Initiable
{
	/**
	 * Plugin settings.
	 *
	 * @var Plugin
	 * @since 1.0.2
	 */
	protected $_plugin;

	/**
	 * Run startup method to class create
	 * it own instance.
	 *
	 * @param Plugin $plugin
	 * @since 1.0.0
	 * @return void
	 */
	public static function init ( Plugin $plugin = null )
	{
		$obj = new self($plugin);
		$obj->startup();
	}
	
	/**
	 * Construct with optional plugin settings.
	 *
	 * @param Plugin $plugin
	 * @since 1.0.2
	 * @return void
	 */
	public function __construct ( Plugin $plugin = null )
	{
		if ( !is_null($plugin) )
		{ $this->_plugin = $plugin; }
	}

	/**
	 * Startup method with all actions and
	 * filter to run.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	abstract public function startup ();

	/**
	 * Set plugin settings.
	 *
	 * @param Plugin $plugin
	 * @since 1.0.2
	 * @return Core
	 */
	public function plugin ( Plugin $plugin )
	{ $this->_plugin = $plugin; return $this; }

	/**
	 * Get plugin settings.
	 *
	 * @since 1.0.2
	 * @return Plugin
	 */
	public function getPlugin () : Plugin
	{ return $this->_plugin; }
}