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
  



  function custom_fields_styles() {
    echo '<style>
            .siteoficial a{
              font-size: 1rem;
              font-weight: 500;
            }

            .lancamento{
              font-size: 1rem;
              font-weight: 500;
              color: #000000;  
              background-color: #ffcc00;
              margin: 0.5rem 0rem 1rem 0rem;
              padding: 0.65rem 0.85rem 0.65rem 0.85rem;
              border-radius: 0.3rem 0.3rem 0.3rem 0.3rem;
              order: 3;
            }

            .experimentacao {
              font-size: 1rem;
              font-weight: 500;
              color: rgba(38, 171, 91, 1);
              background-color: rgba(34, 197, 94, 0.15);
              margin: 0.5rem 0rem 1rem 0rem;
              padding: 0.65rem 0.85rem 0.65rem 0.85rem;
              border-radius: 0.3rem 0.3rem 0.3rem 0.3rem;
              order: 3;
            }

            .encomenda{
              font-size: 1rem;
              font-weight: 500;
              color: rgba(38, 171, 91, 1);
              background-color: rgba(34, 197, 94, 0.15);
              margin: 0.5rem 0rem 1rem 0rem;
              padding: 0.65rem 0.85rem 0.65rem 0.85rem;
              border-radius: 0.3rem 0.3rem 0.3rem 0.3rem;
              order: 3;

            }

            .observacoes {
              font-size: 1rem;
              font-weight: 500;
              color: rgb(77, 77, 77);
              background-color: rgba(128, 128, 128, 0.15);
              margin: 0.5rem 0rem 1rem 0rem;
              padding: 0.65rem 0.85rem 0.65rem 0.85rem;
              border-radius: 0.3rem 0.3rem 0.3rem 0.3rem;
              order: 3;
            }
          </style>';
  }
  add_action( 'wp_head', 'custom_fields_styles' );  

  // actions para exibir os shortcodes nas páginas de produtos
  
  add_action('woocommerce_single_product_summary', 'show_lancamento', 1);
  add_action('woocommerce_single_product_summary', 'show_disponivel_para_experimentacao', 1);
  add_action('woocommerce_single_product_summary', 'show_observacoes', 30);

  add_action('woocommerce_single_product_summary', 'show_vendido_por_encomenda', 30);

  add_action('woocommerce_single_product_summary', 'show_siteoficial', 140); 
  add_action('woocommerce_single_product_summary', 'show_youtube_link', 140);


  add_action('plugins_loaded','alter_woo_hooks2');

function alter_woo_hooks2() {
    $add_result = add_action( 'woocommerce_shop_loop_item_title', 'newTitle', 5);
    $remove_result = remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);

    // echo "<hr/>result of add_result = " . $add_result . "<hr/>";
    // echo "<hr/>result of remove_result = " . $remove_result . "<hr/>";
}


function newTitle() {
  global $product;
    echo '<h4 class="woocommerce-loop-product_title">cccwsss <a href="'.get_the_permalink().'">' . get_the_title() . '</a></h4>';
}




// esse metodo de alterar hooks funciona

function alter_some_hook() {
    $priority = has_action('woocommerce_single_product_summary', 'woocommerce_template_single_price');
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', $priority);

    add_action('woocommerce_single_product_summary','sdfsdf',10);
}

function sdfsdf(){
  echo "AQUI VAI O PRECO NOVO CARAIO";
}

add_action('plugins_loaded','alter_some_hook');



// add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
// add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
// add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
// add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
// add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
// add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
?>