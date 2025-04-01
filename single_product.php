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
  add_action('woocommerce_single_product_summary', 'show_observacoes', 30);
  add_action('woocommerce_single_product_summary', 'show_preco', 30);
  add_action('woocommerce_single_product_summary', 'show_vendido_por_encomenda', 30);
  add_action('woocommerce_single_product_summary', 'show_siteoficial', 140); 
  add_action('woocommerce_single_product_summary', 'show_youtube_link', 140);


function show_preco(){
    global $product;
    $price = $product->get_price();
    echo '<div class="preco">';
    echo '<span class="price">R$ ' . number_format($price, 2, ',', '.') . '</span>';
    echo '</div>';
}   



//   $price         = apply_filters( 'woocommerce_variation_prices_price', $variation->get_price( 'edit' ), $variation, $product );
//   $regular_price = apply_filters( 'woocommerce_variation_prices_regular_price', $variation->get_regular_price( 'edit' ), $variation, $product );
//   $sale_price    = apply_filters( 'woocommerce_variation_prices_sale_price', $variation->get_sale_price( 'edit' ), $variation, $product );






// Simple
add_filter('woocommerce_product_get_price', 'custom_price', 99, 2 );
add_filter('woocommerce_product_get_regular_price', 'custom_price', 99, 2 );
// Variable
add_filter('woocommerce_product_variation_get_regular_price', 'custom_price', 99, 2 );
add_filter('woocommerce_product_variation_get_price', 'custom_price' , 99, 2 );
// Variations (of a variable product)
add_filter('woocommerce_variation_prices_price', 'custom_variation_price', 99, 3 );
add_filter('woocommerce_variation_prices_regular_price', 'custom_variation_price', 99, 3 );


function custom_price( $price, $product ) {
    // Delete product cached price  (if needed)
    wc_delete_product_transients($product->get_id());

    return $price * 10; // X3 for testing
}

function custom_variation_price( $price, $variation, $product ) {
    // Delete product cached price  (if needed)
    wc_delete_product_transients($variation->get_id());

    // Check if variation is on sale
    if ($variation->is_on_sale()) {
        // If on sale, apply different multiplier or logic
        return $price * 1000; // Example: lower multiplier for sale items
    }

    return $price * 1000; // X3 for testing
}


// isso functiona, remove os preços das variações  e manda para outro lugar
// function move_variation_price() {
//     remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );
//     add_action( 'woocommerce_after_add_to_cart_quantity', 'woocommerce_single_variation', 10 );
// }
// add_action( 'woocommerce_before_add_to_cart_form', 'move_variation_price' );




// Customize the sale price format
add_filter('woocommerce_format_sale_price', 'custom_format_sale_price', 10, 3);
function custom_format_sale_price($price, $regular_price, $sale_price) {
    $formatted_regular_price = is_numeric($regular_price) ? wc_price($regular_price) : $regular_price;
    $formatted_sale_price = is_numeric($sale_price) ? wc_price($sale_price) : $sale_price;

    // Custom HTML structure for sale prices
    $price = '<div class="price-wrapper">';
    $price .= '<del class="regular-price">xyx' . $formatted_regular_price . '</del> por';
    $price .= '<ins class="sale-price">' . $formatted_sale_price . '</ins>';
    $price .= '</div>';

    // Add screen reader text for accessibility
    $price .= '<span class="screen-reader-text">';
    $price .= sprintf(__('Preço original: %s. ', 'woocommerce'), 
        wp_strip_all_tags($formatted_regular_price));
    $price .= sprintf(__('Preço atual: %s', 'woocommerce'), 
        wp_strip_all_tags($formatted_sale_price));
    $price .= '</span>';

    return $price;
}



// Change Add to Cart button text
add_filter('woocommerce_product_single_add_to_cart_text', 'custom_add_to_cart_text');
add_filter('woocommerce_product_add_to_cart_text', 'custom_add_to_cart_text');

function custom_add_to_cart_text() {
    return 'Comprar';
}



