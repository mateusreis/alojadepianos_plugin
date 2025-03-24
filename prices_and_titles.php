<?php


  // =================================================================================================
  // =================================================================================================
  // =================================================================================================
  



 
  add_filter( 'woocommerce_get_price_html', 'custom_price_html', 1000, 2 );
  function custom_price_html( $price, $product ) {
    if ( $product->is_type( 'variable' ) ) {
        // Handle variable product prices.
        $variations = $product->get_available_variations();
        if ( ! empty( $variations ) ) {
            $min_price = $max_price = null;
            foreach ( $variations as $variation ) {
                $variation_product = wc_get_product( $variation['variation_id'] );
                $regular_price = $variation_product->get_regular_price();
                $sale_price = $variation_product->get_sale_price();
  
                if ( $sale_price !== '' && $sale_price < $regular_price ) {
                    $current_price = $sale_price;
                } else {
                    $current_price = $regular_price;
                }
  
                if ( $min_price === null || $current_price < $min_price ) {
                    $min_price = $current_price;
                }
                if ( $max_price === null || $current_price > $max_price ) {
                    $max_price = $current_price;
                }
            }
            if ( $min_price === $max_price ) {
                $price = wc_price( $min_price ) . ' <span class="custom-var-price-text">Only</span>';
            } else {
                // $price = wc_price( $min_price ) . ' - ' . wc_price( $max_price ) . ' <span class="custom-var-price-text">Range</span>';
                $price = "<div class='variation-box'>";
                $price .= "<span class='price_sale_variable'>A partir de <span class='price'>" . wc_price($min_price) . '</span></span>'; 
                $price .= "</div>";              
            }
        }
    } else {
        // Handle simple product prices.
        if ( $product->is_on_sale() ) {

            // echo "is_on_sale";
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();
            $price = '<span class="price_sale">De <del>' . wc_price( $regular_price ) . '</del> por <span class="price"><ins>' . wc_price( $sale_price ) . '</ins> à vista</span></span>';
        } else {
                        //   remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price');
            //   add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 25);
            // $price = $price . ' <span class="custom-simple-price-text"> Em até PARCELSx</span>';
        }
    }
  
    return $price;
  }
  


  function price_styles() {
    echo '<style>
        .price_sale_variable{
        
        }

        .price_sale{
            display: block;
            margin-top: 20px;
        }

        .price_sale del{
            color: #666666;
        };

        .price_sale .price{
            font-size:1.5em;
            color: #990000 !important;      
        };
        .woocommerce-variation-description p,
        p.in-stock{
            font-weight: bold;
        }
  
    </style>';
  }
  add_action( 'wp_head', 'price_styles' );
  






  function custom_variable_stock_style() {
    global $product;

    if ($product && $product->is_type('variable')) {
        // Get available variations
        $variations = $product->get_available_variations();

        foreach ($variations as $variation) {
            $variation_id = $variation['variation_id'];
            $variation_product = wc_get_product($variation_id);

            if ($variation_product && $variation_product->is_in_stock()) {
                // If in stock, inject inline style to the variation's stock status.
                add_filter('woocommerce_variation_option_name', function ($term_name, $variation, $attribute_name, $variation_product) use ($variation_id) {

                    if($variation_product->get_id() === $variation_id){

                        $stock_quantity = $variation_product->get_stock_quantity();

                        if($stock_quantity > 0){
                            $term_name .= ' <span style="color:red;">' . __('In Stock', 'woocommerce') . ' (' . $stock_quantity . ')</span>';
                        } else {
                            $term_name .= ' <span style="color:red;">' . __('In Stock', 'woocommerce') . '</span>';
                        }

                    }

                    return $term_name;
                }, 999, 4);

            }
        }
    }
}
add_action('woocommerce_before_variations_form', 'custom_variable_stock_style');

//If you want to change the style of the stock quantity displayed on the single variation page after a variation is selected:

function custom_variation_stock_html($html, $variation) {

    if($variation && $variation->is_in_stock()){
        $stock_quantity = $variation->get_stock_quantity();

        if($stock_quantity > 0){
            $html = '<p class="stock in-stock" style="color:red;">' . sprintf( __( 'Availability: %s in stock', 'woocommerce' ), wc_format_stock_quantity( $stock_quantity ) ) . '</p>';
        } else {
            $html = '<p class="stock in-stock" style="color:red;">' . __( 'Availability: In stock', 'woocommerce' ) . '</p>';
        }

    }

    return $html;
}

