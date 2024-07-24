<?php
/**
 * Bootstrap
 *
 * @package Optioner
 */

if ( ! function_exists( 'optioner_bootstrap' ) ) {
	/**
	 * Bootstrap library.
	 *
	 * @since 1.0.0
	 */
	function optioner_bootstrap() {
		if ( is_admin() ) {
			do_action( 'optioner_admin_init' );
		}
	}
}
