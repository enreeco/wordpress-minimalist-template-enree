
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
      
      <?php
      // Post navigation
      $prev_post = get_previous_post();
      $next_post = get_next_post();
      ?>
      
      <?php if ( $prev_post || $next_post ) : ?>
        <nav class="post-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Posts', 'enree-minimal' ); ?>">
          <div class="nav-links">
            <?php if ( $prev_post ) : ?>
              <div class="nav-previous">
                <a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" rel="prev">
                  <span class="nav-subtitle"><?php esc_html_e( 'Previous:', 'enree-minimal' ); ?></span>
                  <span class="nav-title"><?php echo esc_html( get_the_title( $prev_post->ID ) ); ?></span>
                </a>
              </div>
            <?php endif; ?>
            
            <?php if ( $next_post ) : ?>
              <div class="nav-next">
                <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" rel="next">
                  <span class="nav-subtitle"><?php esc_html_e( 'Next:', 'enree-minimal' ); ?></span>
                  <span class="nav-title"><?php echo esc_html( get_the_title( $next_post->ID ) ); ?></span>
                </a>
              </div>
            <?php endif; ?>
          </div>
        </nav>
      <?php endif; ?>
      
      <?php comments_template(); ?>
    </article>
  <?php endwhile; endif; ?>
</section>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
