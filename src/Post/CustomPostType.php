<?php

namespace Piggly\Wordpress\Post;

use DateTime;
use Piggly\Wordpress\Post\Fields\InputField;
use Piggly\Wordpress\Repository\WPRepository;
use Piggly\Wordpress\Tables\RecordTable;
use Piggly\Wordpress\Core\Scaffold\Initiable;
use Piggly\Wordpress\Core\WP;
use Exception;
use Piggly\Wordpress\Connector;

/**
 * Manage the custom post type structure.
 *
 * @package \Piggly\Wordpress
 * @subpackage \Piggly\Wordpress\Post
 * @version 1.0.7
 * @since 1.0.7
 * @category Post
 * @author Caique Araujo <caique@piggly.com.br>
 * @author Piggly Lab <dev@piggly.com.br>
 * @license MIT
 * @copyright 2022 Piggly Lab <dev@piggly.com.br>
 */
abstract class CustomPostType extends Initiable
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
	 * @since 1.0.7
	 * @var array
	 */
	protected array $current_fields = [];

	/**
	 * Primary key column name.
	 *
	 * @since 1.0.7
	 * @var string
	 */
	protected string $primary_key = 'id';

	/**
	 * Hide when updating or insertin.
	 *
	 * @since 1.0.7
	 * @var array
	 */
	protected array $hidden = [];

	/**
	 * Run startup method to class create
	 * it own instance.
	 *
	 * @since 1.0.7
	 * @return void
	 */
	public function startup()
	{
		WP::add_action('admin_menu', $this, 'add_menu', 99);
	}

	/**
	 * Create a new menu at Wordpress admin menu bar.
	 *
	 * @since 1.0.7
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
	 * @since 1.0.7
	 * @return void
	 */
	public function enqueue_scripts()
	{
		wp_enqueue_media();

		wp_enqueue_script(
			Connector::domain() . '-admin-engine',
			Connector::plugin()->getUrl() . '/assets/js/admin-engine.js',
			null,
			Connector::plugin()->getVersion(),
			true
		);

		wp_enqueue_style(
			Connector::domain() . '-admin-styles',
			Connector::plugin()->getUrl() . '/assets/css/admin-styles.css',
			null,
			Connector::plugin()->getVersion(),
			'all'
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

		echo '<div id="eno-wc-order-contracts-wrapper">';
		require_once Connector::plugin()->getTemplatePath() .
			'admin/post-types-table.php';
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
			$this->action();
			$this->post_load();
		} catch (Exception $e) {
			echo '<div class="notice notice-error is-dismissible"><p>' .
				$e->getMessage() .
				'</p></div>';
		}

		echo '<div id="eno-wc-order-contracts-wrapper" class="container-wrapper">';
		require_once Connector::plugin()->getTemplatePath() .
			'admin/post-types-content.php';
		echo '</div>';
	}

	/**
	 * Fill query values from query string data.
	 *
	 * @since 1.0.7
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
			throw new Exception('Ação indisponível.');
		}
	}

	/**
	 * Prepare fields to editing.
	 *
	 * @since 1.0.7
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
					)
				);
			}

			$fields[$this->primary_key] = $this->query_id;
		}

		$this->current_fields = $fields;
	}

	/**
	 * Process action.
	 *
	 * @since 1.0.7
	 * @return void
	 */
	protected function action()
	{
		switch ($this->query_action) {
			case 'edit':
			case 'add':
				return $this->edit();
			case 'remove':
				return $this->remove();
		}
	}

	/**
	 * Load any data required before show fields.
	 *
	 * @since 1.0.7
	 * @return void
	 */
	protected function post_load()
	{
	}

	/**
	 * Get fields from post request.
	 *
	 * @since 1.0.7
	 * @return void
	 */
	protected function get_fields()
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			return;
		}

		$prefix = $this->fieldPrefix();

		$nonce = \filter_input(
			\INPUT_POST,
			$prefix . 'nonce',
			\FILTER_DEFAULT,
			\FILTER_NULL_ON_FAILURE
		);

		/* Verify the nonce before proceeding. */
		if (empty($nonce) || !\wp_verify_nonce($nonce, $prefix . 'save')) {
			throw new Exception(
				'O nonce para o envio do formulário é inválido.'
			);
		}

		$fields = $this->fieldsStructure();

		foreach ($fields as $field) {
			$_post = \filter_input(
				\INPUT_POST,
				$field->getNameWithPrefix(),
				\FILTER_DEFAULT,
				\FILTER_NULL_ON_FAILURE
			);

			if ($field->isRequired() && empty($_post)) {
				throw new Exception(
					"Campo `{$field->getLabel()}` é obrigatório para prosseguir."
				);
			}

			$this->current_fields[$field->getName()] = $field->parse($_post);
		}
	}

	/**
	 * Edit record.
	 *
	 * @since 1.0.7
	 * @return void
	 */
	protected function edit(): void
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			return;
		}

		$this->get_fields();
		$this->current_fields['updated_at'] = new DateTime(
			'now',
			wp_timezone()
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

		$this->notification(
			\sprintf(
				'%s salvo com sucesso. Você será redirecionado em instantes.',
				static::singularName()
			)
		);

		$this->redirectTo($this->current_fields[$this->primary_key]);
	}

	/**
	 * Remove record.
	 *
	 * @since 1.0.7
	 * @return void
	 */
	protected function remove(): void
	{
		if (empty($this->query_id)) {
			throw new Exception(
				\sprintf(
					'O ID do %s não pode ser vazio.',
					\strtolower(static::singularName())
				)
			);
		}

		$this->getRepository()::delete([
			$this->primary_key => $this->query_id,
		]);

		$this->notification(
			\sprintf(
				'%s removido com sucesso. Você será redirecionado em instantes.',
				static::singularName()
			)
		);

		$this->redirectToTable();
	}

	/**
	 * Redirect to record by id.
	 *
	 * @param mixed $id
	 * @since 1.0.7
	 * @return void
	 */
	protected function redirectTo($id)
	{
		$url = \add_query_arg(
			[
				'id' => $id,
				'action' => 'edit',
			],
			\admin_url('admin.php?page=' . static::getSlug() . '-content')
		); ?>
<script lang="javascript">
	setTimeout(function() {
		window.location.href = "<?= $url ?>";
	}, 3000);
</script>
<?php
	}

	/**
	 * Redirect to table.
	 *
	 * @since 1.0.7
	 * @return void
	 */
	protected function redirectToTable()
	{
		$url = \admin_url('admin.php?page=' . static::getSlug()); ?>
<script lang="javascript">
	setTimeout(function() {
		window.location.href = "<?= $url ?>";
	}, 3000);
</script>
<?php
	}

	/**
	 * Echo notification in screen.
	 *
	 * @param string $message
	 * @param string $type
	 * @since 1.0.7
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
	 * @since 1.0.7
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
	 * @since 1.0.7
	 * @return array<InputField>
	 */
	abstract public function fieldsStructure(): array;

	/**
	 * Get custom post type icon.
	 *
	 * @since 1.0.7
	 * @return string
	 */
	abstract public static function getIcon(): string;

	/**
	 * Get custom post type slug.
	 *
	 * @since 1.0.7
	 * @return string
	 */
	abstract public static function getSlug(): string;

	/**
	 * Get custom post type singular name.
	 *
	 * @since 1.0.7
	 * @return string
	 */
	abstract public static function singularName(): string;

	/**
	 * Get custom post type plural name.
	 *
	 * @since 1.0.7
	 * @return string
	 */
	abstract public static function pluralName(): string;

	/**
	 * Get custom post type field prefix.
	 *
	 * @since 1.0.7
	 * @return string
	 */
	abstract public static function fieldPrefix(): string;

	/**
	 * Get the current repository.
	 *
	 * @since 1.0.7
	 * @return WPRepository
	 */
	abstract public static function getRepository(): WPRepository;

	/**
	 * Get the current table.
	 *
	 * @since 1.0.7
	 * @return RecordTable
	 */
	abstract public static function getTable();
}