function woocommerce_custom_add_to_cart_text( $add_to_cart_text, $product ) {
    // Get cart
    $cart = WC()->cart;
    
    // If cart is NOT empty
    if ( ! $cart->is_empty() ) {

        // Iterating though each cart items
        foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
            // Get product id in cart
            $_product_id = $cart_item['product_id'];
     
            // Compare 
            if ( $product->get_id() == $_product_id ) {
                // Change text
                $add_to_cart_text = __( 'Already in carxxt', 'woocommerce' );
                break;
            }
        }
    }

    return $add_to_cart_text;
}
add_filter( 'woocommerce_product_add_to_cart_text', 'woocommerce_custom_add_to_cart_text', 10, 2 );






add_filter('woocommerce_get_availability_text', 'storefront_change_stock_text', 9999, 2 );

function storefront_change_stock_text ( $availability, $product) {
	
	if($product) {
		$stock = $product->get_stock_quantity();
		$_product = wc_get_product( $product );
		if ( !$_product->is_in_stock() ) {
			$availability = __(  'Out of stock online. But check the stores below for in-store pickup availability.', 'woocommerce' );
		} 
		
		if ( $_product->is_in_stock() ) {
			$availability = __(  $stock . ' in stock online for delivery. Pickup availability listed at the stores below.', 'woocommerce' );
		}
	}
return $availability;
}





// Customize the Add to Cart button layout
add_filter('woocommerce_loop_add_to_cart_link', 'custom_add_to_cart_button_layout', 10, 2);
function custom_add_to_cart_button_layout($button, $product) {
    // Get the existing button classes
    $button_classes = array(
        'button',
        'product_type_' . $product->get_type(),
        $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
        $product->supports('ajax_add_to_cart') && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
    );

    // Build custom button HTML
    $button = sprintf(
        '<a href="%s" data-product_id="%s" data-product_sku="%s" class="%s" %s>
            <span class="button-text">%s</span>
            <span class="button-icon">→</span>
        </a>',
        esc_url($product->add_to_cart_url()),
        esc_attr($product->get_id()),
        esc_attr($product->get_sku()),
        esc_attr(implode(' ', array_filter($button_classes))),
        $product->supports('ajax_add_to_cart') ? 'data-quantity="1"' : '',
        esc_html($product->add_to_cart_text())
    );

    return $button;
}




add_filter('woocommerce_product_add_to_cart_button', 'custom_single_add_to_cart_button', 10, 2);
function custom_single_add_to_cart_button($button_html, $product) {
    // Get button text
    $button_text = $product->is_type('simple') ? $product->single_add_to_cart_text() : $product->add_to_cart_text();
    
    // Build custom button HTML
    $button = sprintf(
        '<button type="submit" name="add-to-cart" value="%s" class="single_add_to_cart_button button alt%s">
            <span class="button-text">%s</span>
            <span class="button-icon">→</span>
        </button>',
        esc_attr($product->get_id()),
        $product->is_type('variable') ? ' disabled wc-variation-selection-needed' : '',
        esc_html($button_text)
    );

    return $button;
}


// Add custom CSS for the button styling
add_action('wp_head', 'add_custom_button_styles');
function add_custom_button_styles() {
    ?>
    <style>
        .add_to_cart_button {
            display: flex !important;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 24px !important;
            transition: all 0.3s ease !important;
        }
        .add_to_cart_button .button-icon {
            transition: transform 0.3s ease;
        }
        .add_to_cart_button:hover .button-icon {
            transform: translateX(5px);
        }
    </style>
    <?php
}



