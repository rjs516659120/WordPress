<?php
if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
	header('Allow: POST');
	header('HTTP/1.1 405 Method Not Allowed');
	header('Content-Type: text/plain');
	exit;
}
/** Sets up the WordPress Environment. */
require( dirname(__FILE__) . '/../../../wp-load.php' );
nocache_headers();
$comment_post_ID = isset($_POST['comment_post_ID']) ? (int) $_POST['comment_post_ID'] : 0;
$post = get_post($comment_post_ID);
if ( empty($post->comment_status) ) {
	do_action('comment_id_not_found', $comment_post_ID);
	err(__('Invalid comment status.','wpdx'));
}
// get_post_status() will get the parent status for attachments.
$status = get_post_status($post);
$status_obj = get_post_status_object($status);
if ( !comments_open($comment_post_ID) ) {
	do_action('comment_closed', $comment_post_ID);
	err(__('Sorry, comments are closed for this item.','wpdx'));
} elseif ( 'trash' == $status ) {
	do_action('comment_on_trash', $comment_post_ID);
	err(__('Invalid comment status.','wpdx'));
} elseif ( !$status_obj->public && !$status_obj->private ) {
	do_action('comment_on_draft', $comment_post_ID);
	err(__('Invalid comment status.','wpdx'));
} elseif ( post_password_required($comment_post_ID) ) {
	do_action('comment_on_password_protected', $comment_post_ID);
	err(__('Password Protected','wpdx'));
} else {
	do_action('pre_comment_on_post', $comment_post_ID);
}
$comment_author       = ( isset($_POST['author']) )  ? trim(strip_tags($_POST['author'])) : null;
$comment_author_email = ( isset($_POST['email']) )   ? trim($_POST['email']) : null;
$comment_author_url   = ( isset($_POST['url']) )     ? trim($_POST['url']) : null;
$comment_content      = ( isset($_POST['comment']) ) ? trim($_POST['comment']) : null;
$edit_id              = ( isset($_POST['edit_id']) ) ? $_POST['edit_id'] : null;
// If the user is logged in
$user = wp_get_current_user();
if ( $user->exists() ) {
	if ( empty( $user->display_name ) )
		$user->display_name=$user->user_login;
	$comment_author       = wp_slash($user->display_name);
	$comment_author_email = wp_slash($user->user_email);
	$comment_author_url   = wp_slash($user->user_url);
	if ( current_user_can('unfiltered_html') ) {
		if ( wp_create_nonce('unfiltered-html-comment_' . $comment_post_ID) != $_POST['_wp_unfiltered_html_comment'] ) {
			kses_remove_filters(); // start with a clean slate
			kses_init_filters(); // set up the filters
		}
	}
} else {
	if ( get_option('comment_registration') || 'private' == $status )
		err(__('Sorry, you must be logged in to post a comment.','wpdx'));
}
$comment_type = '';
if ( get_option('require_name_email') && !$user->exists() ) {
	if ( 6 > strlen($comment_author_email) || '' == $comment_author )
		err( __('Error: please fill the required fields (name, email).','wpdx') );
	elseif ( !is_email($comment_author_email))
		err( __('Error: please enter a valid email address.','wpdx') );
}
if ( '' == $comment_content )
	err( __('Error: please type a comment.','wpdx') );

$pattern = '/[一-龥]/u';
$text = '/['.trim(cmp_get_option( 'sensitive_character' )).']/u';
if (cmp_get_option( 'comment_chinese' ) && !preg_match($pattern,$comment_content) )
	err( __('Your comment must contain Chinese.','wpdx' ) );
if (cmp_get_option( 'comment_sensitive' ) && preg_match($text,$comment_content) )
	err( __('Comments are not allowed sensitive character.','wpdx') );

function err($ErrMsg) {
	header('HTTP/1.1 405 Method Not Allowed');
	echo $ErrMsg;
	exit;
}

$dupe = "SELECT comment_ID FROM $wpdb->comments WHERE comment_post_ID = '$comment_post_ID' AND ( comment_author = '$comment_author' ";
	if ( $comment_author_email ) $dupe .= "OR comment_author_email = '$comment_author_email' ";
	$dupe .= ") AND comment_content = '$comment_content' LIMIT 1";
if ( $wpdb->get_var($dupe) ) {
	err(__('Duplicate comment detected; it looks as though you&#8217;ve already said that!','wpdx'));
}

if ( $lasttime = $wpdb->get_var( $wpdb->prepare("SELECT comment_date_gmt FROM $wpdb->comments WHERE comment_author = %s ORDER BY comment_date DESC LIMIT 1", $comment_author) ) ) {
	$time_lastcomment = mysql2date('U', $lasttime, false);
	$time_newcomment  = mysql2date('U', current_time('mysql', 1), false);
	$flood_die = apply_filters('comment_flood_filter', false, $time_lastcomment, $time_newcomment);
	if ( $flood_die ) {
		err(__('You are posting comments too quickly.  Slow down.','wpdx'));
	}
}
$comment_parent = isset($_POST['comment_parent']) ? absint($_POST['comment_parent']) : 0;
$commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID');

if ( $edit_id ){
	$comment_id = $commentdata['comment_ID'] = $edit_id;
	wp_update_comment( $commentdata );
} else {
	$comment_id = wp_new_comment( $commentdata );
}
$comment = get_comment($comment_id);
do_action('set_comment_cookies', $comment, $user);

$comment_depth = 1;
$tmp_c = $comment;
while($tmp_c->comment_parent != 0){
	$comment_depth++;
	$tmp_c = get_comment($tmp_c->comment_parent);
}

?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
	<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment,$size='40',$default='<path_to_url>' ); ?>
			<?php printf( __( '<a class="fn">%s</a>:','wpdx'), get_comment_author_link() ); ?>
		</div>
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em><?php _e( 'Your comment is awaiting moderation.' ,'wpdx'); ?></em>
		<?php endif; ?>
		<div class="comment-body"><?php comment_text(); ?></div>
		<div class="comment-meta commentmetadata"><?php echo get_comment_date('Y-n-j').'&nbsp;'.  get_comment_time(); ?></div>
	</div>