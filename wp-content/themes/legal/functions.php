<?php
ini_set( 'display_errors', TRUE );
error_reporting( E_ALL ^ E_WARNING ^ E_NOTICE );
define( 'SURL', get_bloginfo( 'url' ) );
define( 'TURL', get_bloginfo( 'template_url' ) );
define( 'INC_PATH', dirname( __FILE__ ) . '/inc/' );
/* Register auto loader */
include_once INC_PATH . '/autoload.php';
new xsSetup();
new xsFormProcess();
add_action( 'get_header', '_init', 1 );
function _init() {
	$hybrid_auth = new Hybrid_Auth( xsConfig::getSocialConfig() );
	if ( isset( $_GET['_login'] ) ) {
		switch ( $_GET['_login'] ) {
			case 'tw':
				$src = 'Twitter';
				break;
			case 'fb':
				$src = 'Facebook';
				break;
			case 'gp':
				$src = "Google";
				break;
		}
	}
	////// Execute Login
	////
	if ( $src != '' ) {
		try {
			$social_login = $hybrid_auth->authenticate( $src );
			$user_profile = $social_login->getUserProfile();
			////////////////////////////////////////////
			$id    = $user_profile->identifier;
			$name  = $user_profile->displayName;
			$fname = $user_profile->firstName;
			$lname = $user_profile->lastName;
			$email = $user_profile->email;
			/////////////////////////////////////////////////
			$_SESSION['id']    = $id;
			$_SESSION['role']  = $_SESSION['xs_user_type'];
			$_SESSION['fname'] = $fname;
			$_SESSION['lname'] = $lname;
			$_SESSION['email'] = $email;
			$_SESSION['src']   = $src;
			global $wpdb;
			$sql = "Select user_id from {$wpdb->prefix}usermeta where meta_key='xs_{$src}' and meta_value='{$id}'";
			$uid = $wpdb->get_var( $sql );
			if ( $uid > 0 ) {
				if ( xsUTL::log_user( $uid ) ) {
					wp_redirect( bp_loggedin_user_domain() );
					die;
				}
			}
			// Check if user exist with that  account
			wp_redirect( SURL . '/complete-your-profile/' );
		} catch( Exception $e ) {
			if ( $e->getCode() == 6 || $e->getCode() == 7 ) {
				// log the user out (erase his session locally)
				$hybrid_auth->logout();
				// try to authenticate again
				$adapter = $hybrid_auth->authenticate( $src );
			}
		}
	}
}

//
add_filter( 'template_include', 'template_404' );
function template_404( $template ) {
	global $wp_query;
	switch ( get_query_var( 'name' ) ) {
		case 'social-callback':
			$template = dirname( __FILE__ ) . '/_social_Callback.php';
			break;
		case 'complete-your-profile':
			add_filter( 'body_class', function ( $classes ) {
				$classes[] = 'page-template-tpl-login ';

				return $classes;
			} );
			add_filter( 'wp_title', function () { return '-Complete Profile'; }, 100 );
			$template = dirname( __FILE__ ) . '/_complete-profile.php';
			break;
	}

	return $template;
}