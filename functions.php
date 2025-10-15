
<?php
/**
 * Funzioni tema Enree Minimal
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Setup tema
 */
function enree_setup() {
  add_theme_support( 'title-tag' );
  add_theme_support( 'post-thumbnails' );
  add_theme_support( 'custom-logo', array(
    'height'      => 80,
    'width'       => 240,
    'flex-height' => true,
    'flex-width'  => true,
  ) );
  add_theme_support( 'custom-header', array(
    'width'  => 1920,
    'height' => 420,
    'flex-height' => true,
    'flex-width' => true,
  ) );

  register_nav_menus( array(
    'primary' => __( 'Menu principale', 'enree-minimal' ),
  ) );
}
add_action( 'after_setup_theme', 'enree_setup' );

/**
 * Sidebar e footer widget
 */
function enree_widgets_init() {
  register_sidebar( array(
    'name'          => __( 'Sidebar', 'enree-minimal' ),
    'id'            => 'sidebar-1',
    'description'   => __( 'Sidebar widget area below the menu.', 'enree-minimal' ),
    'before_widget' => '<section id="%1$s" class="widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',
  ) );

  for ( $i = 1; $i <= 4; $i++ ) {
    register_sidebar( array(
      'name'          => sprintf( __( 'Footer %d', 'enree-minimal' ), $i ),
      'id'            => 'footer-' . $i,
      'before_widget' => '<section id="%1$s" class="widget %2$s">',
      'after_widget'  => '</section>',
      'before_title'  => '<h3 class="widget-title">',
      'after_title'   => '</h3>',
    ) );
  }
}
add_action( 'widgets_init', 'enree_widgets_init' );

/**
 * Enqueue stili e script
 */
function enree_scripts() {
  wp_enqueue_style( 'enree-style', get_stylesheet_uri(), array(), '1.0.0' );
  wp_enqueue_script( 'enree-nav', get_template_directory_uri() . '/js/navigation.js', array(), '1.0.0', true );
  wp_enqueue_script( 'enree-search', get_template_directory_uri() . '/js/search-preview.js', array( 'jquery' ), '1.0.0', true );
  
  // Localize script for AJAX
  wp_localize_script( 'enree-search', 'enree_ajax', array(
    'ajax_url' => admin_url( 'admin-ajax.php' ),
    'nonce'    => wp_create_nonce( 'enree_search_nonce' ),
  ) );
}
add_action( 'wp_enqueue_scripts', 'enree_scripts' );

/**
 * Customizer: testo footer
 */
function enree_customize_register( $wp_customize ) {
  $wp_customize->add_section( 'enree_footer', array(
    'title'    => __( 'Footer', 'enree-minimal' ),
    'priority' => 160,
  ) );

  $wp_customize->add_setting( 'footer_text', array(
    'default'           => '© ' . date('Y') . ' ' . get_bloginfo('name') . ' — Powered by WordPress • Tema: Enree Minimal',
    'sanitize_callback' => 'wp_kses_post',
    'transport'         => 'postMessage',
  ) );

  $wp_customize->add_control( 'footer_text', array(
    'label'   => __( 'Testo del footer', 'enree-minimal' ),
    'type'    => 'textarea',
    'section' => 'enree_footer',
  ) );

  if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'footer_text', array(
      'selector'        => '.site-info',
      'render_callback' => function() { echo wp_kses_post( get_theme_mod( 'footer_text' ) ); },
    ) );
  }
}
add_action( 'customize_register', 'enree_customize_register' );

/**
 * AJAX search handler
 */
function enree_ajax_search() {
  // Verify nonce
  if ( ! wp_verify_nonce( $_POST['nonce'], 'enree_search_nonce' ) ) {
    wp_die( 'Security check failed' );
  }

  $search_query = sanitize_text_field( $_POST['query'] );
  
  if ( empty( $search_query ) ) {
    wp_send_json_error( 'Empty search query' );
  }

  // Search query
  $args = array(
    'post_type'      => array( 'post', 'page' ),
    'post_status'    => 'publish',
    's'              => $search_query,
    'posts_per_page' => 5,
    'orderby'        => 'relevance',
  );

  $search_results = new WP_Query( $args );
  
  $results = array();
  
  if ( $search_results->have_posts() ) {
    while ( $search_results->have_posts() ) {
      $search_results->the_post();
      
      $results[] = array(
        'id'        => get_the_ID(),
        'title'     => get_the_title(),
        'url'       => get_permalink(),
        'excerpt'   => wp_trim_words( get_the_excerpt(), 20, '...' ),
        'date'      => get_the_date(),
        'type'      => get_post_type(),
        'thumbnail' => has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ) : '',
      );
    }
    wp_reset_postdata();
  }

  wp_send_json_success( array(
    'results' => $results,
    'total'   => $search_results->found_posts,
    'query'   => $search_query,
  ) );
}
add_action( 'wp_ajax_enree_search', 'enree_ajax_search' );
add_action( 'wp_ajax_nopriv_enree_search', 'enree_ajax_search' );
