<?php
/**
 * CommentPress JSTOR Admin class.
 *
 * Handles admin functionality for CommentPress JSTOR.
 *
 * @package CommentPress_JSTOR
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * CommentPress JSTOR Admin class.
 *
 * A class that encapsulates admin functionality.
 *
 * @since 0.1
 */
class Commentpress_JSTOR_Admin {

	/**
	 * Plugin object.
	 *
	 * @since 0.1
	 * @access public
	 * @var Commentpress_JSTOR
	 */
	public $plugin;

	/**
	 * Settings data.
	 *
	 * @since 0.1
	 * @access public
	 * @var array
	 */
	public $settings = [];

	/**
	 * Relative path to the Metabox directory.
	 *
	 * @since 0.2.0
	 * @access private
	 * @var string
	 */
	private $metabox_path = 'assets/templates/metaboxes/';

	/**
	 * "Work Code" settings key.
	 *
	 * @since 4.0
	 * @access private
	 * @var string
	 */
	private $key_work = 'commentpress_jstor_work';

	/**
	 * "Matchmaker API token" settings key.
	 *
	 * @since 4.0
	 * @access private
	 * @var string
	 */
	private $key_token = 'commentpress_jstor_token';

	/**
	 * "JSTOR search fields" settings key.
	 *
	 * @since 4.0
	 * @access private
	 * @var string
	 */
	private $key_fields = 'commentpress_jstor_fields';

	/**
	 * "JSTOR search link" settings key.
	 *
	 * @since 4.0
	 * @access private
	 * @var string
	 */
	private $key_link = 'commentpress_jstor_link';

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 *
	 * @param Commentpress_JSTOR $parent The plugin object.
	 */
	public function __construct( $parent ) {

		// Store reference.
		$this->plugin = $parent;

		// Initialise when the plugin has loaded.
		add_action( 'commentpress_jstor/loaded', [ $this, 'initialise' ] );

	}

	/**
	 * Initialise this object.
	 *
	 * @since 0.2.0
	 */
	public function initialise() {

		// Only do this once.
		static $done;
		if ( isset( $done ) && true === $done ) {
			return;
		}

		// Load settings array.
		$this->settings = get_option( 'commentpress_jstor_settings', $this->settings );

		// Register hooks.
		$this->register_hooks();

		// We're done.
		$done = true;

	}

	/**
	 * Register WordPress hooks.
	 *
	 * @since 0.1
	 */
	public function register_hooks() {

		// Add our metabox to the Site Settings screen.
		add_action( 'commentpress/core/settings/site/metaboxes/after', [ $this, 'settings_meta_box_append' ], 100 );

		// Save data from Site Settings form submissions.
		add_action( 'commentpress/core/settings/site/save/before', [ $this, 'settings_meta_box_save' ] );

	}

	/**
	 * Restrict appearance of this plugin.
	 *
	 * @since 0.1
	 *
	 * @return bool True if allowed to view, false otherwise.
	 */
	public function can_view() {

		// Restrict to admins for now.
		if ( is_super_admin() ) {
			return true;
		}

		// Enable for everyone.
		return true;

	}

	/**
	 * Restrict functionality of this plugin until all options are filled out.
	 *
	 * @since 0.1
	 *
	 * @return bool $enabled True if enabled, false otherwise.
	 */
	public function is_active() {

		// Deny by default.
		$enabled = false;

		// Get settings.
		$work   = $this->setting_get( 'work' );
		$token  = $this->setting_get( 'token' );
		$fields = $this->setting_get( 'fields' );

		// Check essential settings have values.
		if ( ! empty( $work ) && ! empty( $token ) && ! empty( $fields ) ) {
			$enabled = true;
		}

		// --<
		return $enabled;

	}

	/**
	 * Appends our metabox to the Site Settings screen.
	 *
	 * @since 0.2.0
	 *
	 * @param string $screen_id The Site Settings Screen ID.
	 */
	public function settings_meta_box_append( $screen_id ) {

		// Create "Theme Customisation" metabox.
		add_meta_box(
			'commentpress_jstor_settings',
			__( 'JSTOR Matchmaker Settings', 'commentpress-jstor' ),
			[ $this, 'settings_meta_box_render' ], // Callback.
			$screen_id, // Screen ID.
			'normal', // Column: options are 'normal' and 'side'.
			'core' // Vertical placement: options are 'core', 'high', 'low'.
		);

	}

