<?php

namespace Piggly\Wordpress\Notices;

use Piggly\Wordpress\Connector;
use Piggly\Wordpress\Notices\Entities\Notice;

/**
 * The manager class to notices.
 *
 * @package \Piggly\Wordpress
 * @subpackage \Piggly\Wordpress\Notices
 * @version 1.0.0
 * @since 1.0.0
 * @category Notices
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2021 Piggly Lab <dev@piggly.com.br>
 */
class NoticeManager
{
	/**
	 * Echo notification in screen.
	 *
	 * @param string $message
	 * @param string $type
	 * @since 1.0.7
	 * @return void
	 */
	public static function echoNotice(
		string $message,
		string $type = 'success'
	) {
		echo "<div class=\"notice notice-{$type}\"><p>{$message}</p></div>";
	}

	/**
	 * Add a notice to admin.
	 *
	 * @param Notice $notice
	 * @since 1.0.0
	 * @return void
	 */
	public function addNotice(Notice $notice)
	{
		if ($notice->isTransient()) {
			$this->_transient($notice);
		}

		$this->_immediate($notice);
	}

	/**
	 * Display all notices at transient.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	protected function displayTransient()
	{
		$notices = get_transient(Connector::plugin()->getNotices()) ?? [];

		foreach ($notices as $notice) {
			$notice = \unserialize($notice);
			echo $notice->export();
		}

		delete_transient(Connector::plugin()->getNotices());
	}

	/**
	 * Add a new notice to transient.
	 *
	 * @param Notice $notice
	 * @since 1.0.0
	 * @return void
	 */
	protected function _transient(Notice $notice)
	{
		$notices   = get_transient(Connector::plugin()->getNotices()) ?? [];
		$notices[] = \serialize($notice);

		set_transient(Connector::plugin()->getNotices(), $notice, 45);
	}

	/**
	 * Run notice immediate.
	 *
	 * @param Notice $notice
	 * @since 1.0.0
	 * @return void
	 */
	protected function _immediate(Notice $notice)
	{
		add_action('admin_notices', [$notice, 'export']);
	}
}
