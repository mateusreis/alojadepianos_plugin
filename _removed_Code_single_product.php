


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



















woocommerce_template_single_price

add_filter('woocommerce_variable_price_html','custom_from',10);
add_filter('woocommerce_grouped_price_html','custom_from',10);
add_filter('woocommerce_variable_sale_price_html','custom_from',10);
function custom_from($price){
    return false;
}














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
