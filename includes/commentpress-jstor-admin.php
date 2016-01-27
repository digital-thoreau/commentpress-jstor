<?php

/**
 * CommentPress JSTOR Admin Class
 *
 * A class that encapsulates admin functionality.
 */
class Commentpress_JSTOR_Admin {



	/**
	 * Plugin object.
	 *
	 * @since 0.1
	 * @access public
	 * @var object $plugin The plugin object
	 */
	public $plugin;



	/**
	 * Settings data.
	 *
	 * @since 0.1
	 * @access public
	 * @var array $settings The plugin settings data
	 */
	public $settings = array();



	/**
	 * Constructor.
	 *
	 * @since 0.1
	 *
	 * @param object $parent The global plugin object
	 * @return object $this
	 */
	public function __construct( $parent ) {

		// store reference
		$this->plugin = $parent;

		// load settings array
		$this->settings = get_option( 'commentpress_jstor_settings', $this->settings );

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

		// add settings option only if it does not exist
		if ( 'fgffgs' == get_option( 'commentpress_jstor_settings', 'fgffgs' ) ) {

			// store default settings
			add_option( 'commentpress_jstor_settings', $this->settings_get_default() );

		}

	}



	/**
	 * Perform plugin deactivation tasks.
	 *
	 * @since 0.1
	 *
	 * @return void
	 */
	public function deactivate() {

		// delete option in uninstall.php

	}



	/**
	 * Register WordPress hooks.
	 *
	 * @since 0.1
	 *
	 * @return void
	 */
	public function register_hooks() {

		// add form elements to end of CommentPress settings page
		add_action( 'commentpress_admin_page_options', array( $this, 'admin_form' ) );

		// grab data when CommentPress settings have been saved
		add_action( 'commentpress_admin_page_options_updated', array( $this, 'options_update' ) );

	}



	/**
	 * Show plugin settings at the end of the CommentPress settings page.
	 *
	 * @param str $admin_form The existing CommentPress settings form
	 * @return str $admin_form The modified CommentPress settings form
	 */
	public function admin_form( $admin_form ) {

		// check user permissions
		if ( ! current_user_can( 'manage_options' ) ) { return $admin_form; }

		// init work
		$work = '';
		$existing_work = $this->setting_get( 'work' );
		if ( ! empty( $existing_work ) ) {
			$work = $existing_work;
		}

		// init token
		$token = '';
		$existing_token = $this->setting_get( 'token' );
		if ( ! empty( $existing_token ) ) {
			$token = $existing_token;
		}

		// init fields
		$fields = '';
		$existing_fields = $this->setting_get( 'fields' );
		if ( ! empty( $existing_fields ) ) {
			$fields = $existing_fields;
		}

		// append our options
		$admin_form .= '

		<hr />

		<h3>' . __( 'JSTOR Matchmaker Options', 'commentpress-jstor' ) . '</h3>

		<p>' . __( 'Add your JSTOR settings here.', 'commentpress-jstor' ) . '</p>

		<table class="form-table">

			<tr valign="top">
				<th scope="row"><label for="commentpress_jstor_work">' . __( 'Work code', 'commentpress-jstor' ) . '</label></th>
				<td><input type="text" id="commentpress_jstor_work" name="commentpress_jstor_work" value="' . $work . '" /></td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="commentpress_jstor_token">' . __( 'Matchmaker API token', 'commentpress-jstor' ) . '</label></th>
				<td><input type="text" id="commentpress_jstor_token" name="commentpress_jstor_token" value="' . $token . '" /></td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="commentpress_jstor_fields">' . __( 'JSTOR search fields', 'commentpress-jstor' ) . '</label></th>
				<td><input type="text" id="commentpress_jstor_fields" name="commentpress_jstor_fields" value="' . $fields . '" /></td>
			</tr>

		</table>



		';

		// --<
		return $admin_form;

	}



