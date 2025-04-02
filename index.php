<?php
/**
 * Plugin Name: A loja de Pianos
 * Description: Uma série de modificações no wordpress e woocommerce. Leia a documentação no menu. 
 * Version: 1.0.1
 * Text Domain: a-loja-de-pianos
 */


 // Include the function files
require_once plugin_dir_path(__FILE__) . 'admin_page.php';
require_once plugin_dir_path(__FILE__) . 'custom_fields.php';
require_once plugin_dir_path(__FILE__) . 'help_page.php';
require_once plugin_dir_path(__FILE__) . 'remove_actions.php';
require_once plugin_dir_path(__FILE__) . 'single_product.php';
require_once plugin_dir_path(__FILE__) . 'translations.php';
require_once plugin_dir_path(__FILE__) . 'whatsapp.php';

require_once plugin_dir_path(__FILE__) . 'test.php';




// Enqueue the custom CSS
function black_background_enqueue_styles() {
  wp_enqueue_style(
      'alojadepianos-plugin-style',
      plugins_url('css/style.css', __FILE__),
      array(),
      '1.0.0'
  );
}
add_action('wp_enqueue_scripts', 'black_background_enqueue_styles'); 



function incluir_tags_na_busca_woocommerce( $query, $query_vars ) {
  if ( isset( $query_vars['s'] ) && empty( $query_vars['s'] ) === false ) {
      $term = esc_attr( $query_vars['s'] );
      $query['s'] = "$term";
      if ( empty( $query['tax_query'] ) ) {
          $query['tax_query'] = array();
      }
      $query['tax_query'][] = array(
          'taxonomy' => 'product_tag',
          'field'    => 'name',
          'terms'    => $term,
          'operator' => 'LIKE'
      );
  }
  return $query;
}
add_filter( 'woocommerce_product_data_store_cpt_get_products_query', 'incluir_tags_na_busca_woocommerce', 10, 2 );




/**
 * Exibe somente novos produtos
 */
function show_newest_products_only( $q ) {
  if ( ! $q->is_main_query() ) return;
  if ( ! $q->is_post_type_archive() ) return;
  
  if ( ! is_admin() && is_shop() ) {
      $q->set( 'orderby', 'date' );
      $q->set( 'order', 'DESC' );
      
      // Optional: Set the number of products to display
      $q->set( 'posts_per_page', 12 );
      
      // Optional: Set a time frame for "newest" products (e.g., last 30 days)
      // $q->set( 'date_query', array(
      //     array(
      //         'after' => '30 days ago',
      //         'inclusive' => true,
      //     ),
      // ) );
  }
}
add_action( 'woocommerce_product_query', 'show_newest_products_only' );
 



//  intercept the variable.php template

add_filter( 'woocommerce_locate_template', 'intercept_wc_template', 10, 3 );
/**
 * Filter the cart template path to use cart.php in this plugin instead of the one in WooCommerce.
 *
 * @param string $template      Default template file path.
 * @param string $template_name Template file slug.
 * @param string $template_path Template file name.
 *
 * @return string The new Template file path.
 */
function intercept_wc_template( $template, $template_name, $template_path ) {

	if ( 'variable.php' === basename( $template ) ) {
		$template = trailingslashit( plugin_dir_path( __FILE__ ) ) . 'woocommerce/single-product/add-to-cart/variable.php';
	}

	return $template;

}



?>