<?php // Do not delete these lines
if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
		?>
		<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'wpdx' ); ?></p>
		<?php
		return;
	}
}
/* This variable is for alternating comment background */
$oddcomment = '';
?>
<!-- You can start editing here. -->
<?php if ($comments) : ?>
	<div id="comments">
		<h3><?php comments_number(__('No comments','wpdx'), __('One comment','wpdx'), '% '.__('comments','wpdx') );?></h3>
	</div>
	<div class="comments-loading"><i class="fa fa-spinner fa-spin fa-lg"></i> <?php _e('Comments Loading ...','wpdx') ?></div>
	<ol class="commentlist">
		<?php wp_list_comments('type=comment&callback=cm_comment&end-callback=cm_end_comment&max_depth=23'); ?>
	</ol>
		<div class="page-nav comment-nav"><?php paginate_comments_links(); ?></div>
		<div class="clear"></div>
<?php else : // this is displayed if there are no comments so far ?>
	<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->
		<div id="comments">
			<h3><?php _e('Leave a Reply','wpdx') ?></h3>
		</div>
	<?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments"><?php _e( 'Comments are closed.', 'wpdx' ); ?></p>
	<?php endif; ?>
<?php endif; ?>
<?php if ( comments_open()) { comment_form(); } ?>