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
class ArrayInputField extends InputField
{
	/**
	 * Values.
	 *
	 * @since 1.0.7
	 * @var array<string>
	 */
	protected $values;

	/**
	 * Values of array.
	 *
	 * @param array<string> $values
	 * @since 1.0.7
	 * @return mixed
	 */
	public function values(array $values)
	{
		$this->values = $values;
		return $this;
	}

	/**
	 * Parse data to desired format.
	 *
	 * @param mixed $data
	 * @since 1.0.7
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

		$op = \implode(
			' ',
			\array_map(
				function ($k, $v) use ($vl) {
					return \sprintf(
						'<option value="%s" %s>%s</option>',
						$k,
						$k === $vl ? 'selected="selected"' : '',
						$v
					);
				},
				\array_keys($this->values),
				\array_values($this->values)
			)
		);

		echo "<fieldset>
			<label for=\"{$id}\">{$this->label}</label>
			<select
				class=\"widefat\"
				name=\"{$id}\"
				id=\"{$id}\"
				placeholder=\"{$ph}\">
				$op
			</select>
		</fieldset>";
	}
}
