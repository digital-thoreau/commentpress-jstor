<?php /*
--------------------------------------------------------------------------------
Plugin Name: CommentPress JSTOR
Plugin URI: http://www.futureofthebook.org/commentpress/
Description: Allows CommentPress to access JSTOR Matchmaker tool from The Reader's Thoreau site.
Author: Institute for the Future of the Book
Version: 0.1
Author URI: http://www.futureofthebook.org
Text Domain: commentpress-jstor
Domain Path: /languages
--------------------------------------------------------------------------------
*/



// set our version here
define( 'COMMENTPRESS_JSTOR_VERSION', '0.1' );

// store reference to this file
if ( ! defined( 'COMMENTPRESS_JSTOR_FILE' ) ) {
	define( 'COMMENTPRESS_JSTOR_FILE', __FILE__ );
}

// store URL to this plugin's directory
if ( ! defined( 'COMMENTPRESS_JSTOR_URL' ) ) {
	define( 'COMMENTPRESS_JSTOR_URL', plugin_dir_url( COMMENTPRESS_JSTOR_FILE ) );
}

// store PATH to this plugin's directory
if ( ! defined( 'COMMENTPRESS_JSTOR_PATH' ) ) {
	define( 'COMMENTPRESS_JSTOR_PATH', plugin_dir_path( COMMENTPRESS_JSTOR_FILE ) );
}



/**
 * CommentPress JSTOR Class
 *
 * A class that encapsulates plugin functionality.
 */
class Commentpress_JSTOR {



	/**
	 * Constructor.
	 *
	 * @since 0.1
	 *
	 * @return object $this
	 */
	public function __construct() {

		// register hooks
		$this->register_hooks();

		// --<
		return $this;

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

		// use translation
		add_action( 'plugins_loaded', array( $this, 'translation' ) );

		// include styles
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 20 );

		// include scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 20 );

		// add markup to CommentPress
		add_action( 'commentpress_after_paragraph_wrapper', array( $this, 'paragraph_wrapper' ), 10, 1 );

	}



	/**
	 * Load translation if present
	 *
	 * @since 0.1
	 *
	 * @return void
	 */
	public function translation() {

		// only use, if we have it...
		if ( function_exists( 'load_plugin_textdomain' ) ) {

			// there are no translations as yet, but they can now be added
			load_plugin_textdomain(

				// unique name
				'commentpress-jstor',

				// deprecated argument
				false,

				// relative path to directory containing translation files
				dirname( plugin_basename( COMMENTPRESS_JSTOR_FILE ) ) . '/languages/'

			);

		}

	}



	/**
	 * Enqueue any styles needed by our public pages.
	 *
	 * @since 0.1
	 *
	 * @return void
	 */
	public function enqueue_styles() {

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

		// enqueue our custom Javascript
		wp_enqueue_script(
			'commentpress_jstor_custom_js',
			COMMENTPRESS_JSTOR_URL . 'assets/js/commentpress-jstor.js',
			array( 'jquery' ),
			COMMENTPRESS_JSTOR_VERSION
		);

		// localisation array
		$vars = array(
			'localisation' => array(),
			'data' => array(
				'spinner' => plugins_url( 'assets/images/loading.gif', COMMENTPRESS_JSTOR_FILE ),
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
	public function paragraph_wrapper( $text_sig ) {

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



} // class Commentpress_JSTOR ends



// instantiate the class
global $commentpress_jstor;
$commentpress_jstor = new Commentpress_JSTOR();