	/**
	 * Renders the "Theme Customisation" metabox.
	 *
	 * @since 4.0
	 */
	public function settings_meta_box_render() {

		// Init work.
		$work          = '';
		$existing_work = $this->setting_get( 'work' );
		if ( ! empty( $existing_work ) ) {
			$work = $existing_work;
		}

		// Init token.
		$token          = '';
		$existing_token = $this->setting_get( 'token' );
		if ( ! empty( $existing_token ) ) {
			$token = $existing_token;
		}

		// Init fields.
		$fields          = '';
		$existing_fields = $this->setting_get( 'fields' );
		if ( ! empty( $existing_fields ) ) {
			$fields = $existing_fields;
		}

		// Init link.
		$link          = 'n';
		$existing_link = $this->setting_get( 'link' );
		if ( ! empty( $existing_link ) ) {
			$link = $existing_link;
		}

		// Include template file.
		include COMMENTPRESS_JSTOR_PATH . $this->metabox_path . 'metabox-settings-site-jstor.php';

	}

	/**
	 * Capture and save plugin settings.
	 *
	 * Since this is called by an action once CommentPress has parsed its data,
	 * we can be sure that the data is from the CommentPress form.
	 *
	 * @since 0.1
	 */
	public function settings_meta_box_save() {

		// Get "Work Code" value.
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$work = isset( $_POST[ $this->key_work ] ) ? sanitize_text_field( wp_unslash( $_POST[ $this->key_work ] ) ) : '';
		$this->setting_set( 'work', $work );

		// Get "Matchmaker API token" value.
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$token = isset( $_POST[ $this->key_token ] ) ? sanitize_text_field( wp_unslash( $_POST[ $this->key_token ] ) ) : '';
		$this->setting_set( 'token', $token );

		// Get "JSTOR search fields" value.
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$fields = isset( $_POST[ $this->key_fields ] ) ? sanitize_text_field( wp_unslash( $_POST[ $this->key_fields ] ) ) : '';
		$this->setting_set( 'fields', $fields );

		// Get "JSTOR search link" value.
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$link = isset( $_POST[ $this->key_link ] ) ? sanitize_text_field( wp_unslash( $_POST[ $this->key_link ] ) ) : 'n';
		$this->setting_set( 'link', $link );

		// Save all.
		$this->settings_save();

	}

	/**
	 * Get default plugin settings.
	 *
	 * @since 0.1
	 *
	 * @return array $settings The array of settings, keyed by setting name.
	 */
	public function settings_get_default() {

		// Init return.
		$settings = [];

		// Set default work.
		$settings['work'] = '';

		// Set default token.
		$settings['token'] = '417901c2555ba65649f356626aada7f273390cf3';

		// Set default fields.
		$settings['fields'] = 'docid,work,work_text,chunk_ids,title,journal,' .
			'authors,pages,pubyear,keyterms,similarity,' .
			'match_size,snippet,source';

		// Set default link behaviour to "open in same window".
		$settings['link'] = 'n';

		/**
		 * Filters the default settings.
		 *
		 * @since 0.1
		 *
		 * @param array $settings The default settings.
		 */
		return apply_filters( 'commentpress_jstor_default_settings', $settings );

	}

	/**
	 * Save the plugin's settings array.
	 *
	 * @since 0.1
	 *
	 * @return bool $result True if setting value has changed, false if not or if update failed.
	 */
	public function settings_save() {

		// Update WordPress option and return result.
		return update_option( 'commentpress_jstor_settings', $this->settings );

	}

	/**
	 * Return a value for a specified setting.
	 *
	 * @since 0.1
	 *
	 * @param string $setting_name The setting name.
	 * @param mixed  $default The default setting value.
	 * @return mixed $setting The value of the setting.
	 */
	public function setting_get( $setting_name, $default = false ) {

		// Get setting.
		return array_key_exists( $setting_name, $this->settings ) ? $this->settings[ $setting_name ] : $default;

	}

	/**
	 * Set a value for a specified setting.
	 *
	 * @since 0.1
	 *
	 * @param string $setting_name The setting name.
	 * @param mixed  $value The setting value.
	 */
	public function setting_set( $setting_name, $value ) {

		// Set setting.
		$this->settings[ $setting_name ] = $value;

	}

}
