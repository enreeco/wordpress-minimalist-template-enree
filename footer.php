
<?php
/**
 * Footer
 */
?>
</main>

<footer class="site-footer">
  <div class="container">
    <div class="footer-widgets">
      <?php for ( $i = 1; $i <= 4; $i++ ) : ?>
        <div class="footer-col">
          <?php if ( is_active_sidebar( 'footer-' . $i ) ) : dynamic_sidebar( 'footer-' . $i ); endif; ?>
        </div>
      <?php endfor; ?>
    </div>
    <?php $ft = get_theme_mod( "footer_text" ); ?>
    <div class="site-info"><?php echo $ft ? wp_kses_post( $ft ) : sprintf( "&copy; %s %s — Powered by WordPress • Theme: Minimal Blog by Enree.co", date("Y"), get_bloginfo("name") ); ?></div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
