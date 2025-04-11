<?php

// add_action('woocommerce_single_product_summary', 'customizing_single_product_summary_hooks', 2  );
// function customizing_single_product_summary_hooks(){
//         remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_price',10  );
//         add_action('woocommerce_template_loop_price','show_prices',10);
// }


// ferra com as columns
// remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
// add_filter('loop_shop_columns', 'loop_columns');
//  if(!function_exists('loop_columns')) { function loop_columns() { return 5; }}
//  if ( empty( $woocommerce_loop['columns'] ) ) { $woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );}



 





// function woo_related_products_limit() {
//     global $product; $args['posts_per_page'] = 6;
//     return $args;
//     }

//     add_filter( 'woocommerce_output_related_products_args', 'jk_related_products_args', 20 );
//     function jk_related_products_args( $args ) {
//     $args['posts_per_page'] = 4; // 4 related products
//     $args['columns'] = 2; // arranged in 2 columns
//     return $args;
//     }






// Make product title bold in shop loop
// add_filter('woocommerce_shop_loop_item_title', 'make_shop_title_bold', 10);
// function make_shop_title_bold() {
//     global $product;
//     echo '<h2 class="woocommerce-loop-product__title"><strong>' . get_the_title() . 'xx</strong></h2>';
//     return;
// }





?>