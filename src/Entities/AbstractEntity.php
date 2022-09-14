<?php

namespace Piggly\Wordpress\Entities;

use DateTime;
use Exception;
use Piggly\Wordpress\Repository\WPRepository;
use stdClass;

/**
 * Abstraction of records entities.
 *
 * @package \Piggly\Wordpress
 * @subpackage \Piggly\Wordpress\Entities
 * @version 1.0.10
 * @since 1.0.10
 * @category Entity
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2022 Piggly Lab <dev@piggly.com.br>
 */
abstract class AbstractEntity
{
	/**
	 * Fields for entity.
	 *
	 * @since 1.0.10
	 * @var array
	 */
	protected array $_fields = [];

	/**
	 * Hide following fields when
	 * updating or insertin.
	 *
	 * @since 1.0.10
	 * @var array
	 */
	protected array $_hidden = [];

	/**
	 * Get a field.
	 *
	 * @param string $field
	 * @param mixed $default
	 * @since 1.0.10
	 * @return void
	 */
	public function get(string $field, $default = null)
	{
		return $this->_fields[$field] ?? $default;
	}

	/**
	 * Set a field.
	 *
	 * @param string $field
	 * @param mixed $value
	 * @since 1.0.10
	 * @return self
	 */
	public function set(string $field, $value)
	{
		$this->_fields[$field] = $value;
		return $this;
	}

	/**
	 * Set a field.
	 *
	 * @param string $field
	 * @param mixed $value
	 * @since 1.0.10
	 * @return bool
	 */
	public function has(string $field): bool
	{
		return isset($this->_fields[$field]);
	}

	/**
	 * Save entity on database.
	 *
	 * @since 1.0.10
	 * @return void
	 */
	public function save()
	{
		$this->_prepareFields();

		try {
			if ($this->isCreated()) {
				static::getRepo()::update(
					$this->_removeFromArray($this->_fields, $this->_hidden),
					[static::primaryKey() => $this->id()]
				);

				return true;
			}

			$this->_fields[static::primaryKey()] = static::getRepo()::insert(
				$this->_removeFromArray($this->_fields, $this->_hidden),
				static::primaryKey()
			)[static::primaryKey()];
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	/**
	 * Remove entity from database.
	 *
	 * @since 1.0.10
	 * @return boolean
	 */
	public function remove(): bool
	{
		if (!$this->isCreated()) {
			return false;
		}

		return static::getRepo()::delete(
			[static::primaryKey() => $this->_fields[static::primaryKey()]]
		);
	}

	/**
	 * Get the id of entity. If empty, entity
	 * must be saved to database before.
	 *
	 * @since 1.0.10
	 * @return mixed
	 */
	public function id()
	{
		return $this->fields[static::primaryKey()] ?? null;
	}

	/**
	 * Return if entity is created on database.
	 *
	 * @since 1.0.10
	 * @return boolean
	 */
	public function isCreated(): bool
	{
		return isset($this->fields[static::primaryKey()]);
	}

	/**
	 * Applies fields to current fields.
	 * Will only apply valid values...
	 *
	 * @param array $fields
	 * @since 1.0.10
	 * @return void
	 */
	public function apply(array $fields = [])
	{
		foreach ($this->_fields as $key => $value) {
			if (isset($fields[$key])) {
				$this->_fields[$key] = $fields[$key];
			}
		}
	}

	/**
	 * Prepare fields before save.
	 *
	 * @since 1.0.10
	 * @return void
	 */
	protected function _prepareFields(): void
	{
		$this->_fields['updated_at'] = new DateTime('now', \wp_timezone());
	}

	/**
	 * Remove fields from array.
	 *
	 * @param array $arr
	 * @param array $remove
	 * @since 1.0.10
	 * @return array
	 */
	protected function _removeFromArray(array $arr, array $remove): array
	{
		return \array_filter(
			$arr,
			function ($k) use ($remove) {
				return !\in_array($k, $remove);
			},
			\ARRAY_FILTER_USE_KEY
		);
	}

	/**
	 * Create entity object from parsed body.
	 *
	 * @param array $parsed Parsed body.
	 * @since 1.0.10
	 * @return self
	 */
	abstract public static function fromBody(array $parsed);

	/**
	 * Create entity object from record object.
	 *
	 * @param stdClass $record
	 * @since 1.0.10
	 * @return self
	 */
	abstract public static function fromRecord(stdClass $record);

	/**
	 * Create entity object with defaults.
	 *
	 * @since 1.0.10
	 * @return self
	 */
	abstract public static function create();

	/**
	 * Get the primary key column name.
	 *
	 * @since 1.0.10
	 * @return string
	 */
	abstract public static function primaryKey(): string;

	/**
	 * Get repository.
	 *
	 * @since 1.0.10
	 * @return WPRepository
	 */
	abstract public static function getRepo(): WPRepository;
}
