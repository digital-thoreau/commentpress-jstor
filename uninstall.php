<?php
/**
 * CommentPress JSTOR Uninstaller.
 *
 * Removes traces of JSTOR Matchmaker tool.
 *
 * @package Commentpress_JSTOR
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Exit if uninstall not called from WordPress.
defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

// Delete settings option.
delete_option( 'commentpress_jstor_settings' );
