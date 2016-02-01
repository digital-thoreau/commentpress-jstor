<?php

/**
 * CommentPress JSTOR Display Class
 *
 * A class that encapsulates front-end functionality.
 */
class Commentpress_JSTOR_Display {



	/**
	 * Plugin object.
	 *
	 * @since 0.1
	 * @access public
	 * @var object $plugin The plugin object
	 */
	public $plugin;



	/**
	 * Constructor.
	 *
	 * @since 0.1
	 *
	 * @param object $parent The global plugin object
	 */
	public function __construct( $parent ) {

		// store reference
		$this->plugin = $parent;

	}



	/**
	 * Perform plugin activation tasks.
	 *
	 * @since 0.1
	 *
	 * @return void
	 */
	public function activate() {

	}



	/**
	 * Perform plugin deactivation tasks.
	 *
	 * @since 0.1
	 *
	 * @return void
	 */
	public function deactivate() {

	}



	/**
	 * Register WordPress hooks.
	 *
	 * @since 0.1
	 *
	 * @return void
	 */
	public function register_hooks() {

		// include styles
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 20 );

		// include scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 20 );

		// add markup to CommentPress
		add_action( 'commentpress_after_paragraph_comments', array( $this, 'add_markup' ), 10, 1 );

	}



	/**
	 * Enqueue any styles needed by our public pages.
	 *
	 * @since 0.1
	 *
	 * @return void
	 */
	public function enqueue_styles() {

		// maybe restrict appearance
		if ( ! $this->plugin->admin->can_view() ) return;

		// check settings
		if ( ! $this->plugin->admin->is_active() ) return;

		// enqueue our front-end CSS
		wp_enqueue_style(
			'commentpress_jstor_custom_css',
			COMMENTPRESS_JSTOR_URL . 'assets/css/commentpress-jstor.css',
			null,
			COMMENTPRESS_JSTOR_VERSION,
			'all' // media
		);

	}



	/**
	 * Enqueue any scripts needed by our public pages.
	 *
	 * @since 0.1
	 *
	 * @return void
	 */
	public function enqueue_scripts() {

		// maybe restrict appearance
		if ( ! $this->plugin->admin->can_view() ) return;

		// check settings
		if ( ! $this->plugin->admin->is_active() ) return;

		// enqueue our custom Javascript
		wp_enqueue_script(
			'commentpress_jstor_custom_js',
			COMMENTPRESS_JSTOR_URL . 'assets/js/commentpress-jstor.js',
			array( 'jquery' ),
			COMMENTPRESS_JSTOR_VERSION
		);

		// localisation array
		$vars = array(
			'localisation' => array(
				'not_found' => __( 'No references were found on JSTOR.', 'commentpress-jstor' ),
				'snippet_link' => __( 'View on JSTOR', 'commentpress-jstor' ),
			),
			'interface' => array(
				'spinner' => plugins_url( 'assets/images/loading.gif', COMMENTPRESS_JSTOR_FILE ),
			),
			'jstor' => array(
				'work' => $this->plugin->admin->setting_get( 'work' ),
				'token' => $this->plugin->admin->setting_get( 'token' ),
				'fields' => $this->plugin->admin->setting_get( 'fields' ),
			),
		);

		// localise via WordPress
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
	 * @param str $text_sig The text signature of the paragraph
	 * @return void
	 */
	public function add_markup( $text_sig ) {

		// maybe restrict appearance
		if ( ! $this->plugin->admin->can_view() ) return;

		// check settings
		if ( ! $this->plugin->admin->is_active() ) return;

		// bail if whole page
		if ( empty( $text_sig ) ) return;

		// build markup
		$markup = '<div class="commentpress_jstor">';
		$markup .= '<p class="commentpress_jstor_trigger" data-jstor-textsig="' . $text_sig . '">' .
						__( 'Find references in JSTOR articles', 'commentpress-jstor' ) .
					'</p>';
		$markup .= '</div>';

		// show it
		echo $markup;

	}



} // class Commentpress_JSTOR_Display ends



