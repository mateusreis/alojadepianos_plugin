<?php
    // Disponível para experimentação
  function show_disponivel_para_experimentacao(){
    global $post;
    $disponivel_para_experimentacao = get_post_meta($post->ID, 'disponivel_para_experimentacao', true);
    if ($disponivel_para_experimentacao) : ?>
    <div class="product-custom-field">
      <span class="wp-block-heading experimentacao">
        <strong>Disponível para experimentação!</strong>
      </span>
    </div>
    <?php endif;
  }
    
  // mostra o vendido por encomenda
  function show_vendido_por_encomenda(){
    global $post;
    $vendido_por_encomenda = get_post_meta($post->ID, 'vendido_por_encomenda', true);
    if ($vendido_por_encomenda) : ?>
    <div class="product-custom-field">
      <span class="wp-block-heading encomenda">
        <strong>Vendido por encomenda!</strong>
      </span>
    </div>
    <?php endif;
  }

  // mostra as observações
  function show_observacoes(){
    global $post;
   
    // Observações
    $observacoes = get_post_meta($post->ID, 'observacoes', true);
    if ($observacoes) : ?>
    <div class="product-custom-field">
      <span class="wp-block-heading observacoes">
        <strong>* <?php echo $observacoes; ?></strong>
      </span>
    </div>
    <?php endif;
  }
  
  // mostra a data do lancamento
  function show_lancamexxnto(){
    global $post;
 
    // Vendido por encomenda
    $lancamento = get_post_meta($post->ID, 'lancamento', true);
    if ($lancamento) : ?>
    <div class="product-custom-field">
      <span class="wp-block-heading lancamento">
          <strong><?php formata_data_lancamento($lancamento); ?></strong>
      </span>
    </div>
    <?php endif;
  }
  

  // mostra o lancamento
  function show_lancamento() {
    global $post;
  
    // Obtém a data atual.
    $data_atual = date('Y-m-d');
    $data_fim_promocao = get_post_meta($post->ID, 'lancamento_termino', true);
    $lancamento = get_post_meta($post->ID, 'lancamento', true);

    // Converte as datas para objetos DateTime para comparação.
    $data_fim = new DateTime($data_fim_promocao);
    $data_atual_obj = new DateTime($data_atual);
    if ($lancamento){
      // Verifica se a data de término da promoção é futura.
      if (!$data_fim || $data_fim > $data_atual_obj) {
        echo '<div class="product-custom-field">';
        echo '<span class="wp-block-heading lancamento">';
        echo '<strong>LANÇAMENTO!</strong>';
        echo '</span>';
        echo '</div>';
      }
    }
    //echo "<! -- Data que deixa de ser lançamento: ". $data_fim_promocao. " -->";
  }

  // mostra o site oficial
