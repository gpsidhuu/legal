<?php
spl_autoload_register( function ( $class ) {
	$filename = INC_PATH . '/classes/' . $class . '.php';
	if ( file_exists( $filename ) ) {
		include_once $filename;
	}
	$filename = INC_PATH . '/classes/Hybrid_' . $class . '.php';
	if ( file_exists( $filename ) ) {
		include_once $filename;
	}
} );