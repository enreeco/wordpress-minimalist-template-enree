
<?php get_header(); ?>

<section class="content">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <h1 class="entry-title"><?php the_title(); ?></h1>
      <div class="entry-meta">
        <time datetime="<?php echo esc_attr( get_the_date('c') ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
        — <?php the_author_posts_link(); ?>
        <?php if ( has_category() ) : ?> • <?php the_category(', '); ?><?php endif; ?>
      </div>
      <div class="entry-content">
        <?php the_content(); ?>
      </div>
      <div class="entry-tags">
        <?php the_tags( '', ' • ' ); ?>
      </div>
      <?php comments_template(); ?>
    </article>
  <?php endwhile; endif; ?>
</section>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
