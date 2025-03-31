<?php
 /**
  * Exibe campos personalizados (custom fields) na página de edição do produto.
  *
  * Esta função adiciona caixas de seleção (checkbox) e campos de texto
  * para informações adicionais do produto, como disponibilidade para experimentação,
  * se é vendido por encomenda, se é um lançamento, se está em promoção, site oficial,
  * link do Youtube e observações sobre preço e condições de pagamento.
  *
  * @param WP_Post $post O objeto post do produto atual.
  * @return void
  */

/**
 * Adiciona um meta box com informações do produto na página de edição do produto.
 *
 * @return void
 */

 function add_product_custom_fields() {
    add_meta_box(
      'product_custom_fields', // ID do meta box
      'Informações do Produto', // Título do meta box
      'display_product_custom_fields', // Função de callback para exibir os campos
      'product', // Tipo de post onde os campos serão exibidos
      'side', // Posição do meta box
      'high' // Prioridade do meta box
    );
  }
  add_action('add_meta_boxes', 'add_product_custom_fields');


  function display_product_custom_fields($post) {
    // Cria um campo nonce para segurança, verificando se o formulário foi submetido corretamente.
    wp_nonce_field('product_custom_fields_nonce', 'product_custom_fields_nonce');

    // Campo "Disponível para experimentação?" (checkbox)
    $disponivel_para_experimentacao_value = get_post_meta($post->ID, 'disponivel_para_experimentacao', true);
    $disponivel_para_experimentacao_checked = checked($disponivel_para_experimentacao_value, '1', false);
    echo '<p>';
    echo '<input type="checkbox" id="disponivel_para_experimentacao" name="disponivel_para_experimentacao" value="1" ' . $disponivel_para_experimentacao_checked . ' />';
    echo '<label for="disponivel_para_experimentacao">Disponível para experimentação?</label>';
    echo '</p>';

    // Campo "Vendido por encomenda?" (checkbox)
    $vendido_por_encomenda_value = get_post_meta($post->ID, 'vendido_por_encomenda', true);
    $vendido_por_encomenda_checked = checked($vendido_por_encomenda_value, '1', false);
    echo '<p>';
    echo '<input type="checkbox" id="vendido_por_encomenda" name="vendido_por_encomenda" value="1" ' . $vendido_por_encomenda_checked . ' />';
    echo '<label for="vendido_por_encomenda">Vendido por encomenda?</label>';
    echo '</p>';

    // Campo "É um Lançamento?" (checkbox)
    $lancamento_value = get_post_meta($post->ID, 'lancamento', true);
    $lancamento_checked = checked($lancamento_value, '1', false);
    echo '<p>';
    echo '<input type="checkbox" id="lancamento" name="lancamento" value="1" ' . $lancamento_checked . ' />';
    echo '<label for="lancamento">É um Lançamento?</label>';
    echo '</p>';

    // Campo "Data de término da promoção" (texto)
    // se a data for nula, fica como lançamento até desmarcar o checkbox
    $lancamento_termino_value = get_post_meta($post->ID, 'lancamento_termino', true);
    echo '<p>';
    echo '<label for="lancamento_termino">Até quando ele será lançamento?</label><br>';
    echo '<input type="date" id="lancamento_termino" name="lancamento_termino" value="' . esc_attr($lancamento_termino_value) . '" />';
    echo '</p>';
    echo '<p>* Se não tiver uma data final ele fica como lançamento até desmarcar o checkbox</p>';


    // Campo "Está em PROMOÇÃO?" (checkbox)
    $promocao_value = get_post_meta($post->ID, '_promocao', true); // Note o "_" antes de "promocao"
    $promocao_checked = checked($promocao_value, '1', false);
    echo '<p>';
    echo '<input type="checkbox" id="promocao" name="promocao" value="1" ' . $promocao_checked . ' />';
    echo '<label for="promocao">Está em PROMOÇÃO?</label>'; // Texto corrigido para clareza
    echo '</p>';

    // Campo "Site oficial" (texto)
    $site_oficial_value = get_post_meta($post->ID, 'site_oficial', true);
    echo '<p>';
    echo '<label for="site_oficial">Site oficial:</label><br>';
    echo '<input type="text" id="site_oficial" name="site_oficial" value="' . esc_attr($site_oficial_value) . '" />';
    echo '</p>';

    // Campo "Youtube" (texto)
    $youtube_url_value = get_post_meta($post->ID, 'youtube_url', true);
    echo '<p>';
    echo '<label for="youtube_url">Link do Youtube (normal ou shorts):</label><br>';
    echo '<input type="text" id="youtube_url" name="youtube_url" value="' . esc_attr($youtube_url_value) . '" />';
    echo '</p>';

    // Campo "Observações" (texto)
    $observacoes_value = get_post_meta($post->ID, 'observacoes', true);
    echo '<p>';
    echo '<label for="observacoes">Observações (sobre o preço e condições de pagamento):</label><br>';
    echo '<input type="text" id="observacoes" name="observacoes" value="' . esc_attr($observacoes_value) . '" />';
    echo '</p>';

} 

/**
 * Salva os valores dos campos personalizados do produto.
 *
 * Esta função é executada quando um post (produto) é salvo e atualiza os valores
 * dos campos personalizados no banco de dados. Realiza verificações de segurança
 * para garantir que apenas usuários autorizados e com nonce válido possam salvar os dados.
 *
 * @param int $post_id O ID do post do produto.
 * @return void
 */

