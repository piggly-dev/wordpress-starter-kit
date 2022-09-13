<?php

namespace Piggly\Wordpress\Post\Fields;

/**
 * Base implementation to a number input field.
 *
 * @package \Piggly\Wordpress
 * @subpackage \Piggly\Wordpress\Fields
 * @version 1.0.7
 * @since 1.0.7
 * @category Fields
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2022 Piggly Lab <dev@piggly.com.br>
 */
class IntegerInputField extends TextInputField
{
	/**
	 * Input type.
	 *
	 * @since 1.0.8
	 * @var string
	 */
	protected $type = 'number';

	/**
	 * Class constructor.
	 *
	 * @since 1.0.8
	 */
	public function __construct(array $options)
	{
		parent::__construct($options);

		$this->_options['transform'] = function ($value) {
			return \intval($value);
		};
	}
}
