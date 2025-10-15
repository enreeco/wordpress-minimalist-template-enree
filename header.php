
<?php
/**
 * Header
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="site-header">
  <div class="container topbar">
    <div class="site-branding">
      <?php if ( has_custom_logo() ) {
        the_custom_logo();
      } ?>
      <div>
        <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
        <?php if ( get_bloginfo( 'description' ) ) : ?>
          <p class="site-description"><?php bloginfo( 'description' ); ?></p>
        <?php endif; ?>
      </div>
    </div>
    <div class="header-actions">
      <?php get_search_form(); ?>
    </div>
  </div>

  <?php if ( get_header_image() ) : ?>
  <div class="header-banner">
    <img src="<?php header_image(); ?>" alt="<?php echo esc_attr( get_bloginfo('name') ); ?>" />
  </div>
  <?php endif; ?>

  <nav class="primary-nav">
    <div class="container">
      <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Menu', 'enree-minimal' ); ?></button>
      <?php
      wp_nav_menu( array(
        'theme_location' => 'primary',
        'menu_id'        => 'primary-menu',
        'container'      => false,
        'menu_class'     => 'menu',
        'depth'          => 3,
      ) );
      ?>
    </div>
  </nav>
</header>

<main id="content" class="container main-area">
