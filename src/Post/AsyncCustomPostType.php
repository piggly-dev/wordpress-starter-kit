<?php

namespace Piggly\Wordpress\Post;

use DateTime;
use Piggly\Wordpress\Post\Fields\InputField;
use Piggly\Wordpress\Repository\WPRepository;
use Piggly\Wordpress\Tables\RecordTable;
use Piggly\Wordpress\Core\WP;
use Exception;
use Piggly\Wordpress\Connector;
use Piggly\Wordpress\Core\Scaffold\JSONable;

/**
 * Manage the custom post type structure.
 *
 * @package \Piggly\Wordpress
 * @subpackage \Piggly\Wordpress\Post
 * @version 1.0.9
 * @since 1.0.9
 * @category Post
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2022 Piggly Lab <dev@piggly.com.br>
 */
abstract class AsyncCustomPostType extends JSONable
{
	/**
	 * ID from query string variable.
	 *
	 * @var integer|null
	 */
	protected ?int $query_id = null;

	/**
	 * Action from query string variable.
	 *
	 * @var integer|null
	 */
	protected string $query_action = 'add';

	/**
	 * Current fields.
	 *
	 * @since 1.0.9
	 * @var array
	 */
	protected array $current_fields = [];

	/**
	 * Primary key column name.
	 *
	 * @since 1.0.9
	 * @var string
	 */
	protected string $primary_key = 'id';

	/**
	 * Hide when updating or insertin.
	 *
	 * @since 1.0.9
	 * @var array
	 */
	protected array $hidden = [];

	/**
	 * Version for PGLY WPS SETTINGS lib.
	 *
	 * @since 1.0.9
	 * @var string
	 */
	protected string $js_version = '0.2.0';

	/**
	 * File for template of content page
	 * in templates folder of plugin.
	 *
	 * @since 1.0.9
	 * @var string
	 */
	protected string $_table_page = 'admin/post-types-table.php';

	/**
	 * File for template of content page
	 * in templates folder of plugin.
	 *
	 * @since 1.0.9
	 * @var string
	 */
	protected string $_content_page = 'admin/post-types-content.php';

	/**
	 * Run startup method to class create
	 * it own instance.
	 *
	 * @since 1.0.9
	 * @return void
	 */
	public function startup()
	{
		WP::add_action('admin_menu', $this, 'add_menu', 99);
	}

	/**
	 * Create a new menu at Wordpress admin menu bar.
	 *
	 * @since 1.0.9
	 * @return void
	 */
	public function add_menu()
	{
		$slug = static::getSlug();

		add_menu_page(
			'Visualizar todos ' . static::pluralName(),
			static::pluralName(),
			'edit_posts',
			$slug,
			[$this, 'table_page'],
			static::getIcon()
		);

		add_submenu_page(
			$slug,
			'Visualizar todos ' . static::pluralName(),
			'Todos ' . static::pluralName(),
			'edit_posts',
			$slug,
			'',
			1
		);

		add_submenu_page(
			$slug,
			'Adicionar novo ' . static::singularName(),
			'Adicionar novo',
			'edit_posts',
			$slug . '-content',
			[$this, 'content_page'],
			10
		);
	}

	/**
	 * Enqueue scripts and styles.
	 *
	 * @since 1.0.9
	 * @return void
	 */
	public function enqueue_scripts()
	{
		\wp_enqueue_media();

		\wp_enqueue_script(
			\sprintf('pgly-wps-settings-%s-js', $this->js_version),
			Connector::plugin()->getUrl() . '/assets/vendor/js/pgly-wps-settings.js',
			null,
			Connector::plugin()->getVersion(),
			true
		);

		\wp_enqueue_style(
			\sprintf('pgly-wps-settings-%s-css', $this->js_version),
			Connector::plugin()->getUrl() . '/assets/vendor/css/pgly-wps-settings.css',
			null,
			Connector::plugin()->getVersion(),
			'all'
		);

		\wp_localize_script(
			\sprintf('pgly-wps-settings-%s-js', $this->js_version),
			'pglyWps',
			[
				'ajax_url' => admin_url('admin-ajax.php'),
				'x_security' => \wp_create_nonce($this->fieldPrefix().'_nonce'),
				'plugin_url' => admin_url('admin.php?page='.Connector::domain()),
				'assets_url' => Connector::plugin()->getUrl()
			]
		);
	}

