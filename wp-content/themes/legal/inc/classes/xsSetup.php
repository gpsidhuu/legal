<?php

class  xsSetup {
	/**
	 * xsSetup constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'setup_pages' ) );
		add_action( 'init', array( $this, 'disable_emojis' ) );
		/* LOGiN PAGE*/
		add_action( 'init', array( $this, 'redirect_login_page' ) );
		add_action( 'wp_login_failed', array( $this, 'login_failed' ) );
		add_filter( 'authenticate', array( $this, 'verify_username_password' ), 1, 3 );
		add_action( 'wp_logout', array( $this, 'logout_page' ) );
		///////////
		add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'add_styles' ) );
		//
		add_action( 'get_header', array( $this, 'set_header' ), 0 );
	}

	function add_scripts() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'global', TURL . '/js/global.js' );
	}

	function add_styles() {
		wp_enqueue_style( "bootstrap-grid", TURL . '/bootstrap/grid.css' );
		wp_enqueue_style( "main-style", TURL . '/style.css' );
		wp_enqueue_style( "roboto", 'https://fonts.googleapis.com/css?family=Roboto:400,700,900,700italic,400italic,100,100italic' );
	}

	function setup_pages() {
		$pages[] = array(
			'post_title'    => 'login',
			'page_template' => 'tpl-login.php'
		);
		$pages[] = array(
			'post_title'    => 'Test page',
			'page_template' => 'tpl--from.php'
		);
		////////
		foreach ( $pages as $page ) {
			if ( get_page_by_title( $page['post_title'] )->ID == NULL ) {
				$arr                = NULL;
				$arr                = $page;
				$arr['post_status'] = 'publish';
				$arr['post_type']   = 'page';
				wp_insert_post( $arr );
			}
		}
	}

	function set_header() {
		session_start();
	}

	function disable_emojis() {
		// all actions related to emojis
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		// filter to remove TinyMCE emojis
		add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
	}

	function redirect_login_page() {
		$login_page  = home_url( '/login/' );
		$page_viewed = basename( $_SERVER['REQUEST_URI'] );
		if ( $page_viewed == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET' ) {
			wp_redirect( $login_page );
			exit;
		}
	}

	function login_failed() {
		$login_page = home_url( '/login/' );
		wp_redirect( $login_page . '?login=failed' );
		exit;
	}

	function verify_username_password( $user, $username, $password ) {
		$login_page = home_url( '/login/' );
		if ( $username == "" || $password == "" ) {
			wp_redirect( $login_page . "?login=empty" );
			exit;
		}
	}

	function logout_page() {
		$login_page = home_url( '/login/' );
		wp_redirect( $login_page . "?login=false" );
		exit;
	}
}