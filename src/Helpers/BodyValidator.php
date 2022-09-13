<?php

namespace Piggly\Wordpress\Helpers;

use Exception;

/**
 * Validate a body.
 *
 * @package \Piggly\Wordpress
 * @subpackage \Piggly\Wordpress\Helpers
 * @version 1.0.8
 * @since 1.0.8
 * @category Helper
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2022 Piggly Lab <dev@piggly.com.br>
 */
class BodyValidator
{
	/**
	 * Validate raw body based on schema. A schema has
	 * following structure [ 'field-name' => $options, ... ].
	 *
	 * There are few options available, are they:
	 *
	 * 'default' => null, // Default value for field
	 * 'allowed_values' => null, // An array with allowed values for field
	 * 'required' => true, // A boolean indication if field is required or not
	 * 'transform' => function ($v) { return $v; }, // A function to transform value to another
	 * 'validation' => [ function ($v) { throw new Exception(); }] // A bunch of functions that must throw error if value is unexpected
	 *
	 * @param array $raw
	 * @param array $schema
	 * @since 1.0.8
	 * @return array
	 * @throws Exception
	 */
	public static function validate(array $raw, array $schema = []): array
	{
		$isEmpty = function ($var): bool {
			return !\is_null($var) && $var !== '';
		};

		$parsed  = [];

		foreach ($schema as $field => $options) {
			$value = $raw[$field];

			if (!empty($options['allowed_values']) && !\in_array($value, $options['allowed_values'], true)) {
				$value = $options['default'] ?? null;
			}

			if ($isEmpty($value) && $options['required']) {
				throw new Exception(\sprintf('(%s) => O campo é obrigatório...', $field));
			}

			if (!empty($options['transform'])) {
				$value = $options['transform']($value);
			}

			if (!empty($options['validation'])) {
				foreach ($options['validation'] as $func) {
					$func($value);
				}
			}

			$parsed[$field] = $value;
		}

		return $parsed;
	}
}
