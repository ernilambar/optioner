<?php

if ( ! function_exists( 'optioner_bootstrap' ) ) {
	function optioner_bootstrap() {
		if ( is_admin() ) {
			do_action( 'optioner_admin_init' );
		}
	}
}