function save_product_custom_fields_values($post_id) {
    // Verifica o nonce para garantir que o formulário foi submetido corretamente.
    if (!isset($_POST['product_custom_fields_nonce']) || !wp_verify_nonce($_POST['product_custom_fields_nonce'], 'product_custom_fields_nonce')) {
        return;
    }

    // Ignora o salvamento automático.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Verifica se o usuário atual tem permissão para editar o post.
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Salva os valores dos campos (checkboxes e textos).
    // Para checkboxes, usa '1' para marcado e '0' para desmarcado.
    // Para campos de texto, usa sanitize_text_field para limpar os dados.

    if (isset($_POST['disponivel_para_experimentacao'])) {
        update_post_meta($post_id, 'disponivel_para_experimentacao', '1');
    } else {
        update_post_meta($post_id, 'disponivel_para_experimentacao', '0');
    }

    if (isset($_POST['vendido_por_encomenda'])) {
        update_post_meta($post_id, 'vendido_por_encomenda', '1');
    } else {
        update_post_meta($post_id, 'vendido_por_encomenda', '0');
    }

    if (isset($_POST['lancamento'])) {
        update_post_meta($post_id, 'lancamento', '1');
    } else {
        update_post_meta($post_id, 'lancamento', '0');
    }

    if (isset($_POST['promocao'])) {
        update_post_meta($post_id, 'promocao', '1'); 
    } else {
        update_post_meta($post_id, 'promocao', '0');
    }

    if (isset($_POST['site_oficial'])) {
        update_post_meta($post_id, 'site_oficial', sanitize_text_field($_POST['site_oficial']));
    }

    if (isset($_POST['youtube_url'])) {
        update_post_meta($post_id, 'youtube_url', sanitize_text_field($_POST['youtube_url']));
    }

    if (isset($_POST['observacoes'])) {
        update_post_meta($post_id, 'observacoes', sanitize_text_field($_POST['observacoes']));
    }

    if (isset($_POST['lancamento_termino'])) {
        update_post_meta($post_id, 'lancamento_termino', sanitize_text_field($_POST['lancamento_termino']));
    }
}
add_action('save_post', 'save_product_custom_fields_values');


  function show_disponivel_para_experimentacao(){
    global $post;
  
    // Disponível para experimentação
    $disponivel_para_experimentacao = get_post_meta($post->ID, 'disponivel_para_experimentacao', true);
    if ($disponivel_para_experimentacao) : ?>
    <div class="product-custom-field">
      <span class="wp-block-heading experimentacao">
        <strong>Disponível para experimentação!</strong>
      </span>
    </div>
    <?php endif;
  }
    
  function show_vendido_por_encomenda(){
    global $post;
  
    // Vendido por encomenda
    $vendido_por_encomenda = get_post_meta($post->ID, 'vendido_por_encomenda', true);
    if ($vendido_por_encomenda) : ?>
    <div class="product-custom-field">
      <span class="wp-block-heading encomenda">
        <strong>Vendido por encomenda!</strong>
      </span>
    </div>
    <?php endif;
  }

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

  add_action('woocommerce_single_product_summary', 'show_vendido_por_encomenda', 30);

  add_action('woocommerce_single_product_summary', 'show_siteoficial', 140); 
  add_action('woocommerce_single_product_summary', 'show_youtube_link', 140);




function format_title_hook() {

    $priority = has_action('woocommerce_single_product_summary', 'woocommerce_template_single_title');
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', $priority);

    add_action( 'woocommerce_single_product_summary', 'show_titulo', 5);
    
    
    // echo "<hr/>result of add_result = " . $add_result . "<hr/>";
    // echo "<hr/>result of remove_result = " . $remove_result . "<hr/>";
}


function show_titulo() {
  echo '<h3 class="woocommerce-loop-product_title">cccwsss ' . get_the_title() . '</h3>';
}

add_action('plugins_loaded','format_title_hook');



// esse metodo de alterar hooks funciona

function format_prices_hook() {
    $priority = has_action('woocommerce_single_product_summary', 'woocommerce_template_single_price');
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', $priority);

    add_action('woocommerce_single_product_summary','show_precos',10);
}

function show_precos(){
  echo '<h3 class="woocommerce-loop-product_title">xxxffsss NOVOS PRECOS</h3>';
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
            </div>
            
            <div class="purchase-section">
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
            </button>
            
            <p class="availability">Disponibilidade: <span>Disponível</span></p>
        </div>';
}

add_action('plugins_loaded','format_prices_hook');



// add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
// add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
// add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
// add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
// add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
// add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 5




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
// remove_action( 'woocommerce_shop_loop_item_title','woocommerce_template_loop_product_title', 1 );
// add_action('woocommerce_shop_loop_item_title', 'abChangeProductsTitle', 10 );
// function abChangeProductsTitle() {
//     echo '<h4 class="woocommerce-loop-product_title">xxccxxccxxxx<a href="'.get_the_permalink().'">' . get_the_title() . '</a></h4>';
// }

// sem tem template, nao funciona?
// remove_action( 'woocommerce_template_single_title','woocommerce_template_loop_product_title', 1111111 );
// add_action('woocommerce_template_single_title', 'chagnesingletiotle', 1111111 );
// function chagnesingletiotle() {
//     echo '<h2 class="woocommerce-loop-product_title">xoopoop<a href="'.get_the_permalink().'">' . get_the_title() . '</a></h2>';
// }


/*

add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

add_action( 'woocommerce_before_subcategory', 'woocommerce_template_loop_category_link_open', 10 );
add_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );
add_action( 'woocommerce_after_subcategory', 'woocommerce_template_loop_category_link_close', 10 );

add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

*/




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