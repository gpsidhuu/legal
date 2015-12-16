<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Legalamp<?php wp_title(); ?> </title>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header>
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<a id="logo" href="">
					<img src="<?php echo TURL; ?>/images/logo.png" alt="">
				</a>
				<ul class="logo-menu">
					<li>
						<form action="" class="header-search">
							<input placeholder="Find Lawyers" type="text">
						</form>
					</li>
					<li>
						<a href="">Browse</a>
					</li>
					<li>
						<a href="">How it work</a>
					</li>
				</ul>
				<ul class="user-menu">
					<li>
						<a href="<?php echo SURL; ?>/login/">Login</a>
					</li>
					<li>
						<a href="">Signup</a>
					</li>
					<li>
						<a href="" id="offer-service">Offer Legal Services</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</header>
<div class="content-wrapper">