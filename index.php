<?php get_header(); ?>

<?php if (have_posts()) { ?>
		<?php
			while (have_posts()) {
				the_post();
				get_template_part( 'template-parts/post-up' );
            }
		?>
<?php } ?>

<?php echo get_Ark_formatted_paginate_links(5); ?>
<?php get_footer(); ?>