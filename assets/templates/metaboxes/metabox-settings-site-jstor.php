<?php
/**
 * Site Settings screen "JSTOR Matchmaker Settings" metabox template.
 *
 * Handles markup for the Site Settings screen "JSTOR Matchmaker Settings" metabox.
 *
 * @package CommentPress_JSTOR
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>
<!-- <?php echo esc_html( $this->metabox_path ); ?>metabox-settings-site-jstor.php -->
<p><?php esc_html_e( 'Add the JSTOR Matchmaker settings here.', 'commentpress-jstor' ); ?></p>

<table class="form-table">

	<?php

	/**
	 * Fires at the top of the "JSTOR Matchmaker Settings" metabox.
	 *
	 * @since 0.2.0
	 */
	do_action( 'commentpress_jstor/metabox/settings/before' );

	?>

	<tr valign="top">
		<th scope="row">
			<label for="<?php echo esc_attr( $this->key_work ); ?>"><?php esc_html_e( 'Work code', 'commentpress-jstor' ); ?></label>
		</th>
		<td>
			<input type="text" id="<?php echo esc_attr( $this->key_work ); ?>" name="<?php echo esc_attr( $this->key_work ); ?>" value="<?php echo esc_attr( $work ); ?>" class="regular-text" />
		</td>
	</tr>

	<tr valign="top">
		<th scope="row">
			<label for="<?php echo esc_attr( $this->key_token ); ?>"><?php esc_html_e( 'Matchmaker API token', 'commentpress-jstor' ); ?></label>
		</th>
		<td>
			<input type="text" id="<?php echo esc_attr( $this->key_token ); ?>" name="<?php echo esc_attr( $this->key_token ); ?>" value="<?php echo esc_attr( $token ); ?>" class="regular-text" />
		</td>
	</tr>

	<tr valign="top">
		<th scope="row">
			<label for="<?php echo esc_attr( $this->key_fields ); ?>"><?php esc_html_e( 'JSTOR search fields', 'commentpress-jstor' ); ?></label>
		</th>
		<td>
			<input type="text" id="<?php echo esc_attr( $this->key_fields ); ?>" name="<?php echo esc_attr( $this->key_fields ); ?>" value="<?php echo esc_attr( $fields ); ?>" class="regular-text" />
		</td>
	</tr>

	<tr valign="top">
		<th scope="row">
			<label for="<?php echo esc_attr( $this->key_link ); ?>"><?php esc_html_e( 'JSTOR search link', 'commentpress-jstor' ); ?></label>
		</th>
		<td>
			<select id="<?php echo esc_attr( $this->key_link ); ?>" name="<?php echo esc_attr( $this->key_link ); ?>">
				<option value="y" <?php selected( $link, 'y' ); ?>><?php esc_html_e( 'Open links in same window', 'commentpress-jstor' ); ?></option>
				<option value="n" <?php selected( $link, 'n' ); ?>><?php esc_html_e( 'Open links in new window', 'commentpress-jstor' ); ?></option>
			</select>
		</td>
	</tr>

	<?php

	/**
	 * Fires at the bottom of the "JSTOR Matchmaker Settings" metabox.
	 *
	 * @since 0.2.0
	 */
	do_action( 'commentpress_jstor/metabox/settings/after' );

	?>

</table>
