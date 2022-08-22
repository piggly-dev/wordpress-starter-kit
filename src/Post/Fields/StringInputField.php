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
class StringInputField extends InputField
{
	/**
	 * Parse data to desired format.
	 *
	 * @param mixed $data
	 * @return mixed
	 */
	public function parse($data)
	{
		return \esc_attr($data);
	}

	/**
	 * Render to HTML with value.
	 *
	 * @param mixed $value
	 * @param mixed $default
	 * @since 1.0.7
	 * @return void
	 */
	public function render($value = '', $default = null)
	{
		$id = "{$this->prefix}{$this->name}";
		$vl = \esc_attr($this->getValue($value, $default));
		$ph = $this->placeholder ?? $this->label;

		echo "<fieldset>
			<label for=\"{$id}\">{$this->label}</label>
			<input
				class=\"widefat\"
				type=\"text\"
				name=\"{$id}\"
				id=\"{$id}\"
				value=\"{$vl}\"
				placeholder=\"{$ph}\"/>
		</fieldset>";
	}
}
