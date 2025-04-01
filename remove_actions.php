<?php

/**
 * Remove a aba de comentários da página do produto.
 */

 add_filter( 'woocommerce_product_tabs', 'remover_aba_comentarios', 98 );
 function remover_aba_comentarios( $tabs ) {
   unset( $tabs['reviews'] );
   return $tabs;
 }
 
 add_filter( 'woocommerce_product_tabs', 'remover_aba_informacoes_adicionais', 98 );
 function remover_aba_informacoes_adicionais( $tabs ) {
   unset( $tabs['additional_information'] ); // To remove the additional information tab, que só mostra as variacoes
   return $tabs;
 }
 
 
 /**
  * Remove WooCommerce Downloads Page
  */
 
 function remove_downloads_endpoint($endpoints) {
   unset($endpoints['downloads']);
   return $endpoints;
 }
 add_filter('woocommerce_account_menu_items', 'remove_downloads_endpoint');
 
 function remove_downloads_content() {
   remove_action('woocommerce_account_downloads_endpoint', 'woocommerce_account_downloads');
 }
 add_action('init', 'remove_downloads_content');
 
 function remove_downloads_rewrite_endpoint() {
   add_rewrite_endpoint('downloads', EP_PAGES);
   flush_rewrite_rules();
 }
 register_activation_hook(__FILE__, 'remove_downloads_rewrite_endpoint');
 
 function remove_downloads_rewrite_endpoint_deactivation() {
   flush_rewrite_rules();
 }
 register_deactivation_hook(__FILE__, 'remove_downloads_rewrite_endpoint_deactivation');
 
 
 
 /*
 * Remove o bloco que exibe o número de resultados de produtos na loja. 
 */
 
 function hide_woocommerce_product_results_count_block() {
   ?>
   <style type="text/css">
       .woocommerce-result-count {
           display: none !important;
       }
   </style>
   <?php
 }
 add_action('wp_head', 'hide_woocommerce_product_results_count_block');
 
 //Alternative way to hide using a filter, if the block is created via a template
 function custom_remove_result_count( $template ) {
   if ( is_shop() || is_product_category() || is_product_tag() ) {
       remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
   }
   return $template;
 }
 add_filter( 'template_include', 'custom_remove_result_count', 99 );
 
 
 
 /*
 * Remove o bloco com o dropdown de ordenação de produtos na loja.
 */
 
 function hide_woocommerce_catalog_sorting() {
   ?>
   <style type="text/css">
       .woocommerce-ordering {
           display: none !important;
       }
   </style>
   <?php
 }
 add_action('wp_head', 'hide_woocommerce_catalog_sorting');
 
 //Alternative way to hide using a filter, if the sorting block is created via a template
 function custom_remove_catalog_sorting( $template ) {
   if ( is_shop() || is_product_category() || is_product_tag() ) {
       remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
   }
   return $template;
 }
 add_filter( 'template_include', 'custom_remove_catalog_sorting', 99 );
 
   
 /**
  * Remove the Sort by dropdown completely
  */  
 
  function thenga_remove_filtering() {
   remove_action('woocommerce_catalog_ordering', 'woocommerce_catalog_ordering');
   remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10 );
   remove_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 );
 }
 add_action( 'init', 'thenga_remove_filtering',1 );
 
 /**
  * Remove product tags display from single product pages
  */  

function remove_tags() {
  $priority = has_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta');
  remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', $priority);
  // add_action('woocommerce_single_product_summary','show_precos',10);
}
add_action('init', 'remove_tags');

 
 
  /**
   * Disable comments on posts and pages
   */
 
   function disable_comments_on_posts_and_pages() {
     // Remove support for comments and trackbacks from post types
     remove_post_type_support('post', 'comments');
     remove_post_type_support('post', 'trackbacks');
     remove_post_type_support('page', 'comments');
     remove_post_type_support('page', 'trackbacks');
   
     // Close comments on the front-end
     add_filter('comments_open', '__return_false', 20, 2);
     add_filter('pings_open', '__return_false', 20, 2);
   
     // Hide existing comments
     add_filter('comments_array', '__return_empty_array', 10, 2);
   
     // Remove comments page in menu
     add_action('admin_menu', function () {
         remove_menu_page('edit-comments.php');
     });
   
     // Remove comments links from admin bar
     add_action('init', function () {
         if (is_admin_bar_showing()) {
             remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
         }
     });
   
     // Disable comment-reply.min.js
     add_action('wp_enqueue_scripts', function () {
         wp_dequeue_script('comment-reply');
     });
   
     // Remove comments metabox from dashboard
     add_action('admin_init', function () {
         remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
     });
   
     // Disable support for comments and trackbacks in post types
     add_action('admin_init', function () {
         $post_types = get_post_types();
         foreach ($post_types as $post_type) {
             if (post_type_supports($post_type, 'comments')) {
                 remove_post_type_support($post_type, 'comments');
                 remove_post_type_support($post_type, 'trackbacks');
             }
         }
     });
   }
   
   add_action('init', 'disable_comments_on_posts_and_pages');
 
   

  /**
   * Remove product title from breadcrumb on single product pages
   */
  add_filter( 'woocommerce_get_breadcrumb', 'remove_product_from_breadcrumb', 20, 2 );
  function remove_product_from_breadcrumb( $crumbs, $breadcrumb ) {  
     if ( is_product() ) {
        global $product;
        $index = count( $crumbs ) - 1; // product name is always last item
        $value = $crumbs[$index];
        $crumbs[$index][0] = null;
     }
     return $crumbs;
  }

  /**
   * Format breadcrumbs with smaller font size
   */
  function smaller_breadcrumb_font() {
      ?>
      <style type="text/css">
        .woocommerce-breadcrumb,
        .woocommerce .woocommerce-breadcrumb,
        nav.woocommerce-breadcrumb {
            font-size: 0.75em !important; /* Reduced from 0.85em */
            margin-bottom: 1em !important;
            opacity: 0.8; /* Added for subtle appearance */
        }
          /* If you need to target specific theme breadcrumbs */
        .breadcrumb-trail,
        .breadcrumbs {
            font-size: 0.75em !important; /* Reduced from 0.85em */
            opacity: 0.8; /* Added for subtle appearance */
        }
      </style>
      <?php
  }
  add_action('wp_head', 'smaller_breadcrumb_font');
?>