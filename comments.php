<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * But I modified this file.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die ('<h1>Fuck you</h1>');

if ( post_password_required() ) {
  return;
}
?>

<div id="comments" class="comments-area">


    <h2 class="comments-title">
      <a href="#respond" title="Add a comment."><?php comments_number('No Comment yet.', '1 comment so far.', (count($comments)-count($trackbacks)) . ' comments so far.' ); ?></a>
    </h2>

  <div id="comment-box">
    <ol class="comment-list">
    <?php if ( have_comments() ) :
        wp_list_comments( array(
          'style'     => 'ol',
          'type'      => 'all',
          'callback'  => themecomment,
          'max_depth' => 500,
        ) );
      endif; // have_comments() ?>
    </ol><!-- .comment-list -->

  <?php if ( have_comments() ) : ?>
    <nav class="navigation comment-navigation pagination" data-post-id="<?php echo $post->ID?>">
      <?php paginate_comments_links('prev_next=0');?>
    </nav>
  <?php endif; // have_comments() ?>

  </div>

  <?php
    // If comments are closed and there are comments, let's leave a little note, shall we?
    if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
  ?>
    <p class="no-comments">Comments are closed.</p>
  <?php endif; ?>

  <?php
  $fields =  array(
    'author' =>
      '<p class="comment-form-author"><label for="author">' . __('Name') . ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' .
      '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
      '" size="30"' . $aria_req . ' /></p>',
    'email' =>
      '<p class="comment-form-email"><label for="email">' . __('Email') . ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' .
      '<input id="email" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) .
      '" size="30"' . $aria_req . ' /></p>',
    'url' =>
      '<p class="comment-form-url"><label for="url">' . __('Website') . '</label>' .
      '<input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) .
      '" size="30" /></p>',
  );
  comment_form( array(
    'id_form'           => 'commentform',
    'id_submit'         => 'submit',
    'class_submit'      => 'submit',
    'name_submit'       => 'submit',
    'title_reply'       => __('Leave a Reply'),
    'title_reply_to'    => __('Leave a Reply to %s'),
    'label_submit'      => __('Post Comment'),
    'format'            => 'html5',

    'comment_notes_after' => null,
    'fields' => $fields,
    )
  );?>

<script type="text/javascript"> //Ctrl+Enter
//<![CDATA[
jQuery(document).keypress(function(e){
  if(e.ctrlKey && e.which == 13 || e.which == 10) {
    jQuery(".submit").click();
    document.body.focus();
  } else if (e.shiftKey && e.which==13 || e.which == 10) {
    jQuery(".submit").click();
  }
});
// ]]>
</script>
</div><!-- .comments-area -->
