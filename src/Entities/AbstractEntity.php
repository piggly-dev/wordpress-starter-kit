<?php

namespace Piggly\Wordpress\Entities;

use DateTime;
use Exception;
use Piggly\Wordpress\Models\AbstractModel;
use Piggly\Wordpress\Post\Fields\Form;
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
abstract class AbstractEntity extends AbstractModel
{
	/**
	 * Convert entity to a form default values array
	 * to render data to HTML.
	 *
	 * @since 1.0.12
	 * @return array
	 */
	abstract public function toFormArray(): array;

	/**
	 * Get the HTML form.
	 *
	 * @param array $options
	 * @since 1.0.12
	 * @return Form
	 */
	abstract public function form(array $options = []): Form;
}
