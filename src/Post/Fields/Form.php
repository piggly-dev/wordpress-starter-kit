<?php

namespace Piggly\Wordpress\Post\Fields;

use Piggly\Wordpress\Post\Fields\Interfaces\Renderable;

/**
 * Base implementation to a form.
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
class Form implements HTMLField
{
	/**
	 * Field options.
	 *
	 * @since 1.0.9
	 * @var array
	 */
	protected array $_options = [];

	/**
	 * Rows with fields.
	 *
	 * @since 1.0.9
	 * @var array<array<HTMLField>>
	 */
	protected array $_rows = [];

	/**
	 * Class constructor.
	 *
	 * @param array $options
	 * @param array<array<HTMLField>> $rows
	 * @since 1.0.9
	 */
	public function __construct(array $options, array $rows = [])
	{
		$this->_options = \array_merge([
			'name' => null,
			'id' => null,
			'action' => null,
			'method' => 'POST',
		], $options);

		$this->_rows = $rows;
	}

	/**
	 * Add a row of fields.
	 *
	 * @param array<Renderable> $columns
	 * @since 1.0.9
	 * @return void
	 */
	public function row(array $columns)
	{
		$this->_rows[] = $columns;
	}

	/**
	 * Get form name.
	 *
	 * @since 1.0.9
	 * @return string|null
	 */
	public function name(): ?string
	{
		return $this->_options['name'];
	}

	/**
	 * Get form id.
	 *
	 * @since 1.0.9
	 * @return string|null
	 */
	public function id(): ?string
	{
		return $this->_options['id'];
	}

	/**
	 * Get form action.
	 *
	 * @since 1.0.9
	 * @return string|null
	 */
	public function action(): ?string
	{
		return $this->_options['action'];
	}

	/**
	 * Get form method.
	 *
	 * @since 1.0.9
	 * @return string|null
	 */
	public function method(): ?string
	{
		return $this->_options['method'];
	}

	/**
	 * Get field options.
	 *
	 * @since 1.0.9
	 * @return array
	 */
	public function options(): array
	{
		return $this->_options;
	}

	/**
	 * Render to HTML.
	 *
	 * @param mixed $value
	 * @since 1.0.9
	 * @return void
	 */
	public function render($values = [])
	{
		$html  = "<form id=\"{$this->id()}\" name=\"{$this->name()}\" action=\"{$this->action()}\" method=\"{$this->method()}\">";

		foreach ($this->_rows as $row) {
			$html .= '<div class="pgly-wps--row">';

			foreach ($row as $column) {
				$values = $values[$column->name()] ?? [];
				$column->render(...$values);
			}

			$html .= '</div>';
		}

		$html .= '</form>';

		echo $html;
	}
}