	/**
	 * Load page to view table listing.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function table_page()
	{
		$this->enqueue_scripts();

		echo '<div id="pgly-wps-plugin" class="pgly-wps--settings">';
		require_once Connector::plugin()->getTemplatePath() . $this->_table_page;
		echo '</div>';
	}

	/**
	 * Load page to view table listing.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function content_page()
	{
		$this->enqueue_scripts();

		try {
			$this->fill_query();
			$this->prepare_fields();
			$this->post_load();
		} catch (Exception $e) {
			echo '<div class="notice notice-error is-dismissible"><p>' .
				$e->getMessage() .
				'</p></div>';

			if ($e->getCode(404)) {
				exit;
			}
		}

		echo '<div id="pgly-wps-plugin" class="pgly-wps--settings">';
		require_once Connector::plugin()->getTemplatePath() . $this->_content_page;
		echo '</div>';
	}

	/**
	 * Fill query values from query string data.
	 *
	 * @since 1.0.9
	 * @return void
	 */
	protected function fill_query()
	{
		$id = filter_input(
			\INPUT_GET,
			'id',
			\FILTER_SANITIZE_NUMBER_INT,
			\FILTER_NULL_ON_FAILURE
		);

		$action = filter_input(
			\INPUT_GET,
			'action',
			\FILTER_SANITIZE_STRING,
			\FILTER_NULL_ON_FAILURE
		);

		$this->query_id = !empty($id) ? \intval($id) : null;
		$this->query_action = empty($action) ? 'add' : $action;

		// Validate action
		if (!\in_array($this->query_action, ['edit', 'remove', 'add'])) {
			throw new Exception('Ação indisponível.', 404);
		}
	}

	/**
	 * Prepare fields to editing.
	 *
	 * @since 1.0.9
	 * @return void
	 */
	protected function prepare_fields()
	{
		// Default fields data
		$fields = $this->current_fields;

		// Try to load fields
		if (!empty($this->query_id)) {
			$fields = $this->getRepository()::byId($this->query_id);

			if (empty($fields)) {
				throw new Exception(
					\sprintf(
						'O %s não foi localizado, tente novamente mais tarde ou selecione outro %s.',
						static::singularName(),
						static::singularName()
					),
					404
				);
			}

			$fields[$this->primary_key] = $this->query_id;
		}

		$this->current_fields = $fields;
	}

	/**
	 * Load any data required before show fields.
	 *
	 * @since 1.0.9
	 * @return void
	 */
	protected function post_load()
	{
	}

	/**
	 * Edit record.
	 *
	 * @since 1.0.9
	 * @throws Exception
	 */
	protected function edit(array $fields = []): array
	{
		$this->current_fields = $fields;
		$this->current_fields['updated_at'] = new DateTime(
			'now',
			\wp_timezone()
		);

		if (!empty($this->current_fields[$this->primary_key])) {
			$this->getRepository()::update(
				$this->_removeFromArray($this->current_fields, $this->hidden),
				[
					$this->primary_key =>
						$this->current_fields[$this->primary_key],
				]
			);
		} else {
			$this->current_fields[
				$this->primary_key
			] = $this->getRepository()::insert(
				$this->_removeFromArray($this->current_fields, $this->hidden),
				$this->primary_key
			)[$this->primary_key];
		}

		return $this->current_fields;
	}

	/**
	 * Remove record.
	 *
	 * @since 1.0.9
	 * @return void
	 * @throws Exception
	 */
	protected function remove(string $id): void
	{
		$this->getRepository()::delete([
			$this->primary_key => $id,
		]);
	}
	
	/**
	 * Echo notification in screen.
	 *
	 * @param string $message
	 * @param string $type
	 * @since 1.0.9
	 * @return void
	 */
	protected function notification(
		string $message,
		string $type = 'success'
	) {
		echo "<div class=\"notice notice-{$type}\"><p>{$message}</p></div>";
	}

	/**
	 * Remove fields from array.
	 *
	 * @param array $arr
	 * @param array $remove
	 * @since 1.0.9
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
	 * Get the fields structure.
	 *
	 * @since 1.0.9
	 * @return array<InputField>
	 */
	abstract public function fieldsStructure(): array;

	/**
	 * Get custom post type icon.
	 *
	 * @since 1.0.9
	 * @return string
	 */
	abstract public static function getIcon(): string;

	/**
	 * Get custom post type slug.
	 *
	 * @since 1.0.9
	 * @return string
	 */
	abstract public static function getSlug(): string;

	/**
	 * Get custom post type singular name.
	 *
	 * @since 1.0.9
	 * @return string
	 */
	abstract public static function singularName(): string;

	/**
	 * Get custom post type plural name.
	 *
	 * @since 1.0.9
	 * @return string
	 */
	abstract public static function pluralName(): string;

	/**
	 * Get custom post type field prefix.
	 *
	 * @since 1.0.9
	 * @return string
	 */
	abstract public static function fieldPrefix(): string;

	/**
	 * Get the current repository.
	 *
	 * @since 1.0.9
	 * @return WPRepository
	 */
	abstract public static function getRepository(): WPRepository;

	/**
	 * Get the current table.
	 *
	 * @since 1.0.9
	 * @return RecordTable
	 */
	abstract public static function getTable();
}
