<?php
namespace Piggly\Wordpress\Settings;

use RuntimeException;

/**
 * The Bucket class is a collection of
 * settings keys.
 *
 * @package \Piggly\Wordpress
 * @subpackage \Piggly\Wordpress\Settings
 * @version 1.0.3
 * @since 1.0.3
 * @category Settings
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2021 Piggly Lab <dev@piggly.com.br>
 */
class NonKeyingBucket extends Bucket
{
	/**
	 * Reset bucket with array data.
	 *
	 * @param array $bucket
	 * @since 1.0.3
	 * @return NonKeyingBucket
	 */
	public function set ( array $bucket )
	{
		$this->_settings = $this->import($bucket);
		return $this;
	}

	/**
	 * Add new value to bucket.
	 * 
	 * When $value is an array, import it 
	 * as a new Bucket class.
	 *
	 * @param string $key
	 * @param Bucket|mixed $value
	 * @since 1.0.3
	 * @return NonKeyingBucket
	 */
	public function push ( $value )
	{ 
		if ( \is_array($value) )
		{ 
			$bucket = self::isAssociative($value) ? KeyingBucket::class : NonKeyingBucket::class;
			$this->_settings[] = (new $bucket())->import($value);
			return $this; 
		}

		$this->_settings[] = $value; 
		return $this; 
	}

	/**
	 * Remove the last element from bucket.
	 *
	 * @since 1.0.3
	 * @return NonKeyingBucket
	 */
	public function pop ()
	{
		\array_pop($this->_settings);
		return $this;
	}

	/**
	 * Remove the first element from bucket.
	 *
	 * @since 1.0.3
	 * @return NonKeyingBucket
	 */
	public function shift ()
	{
		\array_shift($this->_settings);
		return $this;
	}

	/**
	 * Get all bucket data.
	 *
	 * @since 1.0.3
	 * @return array
	 */
	public function get () : array
	{ return $this->_settings; }
}