<?php

namespace Piggly\Wordpress\Post\Fields;

/**
 * Base implementation to an input field.
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
abstract class InputField
{
	/**
	 * Field name.
	 *
	 * @since 1.0.7
	 * @var string
	 */
	protected string $name;

	/**
	 * Field prefix.
	 *
	 * @since 1.0.7
	 * @var string
	 */
	protected string $prefix;

	/**
	 * Field label.
	 *
	 * @since 1.0.7
	 * @var string
	 */
	protected string $label;

	/**
	 * Field placeholder.
	 *
	 * @since 1.0.7
	 * @var string|null
	 */
	protected ?string $placeholder;

	/**
	 * Flag if it is required.
	 *
	 * @since 1.0.7
	 * @var bool
	 */
	protected bool $required;

	/**
	 * Constructor.
	 *
	 * @param string $prefix
	 * @param string $name
	 * @param string $label
	 * @param string $placeholder
	 * @param string $required
	 * @param mixed $default
	 * @since 1.0.7
	 */
	public function __construct(
		string $prefix,
		string $name,
		string $label,
		string $placeholder = null,
		bool $required = false,
		$default = ''
	) {
		$this->name = $name;
		$this->prefix = $prefix;
		$this->label = $label;
		$this->placeholder = $placeholder;
		$this->required = $required;
		$this->default = $default;
	}

	/**
	 * Get value or default.
	 * When default is NULL, always
	 * return value.
	 *
	 * @param mixed $value
	 * @param mixed $default
	 * @since 0.1.0
	 * @return mixed
	 */
	public function getValue($value, $default = '')
	{
		if (\is_null($default)) {
			return $value;
		}

		return empty($value) ? $default : $value;
	}

	/**
	 * Get field name.
	 *
	 * @since 1.0.7
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * Get field name with prefix.
	 *
	 * @since 1.0.7
	 * @return string
	 */
	public function getNameWithPrefix(): string
	{
		return $this->prefix . $this->name;
	}

	/**
	 * Get field label.
	 *
	 * @since 1.0.7
	 * @return string
	 */
	public function getLabel(): string
	{
		return $this->label;
	}

	/**
	 * Flag as required.
	 *
	 * @since 1.0.7
	 * @return self
	 */
	public function required()
	{
		$this->required = true;
		return $this;
	}

	/**
	 * Flag as optional.
	 *
	 * @since 1.0.7
	 * @return self
	 */
	public function optional()
	{
		$this->required = false;
		return $this;
	}

	/**
	 * Check if is required.
	 *
	 * @since 1.0.7
	 * @return bool
	 */
	public function isRequired()
	{
		return $this->required;
	}

	/**
	 * Parse data to desired format.
	 *
	 * @param mixed $data
	 * @return mixed
	 */
	abstract public function parse($data);

	/**
	 * Render to HTML with value.
	 *
	 * @param mixed $value
	 * @param mixed $default
	 * @since 1.0.7
	 * @return void
	 */
	abstract public function render($value = '', $default = '');
}