add_filter('woocommerce_variation_availability_html', 'custom_variation_stock_html', 999, 2);











// formata titulo no loop
remove_action( 'woocommerce_shop_loop_item_title','woocommerce_template_loop_product_title', 10 );
add_action('woocommerce_shop_loop_item_title', 'abChangeProductsTitle', 10 );
function abChangeProductsTitle() {
    echo '<h4 class="woocommerce-loop-product_title">xxccxxccxxxx<a href="'.get_the_permalink().'">' . get_the_title() . '</a></h4>';
}

// sem tem template, nao funciona?
remove_action( 'woocommerce_template_single_title','woocommerce_template_loop_product_title', 1111111 );
add_action('woocommerce_template_single_title', 'chagnesingletiotle', 1111111 );
function chagnesingletiotle() {
    echo '<h2 class="woocommerce-loop-product_title">xoopoop<a href="'.get_the_permalink().'">' . get_the_title() . '</a></h2>';
}


  

  
  
  
    // =================================================================================================
    // =================================================================================================
    // =================================================================================================
    
   
  
  
  
//   /**
//    * Reformata a exibição de preços para produtos variáveis.
//    *
//    * Esta função remove a exibição padrão de preços de produtos variáveis
//    * e a substitui por uma exibição personalizada, listando os preços
//    * para cada variação do produto.
//    */
//   add_action('woocommerce_before_single_product_summary', 'check_if_variable_first');
//   function check_if_variable_first()
//   {
//       if (is_product()) {
  
//           global $post;
//           $product = wc_get_product($post->ID);
  
//           if ($product->is_type('variable')) {
//               // Remove a exibição padrão do preço do produto variável.
//               remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price');
  
//               // Adiciona a função customizada para exibir os preços.
//               add_action('woocommerce_single_product_summary', 'custom_wc_template_single_price', 25);
//               function custom_wc_template_single_price()
//               {
//                   global $product;
  
//                   // Executa apenas para produtos variáveis.
//                   if ($product->is_type('variable')) {
  
//                       // Obtém as variações disponíveis do produto.
//                       $variations = $product->get_available_variations();
  
//                       // Itera sobre as variações para exibir os preços.
//                       foreach ($variations as $variation) {
  
//                           // Obtém os preços normal e promocional da variação.
//                           $product_regular_price = $variation['display_regular_price'];
//                           $product_price = $variation['display_price'];
  
//                           // Obtém o título da variação (ex: cor).
//                           $variation_title = $variation['attributes']['attribute_cores'];
  
//                           // Monta o HTML para exibir o preço da variação.
//                           $html = "<span class='variation-title'>" . $variation_title . "</span>";
//                           $html .= "<p class='price'>";
//                           $html .= "<span class='woocommerce-Price-amount amount'>R$ " . number_format($product_regular_price, 2, ',', '.') . "</span> em 10x </br>";
//                           $html .= "<span class='woocommerce-Price-amount amount'>R$ " . number_format($product_price, 2, ',', '.') . "</span> à vista";
//                           $html .= "</p>";
  
//                           // Exibe o HTML do preço da variação.
//                           echo "<div class='variation-box'>" . $html . "</div>";
//                       }
//                   }
//               }
//           } else {
//               // Se o produto não for variável, restaura a exibição padrão do preço.
//               remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price');
//               add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 25);
//           }
//       }
//   }
  
  

  // regular and sales prices, codigo do roberto

// add_filter( 'woocommerce_get_price_html', 'bbloomer_simple_product_price_format', 10, 2 );
 
// function bbloomer_simple_product_price_format( $price , $product ) {
    
//    if ( $product->is_on_sale() && $product->is_type('simple') ) {
//     $price = sprintf( __( '<div class="was-now-save"><div class="was">xxx %1$s - Em até 10x</div><div class="now">%2$s - À vista</div><div class="save">SAVE %3$s</div></div>', 'woocommerce' ), wc_price ( $product->get_regular_price() ), wc_price( $product->get_sale_price() ), wc_price( $product->get_regular_price() - $product->get_sale_price() )  );     
//    }
    
