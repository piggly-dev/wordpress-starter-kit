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
class ExtendedSelectInputField extends InputField
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
			if (empty($value)) {
				return null;
			}
			return \esc_attr($value);
		};
	}
	/**
	 * Render to HTML with value.
	 *
	 * @param mixed $value
	 * @param mixed $lbl
	 * @since 1.0.9
	 * @return string
	 */
	public function render($value = '', $lbl = ''): string
	{
		$this->changeValue($value);

		$id = $this->name(true);
		$vl = $this->value() ? 'true' : 'false';

		$html  = "<div class=\"pgly-wps--column pgly-wps-col--{$this->columnSize()}\">";
		$html .= "<div class=\"pgly-wps--field {$this->getCssForm()}--input {$this->getCssForm()}--eselect\" data-name=\"{$this->name()}\">";

		if (!empty($this->label())) {
			$html .= "<label class=\"pgly-wps--label\">{$this->label()}</label>";
		}

		$html .= "<div class=\"pgly-wps--select\" data-value=\"{$vl}\" data-label=\"{$lbl}\">
			<div class=\"selected empty\">
				<span>{$this->placeholder()}</span>
				<svg class=\"pgly-wps--arrow\" height=\"48\" viewBox=\"0 0 48 48\" width=\"48\"
					xmlns=\"http://www.w3.org/2000/svg\">
					<path d=\"M14.83 16.42l9.17 9.17 9.17-9.17 2.83 2.83-12 12-12-12z\"></path>
					<path d=\"M0-.75h48v48h-48z\" fill=\"none\"></path>
				</svg>
				<svg class=\"pgly-wps--spinner pgly-wps-is-primary\" viewBox=\"0 0 50 50\">
					<circle class=\"path\" cx=\"25\" cy=\"25\" r=\"20\" fill=\"none\" stroke-width=\"5\"></circle>
				</svg>
			</div>
			<div class=\"items hidden\">
				<div class=\"placeholder clickable\">{$this->placeholder()}</div>
				<div class=\"container\"></div>
			</div>
		</div>";

		if ($this->isRequired()) {
			$html .= '<span class="pgly-wps--badge pgly-wps-is-danger" style="margin-top: 6px; margin-right: 6px">Obrigatório</span>';
		}

		$html .= '<span class="pgly-wps--message"></span>';

		if (!empty($this->description())) {
			$html .= "<p class=\"pgly-wps--description\">{$this->description()}</p>";
		}

		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}
}
