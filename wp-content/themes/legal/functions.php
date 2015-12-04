<?php
define( 'SURL', get_bloginfo( 'url' ) );
define( 'TURL', get_bloginfo( 'template_url' ) );
define( 'INC_PATH', dirname( __FILE__ ) . '/inc/' );


/* Register auto loader */
include_once INC_PATH . '/autoload.php';
new xsSetup();
new xsFormProcess();