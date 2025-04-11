<?php


function show_custom_fields(){
  global $post;
  
  // 1 lancamento
  // Obtém a data atual.
  $data_atual = date('Y-m-d');
  $data_fim_promocao = get_post_meta($post->ID, 'lancamento_termino', true);
  $lancamento = get_post_meta($post->ID, 'lancamento', true);
  // Converte as datas para objetos DateTime para comparação.
  $data_fim = new DateTime($data_fim_promocao);
  $data_atual_obj = new DateTime($data_atual);

  $disponivel_para_experimentacao = get_post_meta($post->ID, 'disponivel_para_experimentacao', true);
  $vendido_por_encomenda = get_post_meta($post->ID, 'vendido_por_encomenda', true);


  
  if ($lancamento || $disponivel_para_experimentacao || $vendido_por_encomenda){
    echo '<div class="cf-custom-fields">';
  }else{
    return;
  }

  // 1 Verifica se a data de término da promoção é futura.
  if ($lancamento){
    // Verifica se a data de término da promoção é futura.
    if (!$data_fim || $data_fim > $data_atual_obj) {
      ?>
      <div class="cf-lancamento">
        <p class="lancamento">LANÇAMENTO!</p>
      </div>
      <?php
    }
  }
  // 2 Disponível para experimentação
  if ($disponivel_para_experimentacao) : ?>
    <div class="cf-experimentacao">
      <p class="experimentacao">Disponível para experimentação!</p>
    </div>
  <?php endif;    
  // 3 Vendido por encomenda
  if ($vendido_por_encomenda) : ?>
    <div class="cf-encomenda">
      <p class="encomenda">Vendido por encomenda!</p>
    </div>
  <?php endif;

  if ($lancamento || $disponivel_para_experimentacao || $vendido_por_encomenda){
    echo '</div>';
  }
}
// mostra as observações
function show_observacoes(){
  global $post;
  // Observações
  $observacoes = get_post_meta($post->ID, 'observacoes', true);
  if ($observacoes) : ?>
      <div class="cf-observacoes">
        <p><strong>* <?php echo $observacoes; ?></strong></p>
      </div>
  <?php endif;
}


// mostra o link do youtube
function show_extras(){
  global $post;
  $youtube_url = get_post_meta($post->ID, 'youtube_url', true);
  $site_oficial = get_post_meta($post->ID, 'site_oficial', true); 
  
  

  if ($youtube_url || $site_oficial) {
    echo '<div class="cf-extras">';
  }else{
    return;
  }

  // Test
  if ($youtube_url) :
    ?>

        <div class="cf-youtube">
          <a href="<?php echo $youtube_url; ?>" target="_blank"><i class="fa-brands fa-youtube"></i> Vídeo</a>
        </div>

    <?php
  endif; 



  if ($site_oficial) { // Se não houver site oficial, sai da função
    ?>

        <div class="cf-site-oficial">
          <a href="<?php echo $site_oficial; ?>" target="_blank"><i class="fa-solid fa-globe"></i> Site oficial</a>
        </div>

    <?php
  }
  
  if ($youtube_url || $site_oficial) {
    echo '</div>';
  }
};








// mostra o título do produto
function show_title() {
  global $post;
  echo '<h1 class="product-title">' . get_the_title() . '</h1>';
}
// mostra o SKU do produto
function show_sku() {
  global $product;
  if ($product->get_sku()) {
    ?>
    <div class="cf-sku">
      <p class="product-sku">Código: <?php echo $product->get_sku(); ?></p>
    </div>
    <?php
  }
}
function show_stock_status ( ) {
  global $product;
  if($product) {
    $stock = $product->get_stock_quantity();
    $_product = wc_get_product( $product );
    if ( !$_product->is_in_stock() ) {
      $availability = 'Fora de estoque.';
    } 
    if ( $_product->is_in_stock() ) {
      if ($stock > 2) {
        $availability = 'Produto disponível.';
      }else{
        $availability = 'Produto disponível. Última unidade!';
      }
    }
  }
  ?>
  <div class="cf-disponivel">
    <?php echo $availability; ?>
  </div>
  <?php
}

// exibe o botão de whatsapp
function show_whatsapp_button(){
  ?>    
  <div class="cf-whatsapp">
    <button class="whatsapp-button">
      <i class="fab fa-whatsapp"></i> Comprar pelo whatsapp
    </button>
  </div>
  <?php
}

// actions para exibir os shortcodes nas páginas de produtos
add_action('woocommerce_single_product_summary', 'show_custom_fields', 1);
add_action('woocommerce_single_product_summary', 'show_title', 10);
add_action('woocommerce_single_product_summary', 'show_sku', 10);
add_action('woocommerce_single_product_summary','show_prices',35);
add_action('woocommerce_simple_add_to_cart', 'show_whatsapp_button', 35);
add_action('woocommerce_simple_add_to_cart', 'show_stock_status', 45 );
add_action('woocommerce_simple_add_to_cart', 'show_observacoes', 200);
add_action('woocommerce_product_meta_end', 'show_extras', 200); 





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
// show price
function show_prices(){
  global $product;


  echo '<div class="pricing-section">';
  echo '<div class="main-price"><h1>single-product.php</h1>';
  
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



  // // Check if product has shipping enabled
  // if ($product->needs_shipping()) {
  //   echo '<div class="shipping-info">';
  //   echo '<span class="truck-icon">';
  //   echo '<i class="fas fa-truck"></i>';
  //   echo '</span>';
  //   echo '<span class="shipping-text">';
  //   echo '<span>Produto com frete</span>';
  //   echo '</span>';
  //   echo '</div>';
  // } else {
  //   echo '<div class="shipping-info">';
  //   echo '<span class="truck-icon">';
  //   echo '<i class="fa-solid fa-store"></i>';
  //   echo '</span>'; 
  //   echo '<span class="shipping-text">';
  //   echo '<span>Somente retirada na loja</span>';
  //   echo '</span>';
  //   echo '</div>';
  // }
  // Check if product has shipping enabled
  // Check if product has shipping class "Somente retirada"
  $shipping_class_id = $product->get_shipping_class_id();
  $shipping_class = $shipping_class_id ? get_term($shipping_class_id, 'product_shipping_class') : null;
  if ($shipping_class && $shipping_class->slug === 'somente-retirada') {
?>
    <div class="shipping-info">
    <span class="store-icon">
    <i class="fas fa-store"></i>
    </span>
    <span class="shipping-text">
    <span>Somente retirada na loja</span>
    </span>
    </div>
<?php
  }else{

    echo '<div class="shipping-info">';
    echo '<span class="truck-icon">';
    echo '<i class="fas fa-truck"></i>';
    echo '</span>';
    echo '<span class="shipping-text">';
    echo '<span>Produto com frete</span>';
    echo '</span>';
    echo '</div>';

    echo do_shortcode('[calculadora_melhor_envio product_id="'. $product->get_id() .'"]');
  }
  echo '</div>'; // main-price
  echo '</div>'; // pricing-section
}   



// Change "Add to cart" button text to "Comprar"
add_filter('woocommerce_product_single_add_to_cart_text', 'custom_add_to_cart_text');
add_filter('woocommerce_product_add_to_cart_text', 'custom_add_to_cart_text');
function custom_add_to_cart_text() {
  return 'COMPRAR';
}



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
function getYoutubeIdFromUrl($url){
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



?>