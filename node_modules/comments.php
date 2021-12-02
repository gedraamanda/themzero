<div class="comments">
	<?php if (post_password_required()) : ?>
	<p><?php _e( 'Post is password protected. Enter the password to view any comments.', 'html5blank' ); ?></p>
</div>

	<?php return; endif; ?>


<?php comment_form(); ?>

<?php if (have_comments()) : ?>
	<div id="comments-list">
		<h3><?php comments_number(); ?></h3>
		<?php setticomment(); // Custom callback in functions.php ?>
	</div>
<?php elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) : ?>

	<p><?php _e( 'Comments are closed here.', 'html5blank' ); ?></p>

<?php endif; ?>



</div>
