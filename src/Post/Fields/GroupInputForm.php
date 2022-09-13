<?php

namespace Piggly\Wordpress\Post\Fields;

use Piggly\Wordpress\Post\Fields\Interfaces\Renderable;

/**
 * Base implementation to a group of inputs inside a form.
 *
 * @package \Piggly\Wordpress
 * @subpackage \Piggly\Wordpress\Fields
 * @version 1.0.7
 * @since 1.0.7s
 * @category Fields
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2022 Piggly Lab <dev@piggly.com.br>
 */
class GroupInputForm extends HTMLField
{
	/**
	 * Field options.
	 *
	 * @since 1.0.8
	 * @var array
	 */
	protected array $_options = [];

	/**
	 * Rows with fields.
	 *
	 * @since 1.0.8
	 * @var array<array<HTMLField>>
	 */
	protected array $_rows = [];

	/**
	 * Class constructor.
	 *
	 * @param array $options
	 * @param array<array<HTMLField>> $rows
	 * @since 1.0.8
	 */
	public function __construct(array $options, array $rows = [])
	{
		$this->_options = \array_merge([
			'name' => null,
			'column_size' => 12
		], $options);

		$this->_rows = $rows;
	}

	/**
	 * Get field column size.
	 *
	 * @since 1.0.8
	 * @return int
	 */
	public function columnSize(): int
	{
		return $this->_options['column_size'];
	}

	/**
	 * Get field options.
	 *
	 * @since 1.0.8
	 * @return array
	 */
	public function options(): array
	{
		return $this->_options;
	}

	/**
	 * Add a row of fields.
	 *
	 * @param array<HTMLField> $columns
	 * @since 1.0.8
	 * @return void
	 */
	public function row(array $columns)
	{
		$this->_rows[] = $columns;
	}

	/**
	 * Render to HTML.
	 *
	 * @param mixed $value
	 * @since 1.0.8
	 * @return void
	 */
	public function render($values = [])
	{
		$html  = "<div class=\"pgly-wps--column pgly-col-is-{$this->columnSize()}\">";
		$html .= "<div class=\"pgly-wps--field pgly-form--input pgly-form--group\" data-name=\"{$this->name()}\">";

		foreach ($this->_rows as $row) {
			$html .= '<div class="pgly-wps--row">';

			foreach ($row as $column) {
				$values = $values[$column->name()] ?? [];
				$column->render(...$values);
			}

			$html .= '</div>';
		}

		$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}
}