function show_siteoficial(){
  global $post;
  $site_oficial = get_post_meta($post->ID, 'site_oficial', true); // Obtém o valor do meta campo 'site_oficial'
  if (!$site_oficial) { // Se não houver site oficial, sai da função
    return;
  } else { // Se houver site oficial, exibe o link
    echo '<div class="wp-block-heading siteoficial">';
    echo '&raquo; <a href="'. $site_oficial .'" target="_blank">Site oficial</a>';
    echo '</div>';
  }
};
add_shortcode('siteoficial', 'show_siteoficial'); // Cria um shortcode para exibir o site oficial
  


  // mostra o youtube
  function embedVideo($url){
      $height = 315;
      $width = 560;
      $width_shorts = $height;
      $height_shorts = $width;
  
      if (strpos($url, "shorts")) {
          // echo getYoutubeIdFromUrl($url);
          // preg_match( '/youtube\.com\/shorts\/(\w+\s*\/?)*([0-9]+)*$/i', $url, $matches );    
          return '<iframe width="' . $width_shorts . '" height="' . $height_shorts . '" src="https://www.youtube.com/embed/' . getYoutubeIdFromUrl($url) . '" style="width: 100% !important; height: auto !important; max-width: 320px!important; aspect-ratio: 9 / 16 !important;" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
          return;
      } else if (getYoutubeIdFromUrl($url)) {
          return '<iframe width="' . $width . '" height="' . $height . '" src="https://www.youtube.com/embed/' . getYoutubeIdFromUrl($url) . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
      } else {
          return "Vídeo não encontrado";
      }
  }

  // pega o id do youtube
  function getYoutubeIdFromUrl($url)
  {
      $parts = parse_url($url);
      if (isset($parts['query'])) {
          parse_str($parts['query'], $qs);
          if (isset($qs['v'])) {
              return $qs['v'];
          } else if (isset($qs['vi'])) {
              return $qs['vi'];
          }
      }
      if (isset($parts['path'])) {
          $path = explode('/', trim($parts['path'], '/'));
          return $path[count($path) - 1];
      }
      return false;
  }
  // mostra o youtube
  function show_youtube(){
    global $post;
    $youtube_url = get_post_meta($post->ID, 'youtube_url', true);
    if (!$youtube_url) {
        return;
    }
    // Test
    // $urls = array(
    // 'http://youtube.com/v/dQw4w9WgXcQ?feature=youtube_gdata_player',
    // 'http://youtube.com/vi/dQw4w9WgXcQ?feature=youtube_gdata_player',
    // 'http://youtube.com/?v=dQw4w9WgXcQ&feature=youtube_gdata_player',
    // 'http://www.youtube.com/watch?v=dQw4w9WgXcQ&feature=youtube_gdata_player',
    // 'http://youtube.com/?vi=dQw4w9WgXcQ&feature=youtube_gdata_player',
    // 'http://youtube.com/watch?v=dQw4w9WgXcQ&feature=youtube_gdata_player',
    // 'http://youtube.com/watch?vi=dQw4w9WgXcQ&feature=youtube_gdata_player',
    // 'https://www.youtube.com/watch?v=CvDFeHaNJ-4',
    // 'https://www.youtube.com/shorts/aCemOhwp4ho',
    // 'https://youtube.com/shorts/aCemOhwp4ho?feature=share',
    // 'https://youtube.com/shorts/aCemOhwp4ho'
    // );
    // foreach($urls as $url){
    //  echo embedVideo($url);
    // }
  
    if ($youtube_url) :
        echo '<h3>Ouça:</h3>';
        echo embedVideo($youtube_url);
    endif; 
  }
 
  // mostra o link do youtube
  function show_youtube_link(){
    global $post;
    $youtube_url = get_post_meta($post->ID, 'youtube_url', true);
    if (!$youtube_url) {
        return;
    }
    // Test
    if ($youtube_url) :
        echo '<div class="youtube_link">';
        echo '<a href="'. $youtube_url .'" target="_blank">Vídeo</a>';
        echo '</div>';
    endif; 
    
  };
  
  // actions para exibir os shortcodes nas páginas de produtos
  
  add_action('woocommerce_single_product_summary', 'show_lancamento', 1);
  add_action('woocommerce_single_product_summary', 'show_disponivel_para_experimentacao', 1);

  add_action('woocommerce_single_product_summary', 'show_vendido_por_encomenda', 20);
  add_action('woocommerce_single_product_summary', 'show_observacoes', 25);

  add_action('woocommerce_single_product_summary', 'show_siteoficial', 140); 
  add_action('woocommerce_single_product_summary', 'show_youtube_link', 140);







// Change product title to h4
add_filter('woocommerce_single_product_summary', 'change_product_title_tag', 1);
// add_filter('woocommerce_single_product_summary', 'change_product_title_tag', 2);
function change_product_title_tag() {
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
    add_action('woocommerce_single_product_summary', 'show_product_title', 5);
}

function show_product_title() {
    global $product;
    echo '<h1 class="product-title">' . get_the_title() . '</h1>';
}

// Show product SKU
add_action('woocommerce_single_product_summary', 'show_product_sku', 6);
function show_product_sku() {
    global $product;
    if (!$product->is_type('variable')){ 
        if ($product->get_sku()) {
            echo '<p class="product-code">Código: ' . $product->get_sku() . '</p>';
        }
    }
}








// add_filter('woocommerce_variable_price_html','custom_from',10);
// add_filter('woocommerce_grouped_price_html','custom_from',10);
// add_filter('woocommerce_variable_sale_price_html','custom_from',10);
// add_filter('woocommerce_template_single_price','custom_from',10);
// function custom_from($price){
//     return false;
// }






// show price
// esse metodo de alterar hooks funciona
// remove os preços das variações que aparecem : R$ 8.100,00 – R$ 54.000,00
// (menor valor de todos e maio valor valor de todos)
// function remove_prices() {
//     $priority = has_action('woocommerce_single_product_summary', 'show_prices');
//     remove_action('woocommerce_single_product_summary', 'show_prices', $priority);
//     add_action('woocommerce_single_product_summary','show_prices',10);
  
// }

// add_action('plugins_loaded', 'remove_prices');

add_action('woocommerce_single_product_summary', 'customizing_single_product_summary_hooks', 2  );
function customizing_single_product_summary_hooks(){
        remove_action('woocommerce_single_product_summary','woocommerce_template_single_price',10  );
        add_action('woocommerce_single_product_summary','show_prices',10);
}



