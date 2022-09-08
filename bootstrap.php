<?php

function optioner_bootstrap() {
	if ( is_admin() ) {
		do_action( 'optioner_admin_init' );
	}
}
