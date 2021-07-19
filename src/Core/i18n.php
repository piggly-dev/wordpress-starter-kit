<?php
namespace Piggly\Wordpress\Core;

use Piggly\Wordpress\Core;
use Piggly\Wordpress\Core\Scaffold\Initiable;

/**
 * i18n manages the plugin trannslation
 *
 * @package \Piggly\Wordpress
 * @subpackage \Piggly\Wordpress\Core
 * @version 1.0.0
 * @since 1.0.0
 * @category Core
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2021 Piggly Lab <dev@piggly.com.br>
 */
class i18n extends Initiable
{
	/**
	 * Startup method with all actions and
	 * filter to run.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function startup ()
	{ WP::add_action( 'plugins_loaded', $this, 'load_plugin_textdomain' ); }

	/**
	 * Load the plugin text domain for translation
	 * at /path/to/plugin/languages.
	 * 
	 * @since 1.0.0
	 * @since 1.0.2 Non static.
	 * @return void
	 */
	public function load_plugin_textdomain ()
	{
		load_plugin_textdomain(
			$this->_plugin->getDomain(),
			false,
			$this->_plugin->getAbspath().'/languages'
		);
	}

	/**
	 * Translates $text and retrieves the singular or plural 
	 * form based on the supplied number.
	 *
	 * @param string $single
	 * @param string $plural
	 * @param integer $number
	 * @since 1.0.0
	 * @since 1.0.2 Non static.
	 * @return string
	 */
	public function _ntranslate ( string $single, string $plural, int $number ) : string
	{ return _n( $single, $plural, $number, 'text-domain' ); }

	/**
	 * Display the translation of $text.
	 *
	 * @param string $text
	 * @since 1.0.0
	 * @since 1.0.2 Non static.
	 * @return void
	 */
	public function _etranslate ( string $text )
	{ _e( $text, $this->_plugin->getDomain() ); }

	/**
	 * Retrieve the translation of $text.
	 *
	 * @param string $text
	 * @since 1.0.0
	 * @since 1.0.2 Non static.
	 * @return string
	 */
	public function __translate ( string $text ) : string
	{ return __( $text, $this->_plugin->getDomain() ); }
}