<?php

class xsUTL {
	private static $_errors = [ ];

	static function get_resized( $w, $h, $lazy = 1, $cp = 't', $echo = 1, $src = NULL, $id = NULL ) {
		global $post;
		$img = NULL;
		// find img from meta
		if ( $src == NULL ) {
			if ( $id == NULL ) {
				$id = get_the_ID();
			}
			$img = get_post_meta( $id, 'XTU', TRUE );
			if ( trim( $img ) != '' ) {
				$src = get_bloginfo( 'url' ) . '/wp-content/uploads/' . $img;
			} else {
				$src = xsUTL::get_source( $id );
			}
		}
		if ( $src == '' ):?>
			<img width="<?php echo $w; ?>" height="<?php echo $h; ?>" class="img-responsive" alt="<?php the_title(); ?>" src="http://placehold.it/<?php echo $w; ?>x<?php echo $h; ?>.png&text=No+Image"/>
		<?php else:
			$img = aq_resize( $src, $w, $h, TRUE, TRUE, TRUE );
			?>
			<img width="<?php echo $w; ?>" height="<?php echo $h; ?>" class="img-responsive"   src="<?php echo $img; ?>"><?php
		endif;
	}

	static function get_source( $id = NULL ) {
		if ( $id != NULL ) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'full' );

			return $image[0];
		}
		global $post;
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );

		return $image[0];
	}

	static function log_user( $user_id ) {
		$user = get_user_by( 'id', $user_id );
// Redirect URL //
		if ( ! is_wp_error( $user ) ) {
			wp_clear_auth_cookie();
			wp_set_current_user( $user->ID );
			wp_set_auth_cookie( $user->ID );

			return TRUE;
		}

		return FALSE;
	}

	static function addError( $err ) {
		self::$_errors[] = $err;
	}

	static function showErrors() {
		if ( count( self::$_errors ) ) {
			echo '<div class="xs-errors">' . implode( self::$_errors, '<br>' ) . '</div>';
		}
	}

	static function  isOnline( $redirect = NULL ) {
		if ( is_user_logged_in() ) {
			// yes
			if ( $redirect != '' ) {
				wp_redirect( $redirect );
			}
		}
	}

	static function add_image( $image_url ) {
		if ( empty( $image_url ) ) {
			return FALSE;
		}
		// Add Featured Image to Post
		$upload_dir = wp_upload_dir(); // Set upload folder
		$image_data = file_get_contents( $image_url ); // Get image data
		$filename   = basename( $image_url ); // Create image file name
// Check folder permission and define file location
		if ( wp_mkdir_p( $upload_dir['path'] ) ) {
			$file = $upload_dir['path'] . '/' . $filename;
		} else {
			$file = $upload_dir['basedir'] . '/' . $filename;
		}
// Create the image  file on the server
		file_put_contents( $file, $image_data );
// Check image file type
		$wp_filetype = wp_check_filetype( $filename, NULL );
// Set attachment data
		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title'     => sanitize_file_name( $filename ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
// Create the attachment
		$attach_id = wp_insert_attachment( $attachment, $file );
// Include image.php
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
// Define attachment metadata
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
// Assign metadata to attachment
		wp_update_attachment_metadata( $attach_id, $attach_data );

		return $attach_id;
	}
}
