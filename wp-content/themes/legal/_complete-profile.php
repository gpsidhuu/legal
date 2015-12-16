<?php
/*
  * Template Name: Login
  */
get_header(); ?>
	<div class="container">
		<div class="login-box">
			<h1 class="outlined-title"><span>Complete Profile</span></h1>
			<form method="post" class="xsAjax" id="loginform" name="loginform">
				<input type="hidden" name="complete_profile" id="">
				<p class="login-username  ">
					<label for="user_login">Username</label>
					<input type="text" size="20" value="" class="input" id="user_login" name="log" placeholder="Username">
				</p>
				<p class="login-password  ">
					<label for="user_pass">Password</label>
					<input type="password" size="20" value="" class="input" id="user_pass" name="pwd" placeholder="Password">
				</p>
				<p class="login-password  ">
					<label for="user_pass">Confirm Password</label>
					<input type="password" size="20" value="" class="input" id="user_pass" name="pwd" placeholder="Confirm Password">
				</p>
				<p class="login-submit">
					<input type="submit" value="Log In" class="button-primary" id="wp-submit" name="wp-submit">
					<input type="hidden" value="http://localhost/legal/login/" name="redirect_to">
				</p>
			</form>
		</div>
	</div>
<?php
get_footer();