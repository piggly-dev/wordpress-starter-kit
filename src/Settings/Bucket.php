<?php
namespace Piggly\Wordpress\Settings;

use RuntimeException;

/**
 * The Bucket class is a collection of
 * settings keys.
 *
 * @package \Piggly\Wordpress
 * @subpackage \Piggly\Wordpress\Settings
 * @version 1.0.0
 * @since 1.0.0
 * @category Settings
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2021 Piggly Lab <dev@piggly.com.br>
 */
class Bucket
{
	/**
	 * Settings array.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	protected $_settings = [];

	/**
	 * Set setting by key.
	 * 
	 * Call set_{$key}($default) method if it
	 * exists.
	 * 
	 * When $value is an array import it to
	 * a new Bucket.
	 *
	 * @param string $key
	 * @param Bucket|mixed $value
	 * @since 1.0.0
	 * @return self
	 */
	public function set ( string $key, $value )
	{ 
		if ( \method_exists($this, 'set_'.$key) )
		{ 
			$setter = 'set_'.$key; 
			$this->{$setter}($value);
			return $this; 
		}

		if ( \is_array($value) )
		{ 
			$this->_settings[$key] = (new Bucket())->import($value);
			return $this; 
		}

		$this->_settings[$key] = $value; 
		return $this; 
	}

	/**
	 * Get setting by key.
	 * 
	 * Call get_{$key}($default) method if it
	 * exists.
	 *
	 * @param string $key
	 * @param mixed $default
	 * @since 1.0.0
	 * @return mixed
	 */
	public function get ( string $key, $default = null )
	{ 
		if ( \method_exists($this, 'get_'.$key) )
		{ $getter = 'get_'.$key; return $this->{$getter}($default); }

		return $this->_settings[$key] ?? $default; 
	}

	/**
	 * Check if has setting by key.
	 *
	 * @param string $key
	 * @since 1.0.0
	 * @return boolean
	 */
	public function has ( string $key ) : bool
	{ return isset($this->_settings[$key]); }

	/**
	 * Remove setting by key.
	 *
	 * @param string $key
	 * @param mixed $default
	 * @since 1.0.0
	 * @return mixed
	 */
	public function remove ( string $key )
	{ unset($this->_settings[$key]); return $this; }

	/**
	 * Import an array to bucket.
	 * 
	 * It will call import_{$key}($value) method when
	 * it exists to add setting key to bucket.
	 *
	 * @param array<Bucket|mixed> $data
	 * @param boolean $overwrite If should overwrite keys.
	 * @since 1.0.0
	 * @return self
	 */
	public function import ( array $data, bool $overwrite = true )
	{
		foreach ( $data as $key => $value )
		{
			if ( $overwrite || !$this->has($key) )
			{ 
				if ( \method_exists($this, 'import_'.$key) )
				{ 
					$setter = 'import_'.$key; 
					$this->{$setter}($value);
					continue; 
				}

				if ( \is_array($value) )
				{ 
					$this->_settings[$key] = (new Bucket())->import($value);
					continue;
				}

				$this->_settings[$key] = $value; 
				continue; 
			}

			if ( $this->_settings[$key] instanceof Bucket )
			{ 
				if ( !($value instanceof Bucket) || \is_array($value) )
				{ throw new RuntimeException(\sprintf('Setting key `%s` must be a Bucket object or an array.', $key)); }

				$value = $value instanceof Bucket ? $value->export() : $value;
				$this->_settings[$key]->import($value, $overwrite);
			}
		}

		return $this;
	}

	/**
	 * Export current bucket to an array.
	 * 
	 * It will call export_{$key}($value) method when
	 * it exists to export setting key from bucket.
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	public function export () : array
	{
		$settings = [];

		foreach ( $this->_settings as $key => $value )
		{
			if ( $value instanceof Bucket )
			{ $settings[$key] = $value->export(); continue; }
			
			if ( \method_exists($this, 'import_'.$key) )
			{ 
				$getter = 'export_'.$key; 
				$settings[$key] = $this->{$getter}($value);
				continue; 
			}

			$settings[$key] = $value;
		}

		return $settings;
	}
}