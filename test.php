<?php
//   function bold_variable_price($price, $product) {
//     if ($product->is_type('variable')) {
//         $price = '<strong>aaa ' . $price . '</strong>';
//     }
//     return $price;
//   }
//   add_filter('woocommerce_get_price_html', 'bold_variable_price', 1, 2);
  
//   function bold_variation_price($price_html, $variation) {
//     if ($variation->get_parent_id()) {
//         $price_html = '<strong>bbb ' . $price_html . '</strong>';
//     }
//     return $price_html;
//   }
//   add_filter('woocommerce_variation_price_html', 'bold_variation_price', 1, 2);
  
//   function bold_variation_sale_price($price_html, $variation) {
//     if ($variation->get_parent_id()) {
//         $price_html = '<strong>ccc ' . $price_html . '</strong>';
//     }
//     return $price_html;
//   }
//   add_filter('woocommerce_variation_sale_price_html', 'bold_variation_sale_price', 1, 2);
  
//   function bold_variation_regular_price($price_html, $variation) {
//     if ($variation->get_parent_id()) {
//         $price_html = '<strong>ddd ' . $price_html . '</strong>';
//     }
//     return $price_html;
//   }
//   add_filter('woocommerce_variation_regular_price_html', 'bold_variation_regular_price', 1, 2);  
// ?>