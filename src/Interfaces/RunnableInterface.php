<?php

namespace Piggly\Wordpress\Interfaces;

/**
 * The runnable interface implements the run()
 * method to run all business logic applied to a
 * class.
 *
 * @package \Piggly\Wordpress
 * @subpackage \Piggly\Wordpress\Interfaces
 * @version 2.0.0
 * @since 2.0.0
 * @category Interfaces
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2022 Piggly Lab <dev@piggly.com.br>
 */
interface RunnableInterface
{
	/**
	 * Method to run all business logic.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function run();
}
