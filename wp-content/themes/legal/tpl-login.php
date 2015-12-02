<?php
/*
  * Template Name: Login
  */
get_header(); ?>
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h1 class="get-started"><span>Let's get started!</span> First, tell us what you're looking for</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-5"><?php
				$login = ( isset( $_GET['login'] ) ) ? $_GET['login'] : 0;
				if ( $login === "failed" ) {
					echo '<p class="red-alert"><strong>ERROR:</strong> Invalid username and/or password.</p>';
				} elseif ( $login === "empty" ) {
					echo '<p class="red-alert"><strong>ERROR:</strong> Username and/or Password is empty.</p>';
				} elseif ( $login === "false" ) {
					echo '<p class="red-alert"><strong>ERROR:</strong> You are logged out.</p>';
				} ?><?php echo wp_login_form(); ?></div>
			<div class="col-sm-2 col-xs-hidden or-sep">OR</div>
			<div class="col-sm-5">dsaasdasds</div>
		</div>
	</div>
<?php
get_footer();