<?php
	if ( post_password_required() ) {
		return;
	}
?>
<?php if ( have_comments() ){ ?>
	<div id="comments" class="comments-area card shadow-sm comment-avatar-vertical-center">
		<div class="card-body">
			<h2 class="comments-title">
				<i class="fa fa-comments">评论</i>
			</h2>
			<ol class="comment-list">
				<?php
					wp_list_comments(
						array(
							'avatar_size' => 44,
							'style'       => 'ol',
							'short_ping'  => true,
						)
					);
				?>
			</ol>
		</div>
	</div>
<?php } ?>