// Add custom CSS for the price styling
add_action('wp_head', 'add_variation_price_styles');
function add_variation_price_styles() {
    ?>
    <style>
        .price-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .regular-price {
            color: #999;
            text-decoration: line-through;
            font-weight: normal;
        }
        .sale-price {
            color: #77a464;
            font-size: 1.2em;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
    <?php
}













  // mostra os preços das variações
add_action('woocommerce_single_product_summary', 'show_variation_prices', 30);
function show_variation_prices() {
    global $product;
    
    if ($product->is_type('variable')) {
        $variations = $product->get_available_variations();
        $prices = array();
        
        foreach ($variations as $variation) {
            if (!empty($variation['display_price'])) {
                $prices[] = $variation['display_price'];
            }
        }
        
        if (!empty($prices)) {
            $min_price = min($prices);
            $max_price = max($prices);
            
            echo '<h3 class="variation-price">xx';
            if ($min_price === $max_price) {
                echo 'xxxbbbR$ ' . number_format($min_price, 2, ',', '.');
            } else {
                echo 'R$ ' . number_format($min_price, 2, ',', '.') . ' - R$ ' . number_format($max_price, 2, ',', '.');
            }
            echo '</h3>';
        }
    }
}





// Filter to make variation prices bold
add_filter('woocommerce_get_price_html', 'make_variation_prices_bold', 999, 2);
function make_variation_prices_bold($price, $product) {
    if ($product->is_type('variable')) {
        $price = str_replace('R$ xx', '<strong>R$</strong>', $price);
        $price = str_replace(',', '<strong>,</strong>', $price);
        $price = str_replace('.', '<strong>.</strong>', $price);
        $price = preg_replace('/(\d+)/', '<strong>$1</strong>', $price);
    }
    return $price;
}






// esse metodo de alterar hooks funciona
// remove os preços das variações que aparecem : R$ 8.100,00 – R$ 54.000,00
// (menor valor de todos e maio valor valor de todos)

function remove_prices() {
    $priority = has_action('woocommerce_single_product_summary', 'show_variation_prices');
    remove_action('woocommerce_single_product_summary', 'show_variation_prices', $priority);
    // add_action('woocommerce_single_product_summary','show_precos',10);
}

add_action('init', 'remove_prices');
















// function format_title_hook() {
//     $priority = has_action('woocommerce_single_product_summary', 'woocommerce_template_single_title');
//     remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', $priority);
//     add_action( 'woocommerce_single_product_summary', 'show_product_title', 5);
// }
// function show_product_title() {
//   echo '<h3 class="woocommerce-loop-product_title">xxzzxv' . get_the_title() . '</h3>';
// }
// add_action('plugins_loaded','format_title_hook');



// esse metodo de alterar hooks funciona

function format_prices_hook() {
    $priority = has_action('woocommerce_single_product_summary', 'woocommerce_template_single_price');
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', $priority);

    add_action('woocommerce_single_product_summary','show_precos',10);
}

function show_precos(){
//   echo '<h3 class="woocommerce-loop-product_title">xxxffsss NOVOS PRECOS</h3>';
  echo '<div class="pricing-section">



            <div class="main-price">
                <span class="price">R$ 6.852,31</span>
                <span class="payment-term">à vista</span>
            </div>
            <p class="savings">Economize: R$ 761,37</p>
            <p class="regular-price">R$ 7.613,68</p>
            


            <div class="installment-info">
                <div class="card-icon">
                    <i class="far fa-credit-card"></i>
                </div>
                <div class="installment-text">
                    <span>até <strong>10x</strong> de <strong>R$ 761,36</strong> sem juros</span>
                    <a href="#" class="payment-options">mais formas de pagamento</a>
                </div>
            </div>'; 
            
            // custom_wc_template_single_price();

            echo '<div class="purchase-section">
                <div class="quantity-selector">
                    <input type="text" value="1" class="quantity-input">
                    <div class="quantity-buttons">
                        <button class="quantity-button plus">+</button>
                        <button class="quantity-button minus">−</button>
                    </div>
                </div>
                
                <button class="buy-button">
                    <i class="fas fa-shopping-cart"></i> Comprar
                </button>
            </div>
            
            <button class="whatsapp-button">
                <i class="fab fa-whatsapp"></i> Comprar pelo whatsapp
            </button>';

            // display_product_availability();
            // show_stock_availability();
            echo '</div>';

}

add_action('plugins_loaded','format_prices_hook');



// Add variation prices above product summary
remove_action('woocommerce_single_product_summary', 'show_variation_prices', 30);
add_action('woocommerce_before_single_product_summary', 'show_variation_prices', 5);





?>