// show price
function show_prices(){
    global $product;

    echo '<div class="pricing-section">';
        echo '<div class="main-price">';

        if ($product->is_type('variable')){
        
            $variations = $product->get_available_variations();
            $prices = array();
            
            
            foreach ($variations as $variation) {
                if (!empty($variation['display_price'])) {
                    $prices[] = $variation['display_price'];
                }
            }
            
            if (!empty($prices)) {
                $min_price = min($prices);
                // $max_price = max($prices);
                echo '<p class="payment-condition">A partir de: </p>';
                echo '<span class="price">R$ '. number_format($min_price, 2, ',', '.') .'</span>';
            }            
            
        }else{
            // price single
            // Get prices for simple product
 
            $price = $product->get_price();
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();
            $savings = $regular_price - $sale_price;

            if ($product->is_on_sale()){

                echo '<span class="price">R$ '. number_format($sale_price, 2, ',', '.') .'</span>';
                echo '<span class="payment-term"> à vista</span>';
                echo '<p class="savings">Economize R$ '. number_format($savings, 2, ',', '.') .'</p>';
                echo '<p class="regular-price">R$ '. number_format($regular_price, 2, ',', '.') .'</p>';                
            }else{
              echo $price;
                echo '<span class="price">xx R$'. number_format($sale_price, 2, ',', '.') .'</span>';
            }
        }


        echo '<div class="installment-info">';
        echo '<div class="card-icon">';
        echo '<i class="far fa-credit-card"></i>';
        echo '</div>';
        echo '<div class="installment-text">';
        echo '<span>até <strong>10x</strong> de <strong>R$ XXX</strong> sem juros</span>';
        echo '<a href="#" class="payment-options">mais formas de pagamento</a>';
        echo '</div>';
        echo '</div>';
      
        // Check if product has shipping enabled
        if ($product->needs_shipping()) {
            echo '<div class="shipping-info">';
            echo '<span class="truck-icon">';
            echo '<i class="fas fa-truck"></i>';
            echo '</span>';
            echo '<span class="shipping-text">';
            echo '<span>Produto com frete</span>';
            echo '</span>';
            echo '</div>';
        } else {
            echo '<div class="shipping-info">';
            echo '<span class="truck-icon">';
            echo '<i class="fas fa-ban"></i>';
            echo '</span>'; 
            echo '<span class="shipping-text">';
            echo '<span>Produto sem frete</span>';
            echo '</span>';
            echo '</div>';
        }

        // Check if product has shipping enabled

        // Check if product has shipping class "Somente retirada"
        $shipping_class_id = $product->get_shipping_class_id();
        $shipping_class = $shipping_class_id ? get_term($shipping_class_id, 'product_shipping_class') : null;
        
        if ($shipping_class && $shipping_class->slug === 'somente-retirada') {
            echo '<div class="shipping-info">';
            echo '<span class="store-icon">';
            echo '<i class="fas fa-store"></i>';
            echo '</span>';
            echo '<span class="shipping-text">';
            echo '<span>Somente retirada na loja</span>';
            echo '</span>';
            echo '</div>';
        }else{
          echo do_shortcode('[calculadora_melhor_envio product_id="'. $product->get_id() .'"]');
        }



        echo '</div>'; // main-price
    echo '</div>'; // pricing-section
}   





// Change "Add to cart" button text to "Comprar"
add_filter('woocommerce_product_single_add_to_cart_text', 'custom_add_to_cart_text');
add_filter('woocommerce_product_add_to_cart_text', 'custom_add_to_cart_text');

function custom_add_to_cart_text() {
    return 'Comprar';
}



add_action('woocommerce_single_product_summary', 'show_whatsapp_button', 20);
function show_whatsapp_button(){
    echo '<button class="whatsapp-button">';
    echo '<i class="fab fa-whatsapp"></i> Comprar pelo whatsapp';
    echo '</button>';
}

add_filter('woocommerce_get_availability_text', 'storefront_change_stock_text', 9999, 2 );
function storefront_change_stock_text ( $availability, $product) {
	
	if($product) {
		$stock = $product->get_stock_quantity();
		$_product = wc_get_product( $product );
		if ( !$_product->is_in_stock() ) {
			$availability = __(  'Fora de estoque.', 'woocommerce' );
		} 
		
		if ( $_product->is_in_stock() ) {
      if ($stock > 2) {
        $availability = __(  $stock . ' disponíveis para entrega.', 'woocommerce' );
      }else{
        $availability = __(  $stock . ' disponível para entrega.', 'woocommerce' );
      }
		}
	}
return $availability;
}


