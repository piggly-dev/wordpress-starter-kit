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
class FinderSelectInputField extends InputField
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
	public function render($value = '', $lbl = '', array $labels = []): string
	{
		$this->changeValue($value);

		$vl = $this->value();
		$lbls = \array_merge(['search' => 'Search', 'unselect' => 'Unselect'], $labels);

		$html  = "<div class=\"pgly-wps--column pgly-wps-col--{$this->columnSize()}\">";
		$html .= "<div class=\"pgly-wps--field {$this->getCssForm()}--input {$this->getCssForm()}--finder\" data-name=\"{$this->name()}\">";

		if (!empty($this->label())) {
			$html .= "<label class=\"pgly-wps--label\">{$this->label()}</label>";
		}

		$html .= "<div class=\"pgly-wps--input flex-wrapper\">
			<input class=\"focus\" placeholder=\"{$this->placeholder()}\" type=\"text\">
			<button class=\"pgly-wps--button pgly-async--behaviour pgly-wps-is-primary\">
				{$lbls['search']}
				<svg class=\"pgly-wps--spinner pgly-wps-is-white\" viewBox=\"0 0 50 50\">
					<circle class=\"path\" cx=\"25\" cy=\"25\" r=\"20\" fill=\"none\" stroke-width=\"5\"></circle>
				</svg>
			</button>
		</div>
		<div class=\"pgly-wps--selected pgly-wps--card pgly-wps-is-white pgly-wps-is-compact\"
			style=\"display: none;\" data-value=\"${vl}\" data-label=\"{$lbl}\">
			<div class=\"pgly-wps--label inside left\"></div>
			<div class=\"pgly-wps--action-bar inside right\">
				<button class=\"pgly-wps--button pgly-wps-is-compact pgly-wps-is-danger\">{$lbls['unselect']}</button>
			</div>
		</div>";

		$html .= '<span class="pgly-wps--message"></span>';

		if ($this->isRequired()) {
			$html .= '<span class="pgly-wps--badge pgly-wps-is-danger" style="margin-top: 6px;">Obrigatório</span>';
		}
		
		if (!empty($this->description())) {
			$html .= "<p class=\"pgly-wps--description\">{$this->description()}</p>";
		}

		$html .= '<div class="pgly-wps--loader"><svg class="pgly-wps--spinner pgly-wps-is-primary" viewBox="0 0 50 50"><circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle></svg><div class="pgly-wps--list"></div></div>';
		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}
}
