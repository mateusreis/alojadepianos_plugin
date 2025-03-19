<?php


/*
* Adiciona uma página de configurações ao menu de administração do WordPress.
*/


// Add menu page
function cft_add_menu_page() {
    add_menu_page(
        'Mensagens customizáveis da loja',
        'Mensagens customizáveis',
        'manage_options',
        'custom-footer-text-settings',
        'cft_settings_page',
        'dashicons-admin-generic',
        99
    );
  }
  add_action('admin_menu', 'cft_add_menu_page');
  
  // Settings page content
  function cft_settings_page() {
  
    ?>
    <div class="wrap">
        <h1>Aviso</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('cft_settings_group');
            do_settings_sections('cft_settings_group');
            ?>
            
            <table class="form-table">
  
                <tr valign="top">
                    <td  span="2">
                      <h2>WHATSAPP</h2>
                    </td>
                </tr>
                
                
                <tr valign="top">
                    <th scope="row">Texto que aparece no botão do Whatsapp:</th>
                    <td>
                        <input type="text" name="custom_whatsapp_phonenumber" placeholder="5511XXXXXXXX." value="<?php echo esc_attr(get_option('custom_whatsapp_phonenumber')); ?>">  
                        <p class="omw-description">Por padrão exibe: Gostaria de encomenda este produto.</p>
                    </td>
                </tr>
                
                <tr valign="top">
                    <th scope="row">Texto que aparece no botão do Whatsapp:</th>
                    <td>
                        <input type="text" name="custom_whatsapp_texto_botao" placeholder="Gostaria de encomenda este produto." value="<?php echo esc_attr(get_option('custom_whatsapp_texto_botao')); ?>">  
                        <p class="omw-description">Por padrão exibe: Gostaria de encomenda este produto.</p>
                    </td>
                </tr>
  
                <tr valign="top">
                    <th scope="row">Texto que aparece no chat do whatsapp:</th>
                    <td>
                        <input type="text" name="custom_whatsapp_texto_mensagem" placeholder="Gostaria de encomenda este produto." value="<?php echo esc_attr(get_option('custom_whatsapp_texto_mensagem')); ?>">  
                        <p class="omw-description">Por padrão exibe: Gostaria de encomenda este produto: PRODUTO XXX</p>
                    </td>
                </tr>
  
  
                <tr valign="top">
                    <td  span="2">
                      <h2>PARCELAS</h2>
                    </td>
                </tr>              
  
                <tr valign="top">
                    <th scope="row">Número de parcelas sem juros:</th>
                    <td>
                        <input type="text" name="custom_parcels" value="<?php echo esc_attr(get_option('custom_parcels')); ?>">  
                        <p class="omw-description">Somente números, padrão: 10</p>
                    </td>
                </tr>
  
                <tr valign="top">
                    <td  span="2">
                      <h2>AVISO</h2>
                    </td>
                </tr>
  
                <tr valign="top">
                    <th scope="row">Texto de aviso (aparece abaixo do header, acima dos produtos).</th>
                    <td>
                        <textarea cols="40" rows="5" name="custom_notice_message"><?php echo esc_attr(get_option('custom_notice_message')); ?></textarea>
                    </td>
                </tr>
  
                <tr valign="top">
                    <th scope="row">Exibir aviso?</th>
                    <td>
                        <input type="checkbox" name="custom_notice_message_checkbox" value="1" <?php checked(1, get_option('custom_notice_message_checkbox'), true); ?> />
                    </td>
                </tr>

  
                <tr valign="top">
                    <th scope="row">Texto de aviso (ACIMA do header).</th>
                    <td>
                        <textarea cols="40" rows="5" name="custom_notice_message_top"><?php echo esc_attr(get_option('custom_notice_message_top')); ?></textarea>
                    </td>
                </tr>
  
                <tr valign="top">
                    <th scope="row">Exibir aviso?</th>
                    <td>
                        <input type="checkbox" name="custom_notice_message_top_checkbox" value="1" <?php checked(1, get_option('custom_notice_message_top_checkbox'), true); ?> />
                    </td>
                </tr>


            </table>
  
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
  }
  
  // Register settings
  function cft_register_settings() {
    // register_setting('cft_settings_group', 'custom_notice_message', 'sanitize_text_field');
    register_setting('cft_settings_group', 'custom_whatsapp_phonenumber');  
    register_setting('cft_settings_group', 'custom_whatsapp_texto_botao');  
    register_setting('cft_settings_group', 'custom_whatsapp_texto_mensagem');  
    register_setting('cft_settings_group', 'custom_parcels');  
    register_setting('cft_settings_group', 'custom_notice_message');  
    register_setting('cft_settings_group', 'custom_notice_message_checkbox');

    register_setting('cft_settings_group', 'custom_notice_message_top');  
    register_setting('cft_settings_group', 'custom_notice_message_top_checkbox');    
  }
  add_action('admin_init', 'cft_register_settings');
  
  
  
  // MENSAGEM DE AVISO
  function custom_notice_message() {
    if (get_option('custom_notice_message_checkbox')) {
        $notice_message = get_option('custom_notice_message');
        if (!empty($notice_message)) {
            // echo '<div style="text-align: center; padding: 10px;">' . esc_html($notice_message) . '</div>';
            // Check if it's the main shop page or a product category page
            // if (is_shop() || is_product_category()) {
  
              echo '<div class="woocommerce-notice" style="background-color: #f9edbe; border: 1px solid #fbeed5; padding: 10px; margin-bottom: 20px; color: #8a6d3b;">';
              echo '<strong>' . esc_html($notice_message) . '</strong>';
              echo '</div>';   
          // esc_html($notice_message)
              // do_action( 'woocommerce_set_cart_cookies',  true );
              // wc_add_notice( 'vai carai' , 'error' );
            // }       
        }
    }
  }
  add_action('woocommerce_before_shop_loop', 'custom_notice_message');


    // MENSAGEM DE AVISO NO TOPO DA PÁGINA
    function custom_notice_message_top() {
        if (get_option('custom_notice_message_top_checkbox')) {
            $notice_message = get_option('custom_notice_message_top');
            if (!empty($notice_message)) {
                // echo '<div style="text-align: center; padding: 10px;">' . esc_html($notice_message) . '</div>';
                // Check if it's the main shop page or a product category page
                // if (is_shop() || is_product_category()) {
      
                  echo '<div class="woocommerce-notice header-notice" style="background-color: #f9edbe; border: 1px solid #fbeed5; padding: 10px; margin-bottom: 20px; color: #8a6d3b;">';
                  echo '<strong>' . esc_html($notice_message) . '</strong>';
                  echo '</div>';   
              // esc_html($notice_message)
                  // do_action( 'woocommerce_set_cart_cookies',  true );
                  // wc_add_notice( 'vai carai' , 'error' );
                // }       
            }
        }
      }
  add_action('wp_head', 'custom_notice_message_top');
  

  function custom_notice_message_styles() {
    echo '<style>
        .header-notice{
            text-align: center;
            font-weight: bold;
            color:#222 !important;
        }</style>';
  }
  add_action( 'wp_head', 'custom_notice_message_styles' );
  

?>