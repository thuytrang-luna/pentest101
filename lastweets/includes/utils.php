<?php

namespace Lastweets\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Debug stuff in debug.log file
 */
function debug( ...$logs ) {
	if ( true === WP_DEBUG ) {
		foreach ( $logs as $log ) {
			error_log( '--------------------------------- /!\ ' . __NAMESPACE__ . ' /!\ --------------------------------- ' );
			if ( is_array( $log ) || is_object( $log ) ) {
				error_log( print_r( $log, true ) );
			} else {
				error_log( $log );
			}
			error_log( '--------------------------------- /!\ ' . __NAMESPACE__ . ' /!\ --------------------------------- ' );
		}
	}
}

/**
 * Does a user have a specific role ?
 *
 * @return boolean
 */
function user_has_role( $role = 'subscriber' ) {
	if ( is_user_logged_in() ) {
		$user  = wp_get_current_user();
		$roles = (array) $user->roles;
		return in_array( $role, $roles, true );
	}

	return false;
}
