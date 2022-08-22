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
class MediaInputField extends InputField
{
	/**
	 * Get field name.
	 *
	 * @since 1.0.7
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name . '_id';
	}

	/**
	 * Get field name with prefix.
	 *
	 * @since 1.0.7
	 * @return string
	 */
	public function getNameWithPrefix(): string
	{
		return $this->prefix . $this->name . '_id';
	}

	/**
	 * Parse data to desired format.
	 *
	 * @param mixed $data
	 * @return mixed
	 */
	public function parse($data)
	{
		return \intval($data);
	}

	/**
	 * Render to HTML with value.
	 *
	 * @param mixed $value
	 * @param mixed $default
	 * @since 1.0.7
	 * @return void
	 */
	public function render($value = [], $default = null)
	{
		$value = $this->getValue($value, $default);

		$id = "{$this->prefix}{$this->name}";
		$vl = [
			'src' => \esc_url($value['src'] ?? ''),
			'id' => \esc_attr($value['id'] ?? ''),
		];

		echo "<fieldset class=\"media\">
			<label for=\"{$id}\">{$this->label}</label>
			<img class=\"image-selector\" id=\"{$id}\" src=\"{$vl['src']}\" />
			<p>
				<button type=\"button\" class=\"button button-primary eno-wc-order-contracts-open-media\"
					data-title=\"Selecionar imagem\" data-input=\"{$id}_id\" data-image=\"{$id}\">Selecionar</button>
				<button type=\"button\" class=\"button eno-wc-order-contracts-clear-media\"
					data-input=\"{$id}_id\" data-image=\"{$id}\">Limpar Seleção</button>
			</p>
			<input type=\"hidden\" name=\"{$id}_id\" id=\"{$id}_id\"
				value=\"{$vl['id']}\" />
		</fieldset>";
	}
}