//    return $price;
// }
  



 
/**
 * Customiza a exibição do preço para produtos simples, mostrando o preço normal e a opção de parcelamento.
 *
 * @param string $price O HTML do preço original.
 * @return string O HTML do preço formatado.
 */
// add_filter('woocommerce_get_price_html', 'cs_custom_simple_product_price_html');
// function cs_custom_simple_product_price_html($price)
// {
//   global $product;

//       // Número de parcelas, pega do setup
//       $parcels = esc_attr(get_option('custom_parcels')); // pega do setup
//       if ( empty( $parcels ) ) {
//           $parcels = 10;
//       }



  // $html = '';
  // if ($product){

  //   if ($product->is_type('variable')) { // Se o produto for variável
      
  //     $sale_price = $product->get_price(); // Obtém o preço de venda

  //     if (!is_product()) { // Se não estiver na página do produto (ex: página de loja)
  //       $html = "<div class='variation-box'><p class='price'>";
  //       $html .= "A partir de</br><span class='woocommerce-Price-amount amount'>" . wc_price($sale_price) . '</span> </br>'; 
  //       $html .= "</p></div>";
  //     }else{ // na pagina de produto

  //       $html = "<div class='variation-box'><p class='price'>";
  //       $html .= "xxxA partir de</br><span class='woocommerce-Price-amount amount'>" . wc_price($sale_price) . '</span> </br>'; // Exibe "A partir de" e o preço
  //       $html .= "</p></div>";
  //     }

  //   } elseif ($product->is_type('simple')) { // Se o produto for simples

  //     $regular_price = $product->get_regular_price(); // Obtém o preço normal
  //     $sale_price = $product->get_sale_price(); // Obtém o preço de venda

  //     if ($regular_price > 0) { // Se o preço normal for maior que zero
  //       if ($sale_price) { // Se houver preço de venda
  //         $html = "<div class='variation-box'><p class='price'>";
  //         $html .= "<span class='woocommerce-Price-amount amount'>" . wc_price($regular_price) . '</span> em até ' . $parcels . 'x</br>'; // Exibe o preço normal e a opção de parcelamento
  //         $html .= wc_price($product->get_price()) . __(' à vista'); // Exibe o preço com desconto para pagamento à vista
  //         $html .= "</p></div>";
  //       } else { // Se não houver preço de venda
  //         $html = "<div class='variation-box'><p class='price'>";
  //         $html .= "<span class='woocommerce-Price-amount amount'>" . wc_price($regular_price) . '</span> em até ' . $parcels . 'x</br>'; // Exibe o preço normal e a opção de parcelamento
  //         $html .= "</p></div>";
  //       }
  //     } else { // Se o preço normal for igual a zero
  //       if ($regular_price){
  //         if ($regular_price == 0) { // Se o preço normal for exatamente zero
  //           $html = "<div class='variation-box'><p class='price'>";
  //           $html .= "<span class='woocommerce-Price-amount amount'>Preço sob consulta</span>"; // Exibe "Preço sob consulta"
  //           $html .= "</p></div>";
  //         } else { // Se o preço normal for outro valor (improvável, mas tratado por precaução)
  //           $html = "<div class='variation-box'><p class='price'>";
  //           $html .= "<span class='woocommerce-Price-amount amount'>Selecione uma opção</span>"; // Exibe "Selecione uma opção"
  //           $html .= "</p></div>";
  //         }
  //       }else{
  //         // nao tem preco cadastrado ainda
  //         $html = "mostra erro? nao tem preco cadastrado ainda ";
  //       }

  //     }
  //   }
  // }  

//   return $html;
// }





  // =================================================================================================
  // =================================================================================================
  // =================================================================================================
  
 

/**
 * Altera o preço para "Sob encomenda zerado" se o preço for igual a zero.
 *
 * @param string $price O HTML do preço original.
 * @param WC_Product $product O objeto do produto.
 * @return string O HTML do preço modificado.
 */
// add_filter( 'woocommerce_get_price_html', 'alterar_preco_sob_encomenda', 10, 2 );
// function alterar_preco_sob_encomenda( $price, $product ) {
//   if ( $product->get_price() == 0 ) {
//     $price = '<span class="sob-encomenda">Sob encomenda zerado</span>';
//   }
//   return $price;
// }


?>