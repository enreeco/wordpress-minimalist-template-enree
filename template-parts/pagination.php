
<?php
/**
 * Template part: Pagination
 */
global $wp_query;
$links = paginate_links( array(
  'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
  'format'    => '?paged=%#%',
  'current'   => max( 1, get_query_var( 'paged' ) ),
  'total'     => $wp_query->max_num_pages ? $wp_query->max_num_pages : 1,
  'type'      => 'list',
  'prev_text' => '← Precedente',
  'next_text' => 'Successivo →',
  'end_size'  => 1,
  'mid_size'  => 2,
  'show_all'  => false,
) );
if ( $links ) {
  // Convert ul class to our styling-friendly version
  echo '<nav class="pagination" role="navigation" aria-label="Paginazione articoli">';
  echo $links;
  echo '</nav>';
} else {
  echo '<nav class="pagination" role="navigation" aria-label="Paginazione articoli"><span class="page-numbers current">1</span></nav>';
}
?>
