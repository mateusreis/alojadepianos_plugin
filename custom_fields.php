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
    echo '<label for="disponivel_para_experimentacao">Disponível para experimentação</label>';
    echo '</p>';

    // Campo "Vendido por encomenda" (checkbox)
    $vendido_por_encomenda_value = get_post_meta($post->ID, 'vendido_por_encomenda', true);
    $vendido_por_encomenda_checked = checked($vendido_por_encomenda_value, '1', false);
    echo '<p>';
    echo '<input type="checkbox" id="vendido_por_encomenda" name="vendido_por_encomenda" value="1" ' . $vendido_por_encomenda_checked . ' />';
    echo '<label for="vendido_por_encomenda">Vendido por encomenda</label>';
    echo '</p>';

    // Campo "É um Lançamento?" (checkbox)
    $lancamento_value = get_post_meta($post->ID, 'lancamento', true);
    $lancamento_checked = checked($lancamento_value, '1', false);
    echo '<p>';
    echo '<input type="checkbox" id="lancamento" name="lancamento" value="1" ' . $lancamento_checked . ' />';
    echo '<label for="lancamento">Lançamento</label>';
    echo '</p>';

    // Campo "Data de término da promoção" (texto)
    // se a data for nula, fica como lançamento até desmarcar o checkbox
    // $lancamento_termino_value = get_post_meta($post->ID, 'lancamento_termino', true);
    // echo '<p>';
    // echo '<label for="lancamento_termino">Até quando ele será lançamento?</label><br>';
    // echo '<input type="date" id="lancamento_termino" name="lancamento_termino" value="' . esc_attr($lancamento_termino_value) . '" />';
    // echo '</p>';
    // echo '<p>* Se não tiver uma data final ele fica como lançamento até desmarcar o checkbox</p>';


    // Campo "Está em PROMOÇÃO?" (checkbox)
    // $promocao_value = get_post_meta($post->ID, '_promocao', true); // Note o "_" antes de "promocao"
    // $promocao_checked = checked($promocao_value, '1', false);
    // echo '<p>';
    // echo '<input type="checkbox" id="promocao" name="promocao" value="1" ' . $promocao_checked . ' />';
    // echo '<label for="promocao">Está em PROMOÇÃO</label>'; // Texto corrigido para clareza
    // echo '</p>';

    // Campo "Preço sob consulta?" (checkbox)
    // $preco_sob_consulta_value = get_post_meta($post->ID, 'preco_sob_consulta', true);
    // $preco_sob_consulta_checked = checked($preco_sob_consulta_value, '1', false);
    // echo '<p>';
    // echo '<input type="checkbox" id="preco_sob_consulta" name="preco_sob_consulta" value="1" ' . $preco_sob_consulta_checked . ' />';
    // echo '<label for="preco_sob_consulta">Preço sob consulta</label>';
    // echo '</p>';

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


    if (isset($_POST['preco_sob_consulta'])) {
        update_post_meta($post_id, 'preco_sob_consulta', '1'); 
    } else {
        update_post_meta($post_id, 'preco_sob_consulta', '0');
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



?>