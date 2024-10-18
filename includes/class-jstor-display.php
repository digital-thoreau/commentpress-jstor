<?php
/**
 * CommentPress JSTOR Display class.
 *
 * Handles front-end functionality for CommentPress JSTOR.
 *
 * @package CommentPress_JSTOR
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


/**
 * CommentPress JSTOR Display class.
 *
 * A class that encapsulates front-end functionality.
 *
 * @since 0.1
 */
class Commentpress_JSTOR_Display {

	/**
	 * Plugin object.
	 *
	 * @since 0.1
	 * @access public
	 * @var Commentpress_JSTOR
	 */
	public $plugin;

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

		// Include styles.
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ], 20 );

		// Include scripts.
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 20 );

		// Add markup to CommentPress.
		add_action( 'commentpress_after_paragraph_comments', [ $this, 'add_markup' ], 10 );

	}

	/**
	 * Enqueue any styles needed by our public pages.
	 *
	 * @since 0.1
	 */
	public function enqueue_styles() {

		// Maybe restrict appearance.
		if ( ! $this->plugin->admin->can_view() ) {
			return;
		}

		// Check settings.
		if ( ! $this->plugin->admin->is_active() ) {
			return;
		}

		// Enqueue our front-end CSS.
		wp_enqueue_style(
			'commentpress_jstor_custom_css',
			COMMENTPRESS_JSTOR_URL . 'assets/css/commentpress-jstor.css',
			null,
			COMMENTPRESS_JSTOR_VERSION,
			'all' // Media.
		);

	}

	/**
	 * Enqueue any scripts needed by our public pages.
	 *
	 * @since 0.1
	 */
	public function enqueue_scripts() {

		// Maybe restrict appearance.
		if ( ! $this->plugin->admin->can_view() ) {
			return;
		}

		// Check settings.
		if ( ! $this->plugin->admin->is_active() ) {
			return;
		}

		// Enqueue our custom Javascript.
		wp_enqueue_script(
			'commentpress_jstor_custom_js',
			COMMENTPRESS_JSTOR_URL . 'assets/js/commentpress-jstor.js',
			[ 'jquery' ],
			COMMENTPRESS_JSTOR_VERSION,
			false
		);

		// Get link behaviour.
		$link = '';
		if ( 'y' === $this->plugin->admin->setting_get( 'link' ) ) {
			$link = ' target="_blank"';
		}

		// Localisation array.
		$vars = [
			'localisation' => [
				'not_found'      => esc_html__( 'No references were found on JSTOR.', 'commentpress-jstor' ),
				'snippet_link'   => esc_html__( 'View on JSTOR', 'commentpress-jstor' ),
				'triggered_text' => esc_html__( 'References found in JSTOR articles', 'commentpress-jstor' ),
			],
			'interface'    => [
				'spinner' => plugins_url( 'assets/images/loading.gif', COMMENTPRESS_JSTOR_FILE ),
			],
			'jstor'        => [
				'work'   => $this->plugin->admin->setting_get( 'work' ),
				'token'  => $this->plugin->admin->setting_get( 'token' ),
				'fields' => $this->plugin->admin->setting_get( 'fields' ),
				'link'   => $link,
			],
		];

		// Localise the WordPress way.
		wp_localize_script(
			'commentpress_jstor_custom_js',
			'CommentPress_JSTOR_Settings',
			$vars
		);

	}

	/**
	 * Add markup to CommentPress paragraph wrappers.
	 *
	 * @since 0.1
	 *
	 * @param str $text_sig The text signature of the paragraph.
	 */
	public function add_markup( $text_sig ) {

		// Maybe restrict appearance.
		if ( ! $this->plugin->admin->can_view() ) {
			return;
		}

		// Check settings.
		if ( ! $this->plugin->admin->is_active() ) {
			return;
		}

		// Bail if whole page.
		if ( empty( $text_sig ) ) {
			return;
		}

		// Build markup.
		$markup  = '<div class="commentpress_jstor">';
		$markup .= '<p class="commentpress_jstor_trigger" data-jstor-textsig="' . esc_attr( $text_sig ) . '">' .
						esc_html__( 'Find references in JSTOR articles', 'commentpress-jstor' ) .
					'</p>';
		$markup .= '</div>';

		// Show it.
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $markup;

	}

}
