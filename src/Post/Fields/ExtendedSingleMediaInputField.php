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
class ExtendedSingleMediaInputField extends InputField
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
			return \intval($value);
		};
	}
	/**
	 * Render to HTML with value.
	 *
	 * @param mixed $value
	 * @param mixed $src
	 * @since 1.0.8
	 * @return void
	 */
	public function render($value = '', $src = '', array $labels = [])
	{
		$this->changeValue($value);

		$vl = $this->value() ? 'true' : 'false';
		$lbls = \array_merge(['clean' => 'Clean Selection', 'select' => 'Select'], $labels);

		$html  = "<div class=\"pgly-wps--column pgly-col-is-{$this->columnSize()}\">";
		$html .= "<div class=\"pgly-wps--field pgly-form--input pgly-form--single-media\" data-name=\"{$this->name()}\">";

		if (!empty($this->label())) {
			$html .= "<label class=\"pgly-wps--label\">{$this->label()}</label>";
		}

		$html .= "<div class=\"container\">
			<img data-value=\"{$vl}\" data-src=\"{$src}\" />
			<span class=\"pgly-wps--placeholder\">{$this->placeholder()}</span>
		</div>";

		$html .= '<span class="pgly-wps--message"></span>';

		if (!empty($this->description())) {
			$html .= "<p class=\"pgly-wps--description\">{$this->description()}</p>";
		}

		$html .= "<div class=\"pgly-wps--action-bar\">
			<button class=\"pgly-wps--button pgly-wps-is-compact pgly-wps-is-primary pgly-wps--select\">{$lbls['select']}</button>
			<button class=\"pgly-wps--button pgly-wps-is-compact pgly-wps-is-danger pgly-wps--clean\">{$lbls['clean']}</button>
		</div>";

		$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}
}
