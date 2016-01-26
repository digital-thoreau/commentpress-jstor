<?php

/**
 * CommentPress JSTOR Uninstaller.
 *
 * Removes traces of JSTOR Matchmaker tool.
 *
 * @package WordPress
 * @subpackage Commentpress_JSTOR
 */

// kick out if uninstall not called from WordPress
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit(); }

// delete settings option
delete_option( 'commentpress_jstor_settings' );
