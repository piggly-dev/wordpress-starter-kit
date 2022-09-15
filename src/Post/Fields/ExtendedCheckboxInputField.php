<?php

namespace Piggly\Wordpress\Post\Fields;

/**
 * Base implementation to a text input field.
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
class ExtendedCheckboxInputField extends InputField
{
	/**
	 * Class constructor.
	 *
	 * @since 1.0.9
	 */
	public function __construct(array $options)
	{
		parent::__construct($options);

		$this->_options['parse'] = function ($value) {
			return \boolval($value);
		};
	}
	/**
	 * Render to HTML with value.
	 *
	 * @param mixed $value
	 * @param mixed $default
	 * @since 1.0.9
	 * @return string
	 */
	public function render($value = ''): string
	{
		$this->changeValue($value);

		$id = $this->name(true);
		$vl = $this->value() ? 'true' : 'false';

		$html  = "<div class=\"pgly-wps--column pgly-col-is-{$this->columnSize()}\">";
		$html .= "<div class=\"pgly-wps--field pgly-form--input pgly-form--checkbox\" data-name=\"{$this->name()}\">";

		if (!empty($this->label())) {
			$html .= "<label class=\"pgly-wps--label\">{$this->label()}</label>";
		}

		$html .= "<div class=\"pgly-wps--checkbox\" data-value=\"{$vl}\">";
		$html .= "<div class=\"pgly-wps--icon\"></div>";
		$html .= "<div class=\"pgly-wps--placeholder\">My checkbox</div>";
		$html .= "</div>";

		$html .= '<span class="pgly-wps--message"></span>';

		if (!empty($this->description())) {
			$html .= "<p class=\"pgly-wps--description\">{$this->description()}</p>";
		}


		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}
}
