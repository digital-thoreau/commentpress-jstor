<?php /*
--------------------------------------------------------------------------------
Plugin Name: CommentPress JSTOR
Plugin URI: http://www.futureofthebook.org/commentpress/
Description: Allows CommentPress to access JSTOR Matchmaker tool from The Reader's Thoreau site.
Author: Institute for the Future of the Book
Version: 0.2.0a
Author URI: http://www.futureofthebook.org
Text Domain: commentpress-jstor
Domain Path: /languages
--------------------------------------------------------------------------------
*/



// set our version here
define( 'COMMENTPRESS_JSTOR_VERSION', '0.2.0a' );

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
 * CommentPress JSTOR Class.
 *
 * A class that encapsulates plugin functionality.
 *
 * @since 0.1
 */
class Commentpress_JSTOR {

	/**
	 * Admin object.
	 *
	 * @since 0.1
	 * @access public
	 * @var object $admin The admin object
	 */
	public $admin;

	/**
	 * Display object.
	 *
	 * @since 0.1
	 * @access public
	 * @var object $admin The display object
	 */
	public $display;



	/**
	 * Constructor.
	 *
	 * @since 0.1
	 */
	public function __construct() {

		// include files
		$this->include_files();

		// setup globals
		$this->setup_globals();

		// register hooks
		$this->register_hooks();

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
	 * Include files.
	 *
	 * @since 0.1
	 *
	 * @return void
	 */
	public function include_files() {

		// include admin class
		include_once COMMENTPRESS_JSTOR_PATH . 'includes/commentpress-jstor-admin.php';

		// include display class
		include_once COMMENTPRESS_JSTOR_PATH . 'includes/commentpress-jstor-display.php';

	}



	/**
	 * Set up objects.
	 *
	 * @since 0.1
	 *
	 * @return void
	 */
	public function setup_globals() {

		// init admin object
		$this->admin = new Commentpress_JSTOR_Admin( $this );

		// init display object
		$this->display = new Commentpress_JSTOR_Display( $this );

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

		// hooks that always need to be present
		$this->admin->register_hooks();
		$this->display->register_hooks();

	}



	/**
	 * Load translation if present.
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



} // class Commentpress_JSTOR ends



// instantiate the class
global $commentpress_jstor;
$commentpress_jstor = new Commentpress_JSTOR();


