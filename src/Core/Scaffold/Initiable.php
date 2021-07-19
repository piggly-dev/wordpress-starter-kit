<?php
namespace Piggly\Wordpress\Core\Scaffold;

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
	 * Run startup method to class create
	 * it own instance.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function init ()
	{
		$obj = new self();
		$obj->startup();
	}

	/**
	 * Startup method with all actions and
	 * filter to run.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	abstract public function startup ();
}