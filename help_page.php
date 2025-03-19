<?php

 /**
  * Adiciona uma página em branco ao menu de administração do WordPress.
  * Esta página é acessível através do menu "A loja de PIANOS".
  *
  * @return void
  */
  add_action( 'admin_menu', 'adicionar_pagina_em_branco_ao_menu' );
  function adicionar_pagina_em_branco_ao_menu() {
      add_menu_page( 
          'A loja de PIANOS', // Título da página no menu
          'A loja de PIANOS', // Título do menu
          'manage_options', // Capacidade necessária para acessar a página
          'pagina-em-branco', // Slug da página (usado na URL)
          'exibir_pagina_em_branco', // Função de callback para exibir o conteúdo da página
          'dashicons-admin-generic', // URL do ícone
          80  // Posição          
      );
  }
  
  /**
   * Exibe o conteúdo da página em branco no menu de administração.
   * Esta função é chamada pela função `adicionar_pagina_em_branco_ao_menu()`.
   *
   * @return void
   */
  function exibir_pagina_em_branco() {
      echo '<h1>O que esse plugin faz:</h1>';
      echo '<p>- Remove as categorias de posts caso o post não tenha categoria definida</p>';
      echo '<p>- Remove as categorias do rodapé do produto se o produto estiver na categorias "sem-categoria".</p>';
      echo '<p>- Remove todos os formulários de comentários</p>';
      echo '<p>- Remove as abas de comentários dos produtos do woocommerce</p>';
      echo '<p>- Cria custom fields para a página de produto (Lançamento, disponível para experimentação e outros).</p>';
  }
 

  
?>