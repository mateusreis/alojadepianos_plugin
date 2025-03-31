<?php
/**
 * Plugin Name: A loja de Pianos
 * Description: Uma série de modificações no wordpress e woocommerce. Leia a documentação no menu. 
 * Version: 1.0.1
 * Text Domain: a-loja-de-pianos
 */


 // Include the function files
require_once plugin_dir_path(__FILE__) . 'admin_page.php';
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
 


/**
 * Oculta categoria sem slug
 */
function ocultar_product_meta_sem_categoria_slug() {
  // Declara a variável global $post para acessar o objeto do post atual.
  global $post;

  // Verifica se a página atual é uma página de produto do WooCommerce.
  if (is_product()) {
      // Obtém todas as categorias do produto atual e armazena em $categorias.
      $categorias = get_the_terms($post->ID, 'product_cat');

      // Verifica se o produto possui categorias e se não houve erros ao obtê-las.
      if ($categorias && !is_wp_error($categorias)) {
          // Itera sobre cada categoria do produto.
          foreach ($categorias as $categoria) {
              // Verifica se o slug da categoria atual é "sem-categoria".
              if ($categoria->slug === 'sem-categoria') {
                  // Se a categoria "sem-categoria" for encontrada, remove a ação que exibe as informações meta do produto.
                  // 'woocommerce_template_single_meta' é a função que exibe os metadados do produto (SKU, categorias, tags).
                  // 'woocommerce_single_product_summary' é o hook onde essa função é adicionada.
                  // 40 é a prioridade da ação.
                  remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
                  // Sai do loop após encontrar a categoria "sem-categoria", pois não é necessário verificar as outras categorias.
                  break;
              }
          }
      }
  }
}
// Adiciona a função 'ocultar_product_meta_sem_categoria_slug' ao hook 'woocommerce_before_single_product'.
// Isso garante que a função seja executada antes da exibição do conteúdo do produto.
add_action('woocommerce_before_single_product', 'ocultar_product_meta_sem_categoria_slug');

?>