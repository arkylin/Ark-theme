<!DOCTYPE html>
<html <?php language_attributes(); ?> >
<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="baidu-site-verification" content="code-He6yzuiCXu" />
	<title>
		<?php 
		wp_title('-',true,'right');
		bloginfo('name');
		?>
	</title>
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/assets/css/main.css">
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/assets/js/main.js">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/twbs/bootstrap@main/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/FortAwesome/Font-Awesome/css/all.min.css">
	<script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/gh/twbs/bootstrap@main/dist/js/bootstrap.min.js"></script>
</head>

<body>
	<div class="container bg-light">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<div class="container-fluid" style="display:inline-block;">
			<div class="row">
				<a class="col navbar-brand" href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name','display')); ?>">
					<h1><?php bloginfo('name'); ?></h1>
				</a>
				<button class="col-2 navbar-toggler" style="height:2em" type="button" data-bs-toggle="collapse" data-bs-target="#navbarExpandContent" aria-controls="navbarExpandContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarExpandContent">
					<div>
						<?php
							/*顶栏菜单*/
							wp_nav_menu(array());
						?>
					</div>
				</div>
			</div>
			<?php get_search_form(); ?>
		</div>
		</nav>
		