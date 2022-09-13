<?php

namespace Piggly\Wordpress\Post\Fields;

/**
 * Base implementation to a text input field.
 *
 * @package \Piggly\Wordpress
 * @subpackage \Piggly\Wordpress\Fields
 * @version 1.0.8
 * @since 1.0.8
 * @category Fields
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2022 Piggly Lab <dev@piggly.com.br>
 */
class TextInputField extends InputField
{
	/**
	 * Input type.
	 *
	 * @since 1.0.8
	 * @var string
	 */
	protected $type = 'text';

	/**
	 * Class constructor.
	 *
	 * @since 1.0.8
	 */
	public function __construct(array $options)
	{
		parent::__construct($options);

		$this->_options['parse'] = function ($value) {
			return \esc_attr($value);
		};
	}

	/**
	 * Render to HTML with value.
	 *
	 * @param mixed $value
	 * @param mixed $default
	 * @since 1.0.8
	 * @return void
	 */
	public function render($value = '')
	{
		$this->changeValue($value);

		$id = $this->name(true);
		$vl = $this->value();

		$html  = "<div class=\"pgly-wps--column pgly-col-is-{$this->columnSize()}\">";
		$html .= "<div class=\"pgly-wps--field pgly-form--input pgly-form--text\" data-name=\"{$this->name()}\">";

		if (!empty($this->label())) {
			$html .= "<label class=\"pgly-wps--label\">{$this->label()}</label>";
		}

		$html .= "<input id=\"{$id}\" name=\"{$id}\" placeholder=\"{$this->placeholder()}\" type=\"{$this->type}\" value=\"{$vl}\">";
		$html .= '<span class="pgly-wps--message"></span>';

		if (!empty($this->description())) {
			$html .= "<p class=\"pgly-wps--description\">{$this->description()}</p>";
		}

		$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}
}