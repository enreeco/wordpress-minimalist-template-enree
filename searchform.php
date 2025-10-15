
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
  <label>
    <span class="screen-reader-text"><?php _e( 'Search:', 'enree-minimal' ); ?></span>
    <input type="search" class="search-field" id="search-field" placeholder="<?php esc_attr_e( 'Search…', 'enree-minimal' ); ?>" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" />
  </label>
  <button type="submit" class="search-submit"><?php esc_html_e( 'Search', 'enree-minimal' ); ?></button>
</form>

<!-- Search Preview Container -->
<div id="search-preview" class="search-preview" style="display: none;">
  <div class="search-preview-content">
    <div class="search-preview-header">
      <h3><?php esc_html_e( 'Search Results', 'enree-minimal' ); ?></h3>
      <button type="button" class="search-preview-close" aria-label="<?php esc_attr_e( 'Close preview', 'enree-minimal' ); ?>">&times;</button>
    </div>
    <div class="search-preview-results">
      <div class="search-preview-loading"><?php esc_html_e( 'Loading...', 'enree-minimal' ); ?></div>
    </div>
    <div class="search-preview-footer">
      <a href="#" class="search-preview-view-all"><?php esc_html_e( 'View all results', 'enree-minimal' ); ?></a>
    </div>
  </div>
</div>
