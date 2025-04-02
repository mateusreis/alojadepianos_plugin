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
              echo '<div class="info-destaque">';
              echo '<div class="info-texto">';
              echo '<strong>'.esc_html($notice_message).'</strong>';
              echo '</div>';
              echo '</div>';
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
        
                echo '<div class="info-destaque">';   
                //  echo '<a href="https://www.classickeyboards.com.br/">';   
                //  echo '<span class="icone-place">';   
                //  echo '<svg version="1.0" viewBox="0 0 901.000000 900.000000" xmlns="http://www.w3.org/2000/svg"><g transform="translate(0 900) scale(.1 -.1)"><path d="m4265 8986c-216-42-357-99-536-217-83-54-204-172-1001-968-500-498-908-909-908-913 0-5 92-8 204-8 288 0 449-33 646-132 186-94 194-101 985-889 413-411 766-755 785-765 49-26 181-26 230 0 19 10 368 350 775 755 642 640 752 746 834 800 241 159 431 218 735 228l178 6-888 889c-489 489-920 913-959 943-157 121-324 202-519 252-88 22-127 26-296 29-136 2-216-1-265-10z"></path><path d="m861 5935c-485-487-577-584-630-664-102-154-154-272-199-450-25-99-27-121-27-321 0-191 3-225 24-310 49-199 134-377 256-535 30-38 299-315 598-614l544-543 359 1c403 2 467 8 607 55 204 69 179 48 1022 887 759 756 760 756 845 798 197 96 409 94 603-5 66-35 130-95 812-774 446-444 766-756 807-784 79-56 187-107 289-137 68-20 102-23 419-29 190-3 352-9 360-13 11-5 167 145 586 565 488 490 580 587 633 667 34 52 73 116 87 142 195 385 195 873 0 1258-14 26-53 90-87 142-53 80-145 177-633 667-419 420-575 570-586 565-8-4-170-10-360-13-317-6-351-9-419-29-102-30-210-81-289-137-40-28-364-343-812-789-802-798-778-776-930-823-175-53-368-27-525 70-29 18-367 347-805 782-837 833-813 812-1017 882-136 46-219 54-616 56l-348 3-568-570z"></path><path d="m4465 3913c-47-14-101-65-805-767-796-793-804-800-990-894-196-99-359-132-647-132-112 0-203-3-203-8 0-4 408-415 908-913 797-796 918-914 1001-968 154-102 272-154 450-199 99-25 121-27 321-27 191 0 225 3 310 24 199 49 377 134 535 256 39 30 470 454 959 943l888 889-178 6c-304 10-494 69-735 228-82 54-193 161-839 805-722 719-747 743-799 758-67 19-110 19-176-1z"></path></g></svg>';   
                //  echo '</span>';   
                 echo '<div class="info-texto">';
                 echo '<strong>'.$notice_message.'</strong>';
                 echo '</div>';
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