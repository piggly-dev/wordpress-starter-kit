<?php

namespace Piggly\Wordpress\Post\Fields;

/**
 * Base implementation to an url input field.
 *
 * @package \Piggly\Wordpress
 * @subpackage \Piggly\Wordpress\Post\Fields
 * @version 1.0.7
 * @since 1.0.7
 * @category Fields
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2022 Piggly Lab <dev@piggly.com.br>
 */
class UrlInputField extends TextInputField
{
	/**
	 * Class constructor.
	 *
	 * @since 1.0.8
	 */
	public function __construct(array $options)
	{
		parent::__construct($options);

		$this->_options['parse'] = function ($value) {
			return \esc_url($value);
		};
	}
}