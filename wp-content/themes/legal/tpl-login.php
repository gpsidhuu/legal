<?php
/*
  * Template Name: Login
  */
get_header(); ?>
	<div class="container">
		<div class="row ">
			<div class="col-sm-12">
				<h1 class="get-started"><span>Let's get started!</span> First, tell us what you're looking for</h1>
			</div>
		</div>
		<div class="row eq-height">
			<div class="col-sm-5"><?php
				$login = ( isset( $_GET['login'] ) ) ? $_GET['login'] : 0;
				if ( $login === "failed" ) {
					echo '<p class="red-alert"><strong>ERROR:</strong> Invalid username and/or password.</p>';
				} elseif ( $login === "empty" ) {
					echo '<p class="red-alert"><strong>ERROR:</strong> Username and/or Password is empty.</p>';
				} elseif ( $login === "false" ) {
					echo '<p class="red-alert"><strong>ERROR:</strong> You are logged out.</p>';
				} ?><?php echo wp_login_form(); ?>
				<div class="row">
					<div class="col-sm-12">
						<div class="or-sep"><span>OR Connect With</span></div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<a class="social-btn-fb icon" href="<?php echo SURL; ?>/?_login=fb"><i class="icon-facebook"> </i> Facebook</a>
						<a class="social-btn-tw icon" href="<?php echo SURL; ?>/?_login=tw"><i class="icon-twitter"> </i> Twitter</a>
						<a class="social-btn-gp icon" href="<?php echo SURL; ?>/?_login=gp"><i class="icon-google-plus"> </i> Google</a>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
get_footer();