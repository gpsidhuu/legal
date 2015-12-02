<?php

class xsUser extends WP_User {
	public function __construct( $id = NULL ) {
		if ( $id != NULL ) {
			parent::__construct( $id );
		} else {
			parent::__construct( get_current_user_id() );
		}
	}

	function is_Client() {
		if ( in_array( 'client', $this->roles ) ) {
			return TRUE;
		}

		return FALSE;
	}

	function is_business() {
		if ( current_user_can( 'manage_options' ) ) {
			return TRUE;
		}
		if ( in_array( 'author', $this->roles ) ) {
			return TRUE;
		}

		return FALSE;
	}

	function set_credits( $credits, $user_id ) {
		update_user_meta( get_current_user_id(), 'xs_credits', $credits );
	}

	function get_credits( $user_id = NULL ) {
		$id = get_current_user_id();
		if ( $user_id > 0 ) {
			$id = $user_id;
		}
		$credits = get_user_meta( $id, 'xs_credits', TRUE );
		if ( $credits > 0 ) {
			return $credits;
		}

		return 0;
	}

	function can_add_coupon( $required_credits ) {
		if ( $this->get_credits() < $required_credits ) {
			return FALSE;
		}

		return TRUE;
	}
}