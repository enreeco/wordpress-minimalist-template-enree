
<?php get_header(); ?>

<section class="content">
  <header>
    <h1 class="entry-title"><?php the_archive_title(); ?></h1>
    <?php the_archive_description('<div class="archive-description">','</div>'); ?>
  </header>

  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      <div class="entry-meta">
        <time datetime="<?php echo esc_attr( get_the_date('c') ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
        — <?php the_author_posts_link(); ?>
      </div>
      <div class="entry-content">
        <?php the_excerpt(); ?>
      </div>
    </article>
  <?php endwhile; get_template_part( 'template-parts/pagination' ); else : ?>
    <p>Nessun contenuto trovato.</p>
  <?php endif; ?>
</section>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
