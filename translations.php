<?php



  /*  
  Traduzir uma frase específica em um domínio específico.
  */

  function traduzir_frase_personalizada($translated_text, $text, $domain) {
    // Verifica se o texto a ser traduzido corresponde à frase desejada e se o domínio é o correto.
    if ($text === 'Ship' && $domain === 'a-loja-de-pianos') {
        // Substitui a tradução original pela frase personalizada.
        $translated_text = 'vai';
    }
  
    // Retorna o texto traduzido (personalizado ou original).
    return $translated_text;
  }
  // Adiciona a função 'traduzir_frase_personalizada' ao filtro 'gettext'.
  // Isso garante que a função seja executada sempre que uma tradução for solicitada.
  add_filter('gettext', 'traduzir_frase_personalizada', 20, 3);
  
  function remover_texto_privacidade_checkout($text) {
    return ''; // Retorna uma string vazia para remover o texto
  }
  add_filter('woocommerce_privacy_policy_text', 'remover_texto_privacidade_checkout');
  
  remove_action('woocommerce_checkout_privacy_policy', 'wc_checkout_privacy_policy');

?>