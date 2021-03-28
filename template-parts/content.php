<?php get_header(); ?>

<article>
	<div class="post-content" id="post-content">
		<?php
            the_content();
		?>
	</div>
</article>

<?php comments_template(); ?>