// Make product title bold in shop loop
add_filter('woocommerce_shop_loop_item_title', 'make_shop_title_bold', 10);
function make_shop_title_bold() {
    global $product;
    echo '<h2 class="woocommerce-loop-product__title"><strong>' . get_the_title() . 'xx</strong></h2>';
    return;
}



// function make_prices_blue($price_html, $product) {
//     return '<span style="color: blue;">' . $price_html . '</span>';
// }


// // Add text between sale price and regular price in variation price display
// add_filter('woocommerce_format_sale_price', 'custom_format_sale_price', 10, 3);
// function custom_format_sale_price($price, $regular_price, $sale_price) {
//     // Create the custom format with text between prices
//     $custom_text = '<span class="price-savings-text">Economize</span>';
    
//     // Format with the custom text between prices
//     $formatted_price = '<ins>' . $sale_price . '</ins> ' . $custom_text . ' <del>' . $regular_price . '</del>';
    
//     return $formatted_price;
// }


// // Move product summary below price
// remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
// add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 115);





//   $price         = apply_filters( 'woocommerce_variation_prices_price', $variation->get_price( 'edit' ), $variation, $product );
//   $regular_price = apply_filters( 'woocommerce_variation_prices_regular_price', $variation->get_regular_price( 'edit' ), $variation, $product );
//   $sale_price    = apply_filters( 'woocommerce_variation_prices_sale_price', $variation->get_sale_price( 'edit' ), $variation, $product );







// // Simple
// add_filter('woocommerce_product_get_price', 'custom_price', 99, 2 );
// add_filter('woocommerce_product_get_regular_price', 'custom_price', 99, 2 );
// // Variable
// add_filter('woocommerce_product_variation_get_regular_price', 'custom_price', 99, 2 );
// add_filter('woocommerce_product_variation_get_price', 'custom_price' , 99, 2 );
// // Variations (of a variable product)
// add_filter('woocommerce_variation_prices_price', 'custom_variation_price', 99, 3 );
// add_filter('woocommerce_variation_prices_regular_price', 'custom_variation_price', 99, 3 );


// function custom_price( $price, $product ) {
//     // Delete product cached price  (if needed)
//     wc_delete_product_transients($product->get_id());

//     return $price * 10; // X3 for testing
// }

// function custom_variation_price( $price, $variation, $product ) {
//     // Delete product cached price  (if needed)
//     wc_delete_product_transients($variation->get_id());

//     // Check if variation is on sale
//     if ($variation->is_on_sale()) {
//         // If on sale, apply different multiplier or logic
//         return $price * 2; // Example: lower multiplier for sale items
//     }

//     return $price * 2; // X3 for testing
// }


// isso functiona, remove os preços das variações  e manda para outro lugar
// function move_variation_price() {
//     remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );
//     add_action( 'woocommerce_after_add_to_cart_quantity', 'woocommerce_single_variation', 10 );
// }
// add_action( 'woocommerce_before_add_to_cart_form', 'move_variation_price' );




// Customize the sale price format
// add_filter('woocommerce_format_sale_price', 'custom_format_sale_price', 10, 3);
// function custom_format_sale_price($price, $regular_price, $sale_price) {
//     $formatted_regular_price = is_numeric($regular_price) ? wc_price($regular_price) : $regular_price;
//     $formatted_sale_price = is_numeric($sale_price) ? wc_price($sale_price) : $sale_price;

//     // Custom HTML structure for sale prices
//     $price = '<div class="pricing-section">';
//     $price .= '<div class="main-price">';
//     $price .= '<span class="price">xccDe ' . $formatted_regular_price . '</span> por <ins class="sale-price">' . $formatted_sale_price . '</ins>';
//     $price .= '<span class="payment-term">à vista</span>';
//     $price .= '</div>';
//     $price .= '<p class="regular-price">R$ 7.613,68</p>';
//     $price .= '</div>';
//     // Add screen reader text for accessibility
//     // $price .= '<span class="screen-reader-text">';
//     // $price .= sprintf(__('Preço original: %s. ', 'woocommerce'), 
//     //     wp_strip_all_tags($formatted_regular_price));
//     // $price .= sprintf(__('Preço atual: %s', 'woocommerce'), 
//     //     wp_strip_all_tags($formatted_sale_price));
//     // $price .= '</span>';

//     return $price;
// }



// [calculadora_melhor_envio product_id="product_id"]





// These are actions you can unhook/remove!
 
// add_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
// add_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
 
// add_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
// add_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );
 
// add_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );
// add_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
// add_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
 
// add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 ); 
 
// add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
// add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
 
// add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
 
// add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
// add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
 
// add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
// add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
 
// add_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
 
// add_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );


?>