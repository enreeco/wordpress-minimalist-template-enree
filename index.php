
<?php get_header(); ?>

<section class="content">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      <div class="entry-meta">
        <time datetime="<?php echo esc_attr( get_the_date('c') ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
        — <?php the_author_posts_link(); ?>
        <?php if ( has_category() ) : ?> • <?php the_category(', '); ?><?php endif; ?>
      </div>
      <div class="entry-content">
        <?php if ( has_post_thumbnail() ) : ?>
          <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('large'); ?></a>
        <?php endif; ?>
        <?php the_excerpt(); ?>
      </div>
    </article>
  <?php endwhile; ?>

    <?php get_template_part( 'template-parts/pagination' ); ?>

  <?php else : ?>
    <p>Nessun contenuto trovato.</p>
  <?php endif; ?>
</section>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