	/**
	 * Capture and save plugin settings.
	 *
	 * Since this is called by an action once CommentPress has parsed its data,
	 * we can be sure that the data is from the CommentPress form.
	 *
	 * @since 0.1
	 *
	 * @return void
	 */
	public function options_update() {

	 	// let's be sure the form was submitted
		if( ! isset( $_POST['commentpress_submit'] ) ) return;

		// default to empty value
		$work = '';

		// sanitise and overwrite if we entered a JSTOR work code
		if ( isset( $_POST['commentpress_jstor_work'] ) ) {
			$work = esc_sql( $_POST['commentpress_jstor_work'] );
		}

		// save
		$this->setting_set( 'work', $work );

		// default to empty value
		$token = '';

		// sanitise and overwrite if we entered a JSTOR token
		if ( isset( $_POST['commentpress_jstor_token'] ) ) {
			$token = esc_sql( $_POST['commentpress_jstor_token'] );
		}

		// save
		$this->setting_set( 'token', $token );

		// default to empty value
		$fields = '';

		// sanitise and overwrite if we entered some JSTOR fields
		if ( isset( $_POST['commentpress_jstor_fields'] ) ) {
			$fields = esc_sql( $_POST['commentpress_jstor_fields'] );
		}

		// save
		$this->setting_set( 'fields', $fields );

		// save all
		$this->settings_save();

	}



	/**
	 * Restrict appearance of this plugin.
	 *
	 * @since 0.1
	 *
	 * @return bool True if allowed to view, false otherwise
	 */
	public function can_view() {

		// restrict to admins for now
		if ( is_super_admin() ) return true;

		// --<
		return false;

	}



	/**
	 * Restrict functionality of this plugin until all options are filled out.
	 *
	 * @since 0.1
	 *
	 * @return bool $enabled True if enabled, false otherwise
	 */
	public function is_active() {

		// deny by default
		$enabled = false;

		// get settings
		$work = $this->setting_get( 'work' );
		$token = $this->setting_get( 'token' );
		$fields = $this->setting_get( 'fields' );

		// check essential settings have values
		if ( ! empty( $work ) AND ! empty( $token ) AND ! empty( $fields ) ) {
			$enabled = true;
		}

		// --<
		return $enabled;

	}



	/**
	 * Get default plugin settings.
	 *
	 * @since 0.1
	 *
	 * @return array $settings The array of settings, keyed by setting name
	 */
	public function settings_get_default() {

		// init return
		$settings = array();

		// set default work
		$settings['work'] = '';

		// set default token
		$settings['token'] = '417901c2555ba65649f356626aada7f273390cf3';

		// set default fields
		$settings['fields'] = 'docid,work,work_text,chunk_ids,title,journal,' .
							  'authors,pages,pubyear,keyterms,similarity,' .
							  'match_size,snippet,source';

		// allow filtering
		return apply_filters( 'commentpress_jstor_default_settings', $settings );

	}



	/**
	 * Save the plugin's settings array.
	 *
	 * @since 0.1
	 *
	 * @return bool $result True if setting value has changed, false if not or if update failed
	 */
	public function settings_save() {

		// update WordPress option and return result
		return update_option( 'commentpress_jstor_settings', $this->settings );

	}



	/**
	 * Return a value for a specified setting.
	 *
	 * @since 0.1
	 *
	 * @param str $setting_name The setting name (array key)
	 * @param mixed $default The default setting value (array value)
	 * @return mixed $setting The value of the setting
	 */
	public function setting_get( $setting_name = '', $default = false ) {

		// sanity check
		if ( $setting_name == '' ) {
			wp_die( __( 'You must supply a setting to setting_get()', 'commentpress-jstor' ) );
		}

		// get setting
		return ( array_key_exists( $setting_name, $this->settings ) ) ? $this->settings[$setting_name] : $default;

	}



	/**
	 * Set a value for a specified setting.
	 *
	 * @since 0.1
	 *
	 * @param str $setting_name The setting name (array key)
	 * @param mixed $value The setting value (array value)
	 * @return void
	 */
	public function setting_set( $setting_name = '', $value = '' ) {

		// sanity check
		if ( $setting_name == '' ) {
			wp_die( __( 'You must supply a setting to setting_set()', 'commentpress-jstor' ) );
		}

		// set setting
		$this->settings[$setting_name] = $value;

	}



} // class Commentpress_JSTOR_Admin ends



