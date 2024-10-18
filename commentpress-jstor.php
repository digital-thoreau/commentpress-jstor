<?php
/**
 * CommentPress JSTOR
 *
 * Plugin Name:       CommentPress JSTOR
 * Description:       Allows CommentPress to access JSTOR Matchmaker tool from The Reader's Thoreau site.
 * Plugin URI:        https://github.com/digital-thoreau/commentpress-jstor
 * GitHub Plugin URI: https://github.com/digital-thoreau/commentpress-jstor
 * Version:           0.2.0a
 * Author:            Christian Wach
 * Author URI:        https://haystack.co.uk
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Requires at least: 5.7
 * Requires PHP:      7.4
 * Text Domain:       commentpress-jstor
 * Domain Path:       /languages
 *
 * @package Commentpress_JSTOR
 * @link    https://github.com/digital-thoreau/commentpress-jstor
 * @license GPL v2 or later
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

// Set our version here.
define( 'COMMENTPRESS_JSTOR_VERSION', '0.2.0a' );

// Store reference to this file.
if ( ! defined( 'COMMENTPRESS_JSTOR_FILE' ) ) {
	define( 'COMMENTPRESS_JSTOR_FILE', __FILE__ );
}

// Store URL to this plugin's directory.
if ( ! defined( 'COMMENTPRESS_JSTOR_URL' ) ) {
	define( 'COMMENTPRESS_JSTOR_URL', plugin_dir_url( COMMENTPRESS_JSTOR_FILE ) );
}

// Store PATH to this plugin's directory.
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
	 * @var Commentpress_JSTOR_Admin
	 */
	public $admin;

	/**
	 * Display object.
	 *
	 * @since 0.1
	 * @access public
	 * @var Commentpress_JSTOR_Display
	 */
	public $display;

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 */
	public function __construct() {

		// Bootstrap plugin.
		$this->include_files();
		$this->setup_globals();
		$this->register_hooks();

		/**
		 * Fires when CommentPress JSTOR has loaded.
		 *
		 * @since 0.2.0
		 */
		do_action( 'commentpress_jstor/loaded' );

	}

	/**
	 * Includes files.
	 *
	 * @since 0.1
	 */
	public function include_files() {

		// Include class files.
		include_once COMMENTPRESS_JSTOR_PATH . 'includes/class-jstor-admin.php';
		include_once COMMENTPRESS_JSTOR_PATH . 'includes/class-jstor-display.php';

	}

	/**
	 * Sets up objects.
	 *
	 * @since 0.1
	 */
	public function setup_globals() {

		// Init objects.
		$this->admin   = new Commentpress_JSTOR_Admin( $this );
		$this->display = new Commentpress_JSTOR_Display( $this );

	}

	/**
	 * Registers WordPress hooks.
	 *
	 * @since 0.1
	 */
	public function register_hooks() {

		// Use translation.
		add_action( 'init', [ $this, 'translation' ] );

	}

	/**
	 * Loads translation if present.
	 *
	 * @since 0.1
	 */
	public function translation() {

		// Load translations if present.
		// phpcs:ignore WordPress.WP.DeprecatedParameters.Load_plugin_textdomainParam2Found
		load_plugin_textdomain(
			// Unique name.
			'commentpress-jstor',
			// Deprecated argument.
			'',
			// Relative path to translation files.
			dirname( plugin_basename( COMMENTPRESS_JSTOR_FILE ) ) . '/languages/'
		);

	}

}

/**
 * Bootstraps plugin if not yet loaded and returns reference.
 *
 * @since 0.2.0
 *
 * @return Commentpress_JSTOR $plugin The plugin reference.
 */
function commentpress_jstor() {

	// Maybe bootstrap plugin.
	static $plugin;
	if ( ! isset( $plugin ) ) {
		$plugin = new Commentpress_JSTOR();
	}

	// Return reference.
	return $plugin;

}

// Bootstrap immediately.
commentpress_jstor();

/*
 * Uninstall uses the 'uninstall.php' method.
 *
 * @see https://developer.wordpress.org/reference/functions/register_uninstall_hook/
 */
