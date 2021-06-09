<?php
namespace Piggly\Dev\Wordpress\Settings;

use DateTime;
use Piggly\Wordpress\Settings\Bucket;
use RuntimeException;

class CustomBucket extends Bucket
{
	/**
	 * Validate if $number is integer or float.
	 *
	 * @param integer|float $number
	 * @return self
	 * @throws RuntimeException
	 */
	public function set_number ( $number )
	{
		if ( \is_int($number) || \is_float($number) )
		{ $this->_settings['number'] = $number; return $this; }

		throw new RuntimeException('Number setting must be an integer or float number.');
	}

	/**
	 * Get number or zero, ignoring $default.
	 *
	 * @param mixed $default
	 * @return integer|float
	 */
	public function get_number ( $default )
	{ return $this->_settings['number'] ?? 0; }

	/**
	 * Import $data as DateTime object.
	 *
	 * @param DateTime $date
	 * @return void
	 */
	public function import_created_at ( $date )
	{ $this->_settings['created_at'] = !($date instanceof DateTime) ? (new DateTime())->setTimestamp($date) : $date; }

	/**
	 * Export DateTime object as timestamp or null.
	 *
	 * @return integer|null
	 */
	public function export_created_at ()
	{ return isset($this->_settings['created_at']) ? $this->_settings['created_at']->getTimestamp() : null; }
}