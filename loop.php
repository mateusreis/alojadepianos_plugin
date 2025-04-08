<?php

// add_action('woocommerce_single_product_summary', 'customizing_single_product_summary_hooks', 2  );
// function customizing_single_product_summary_hooks(){
//         remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_price',10  );
//         add_action('woocommerce_template_loop_price','show_prices',10);
// }



remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
add_filter('loop_shop_columns', 'loop_columns');
 if(!function_exists('loop_columns')) { function loop_columns() { return 5; }}
 if ( empty( $woocommerce_loop['columns'] ) ) { $woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );}



 


add_filter( 'woocommerce_after_shop_loop_item_title', 'remove_woocommerce_loop_price', 2 );
function remove_woocommerce_loop_price() {
    remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
    add_action('woocommerce_after_shop_loop_item_title', 'show_prices_loop', 10);
}

function show_prices_loop() {
    if (!is_product()) { // Only run on catalog/loop pages
        global $product;
        
        if ($product->is_type('variable')) {
            $prices = $product->get_variation_prices();
            $min_price = current($prices['price']);
            $max_price = end($prices['price']);
            
            if ($min_price !== $max_price) {
                echo '<span class="price">A partir de R$ ' . number_format($min_price, 2, ',', '.') . '</span>';
            } else {
                echo '<span class="price">R$ ' . number_format($min_price, 2, ',', '.') . '</span>';
            }
        } else {
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();
            
            if ($sale_price) {
                echo '<span class="price"><del>R$ ' . number_format($regular_price, 2, ',', '.') . '</del>';
                echo ' oi ';
                echo '<ins>R$ ' . number_format($sale_price, 2, ',', '.') . '</ins></span>';
            } else {
                echo '<span class="price">R$ ' . number_format($regular_price, 2, ',', '.') . '</span>';
            }


            echo '<span class="price">À vista no Pix/TED: R$xxxx.00</span>';
        }
    }
}



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