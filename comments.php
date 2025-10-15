
<?php
if ( post_password_required() ) { return; }
?>

<div id="comments" class="comments-area">
  <?php if ( have_comments() ) : ?>
    <h2 class="comments-title">
      <?php
      $count = get_comments_number();
      printf( _n( '%s commento', '%s commenti', $count, 'enree-minimal' ), number_format_i18n( $count ) );
      ?>
    </h2>

    <ol class="comment-list">
      <?php wp_list_comments( array( 'style' => 'ol', 'short_ping' => true ) ); ?>
    </ol>

    <?php the_comments_navigation(); ?>
  <?php endif; ?>

  <?php if ( comments_open() ) comment_form(); ?>
</div>
