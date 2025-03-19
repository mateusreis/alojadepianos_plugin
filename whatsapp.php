<?php


/**
 * Adiciona um botao de whatsapp.
 */
function whatsapp_sob_encomenda_botao() {
    global $product;
  
    if ( ! $product ) {
        return;
    }
  
    $preco = $product->get_price();
  
    if ( empty( $preco ) || $preco == 0 ) {
  
        // numero do whatsapp
        $numero_whatsapp = esc_attr(get_option('custom_whatsapp_phonenumber')); // pega do setup
        if ( empty( $numero_whatsapp ) ) {
            return;
        }
  
        // texto do botao whatsapp
        $texto_botao_whatsapp = esc_attr(get_option('custom_whatsapp_texto_botao')); // pega do setup 
        if ( empty( $texto_botao_whatsapp ) ) {
          $texto_botao_whatsapp = 'Gostaria de encomendar este produto';
        }
  
        // texto da mensagem whatsapp, antes do produto
        $texto_mensagem_whatsapp = esc_attr(get_option('custom_whatsapp_texto_mensagem')); // pega do setup 
        if ( empty( $texto_mensagem_whatsapp ) ) {
          $texto_mensagem_whatsapp = 'Gostaria de encomendar este produto';
        }      
        
        // mensagem whatsapp
        $product_name = $product->get_name();
        $texto_mensagem_whatsapp = urlencode( $texto_mensagem_whatsapp . ': ' . $product_name . '.' );   
        $link_whatsapp = 'https://wa.me/' . $numero_whatsapp . '?text=' . $texto_mensagem_whatsapp;
  
        // botao
        echo '<div class="whatsapp-sob-encomenda">';
        echo '<a href="' . $link_whatsapp . '" target="_blank" role="button" class="whatsapp-sob-encomenda-botao">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="svg_whatsapp" x="0px" y="0px" width="25px" height="25px" viewBox="0 0 90 90" style="enable-background:new 0 0 90 90;" xml:space="preserve"><path id="WhatsApp" d="M90,43.841c0,24.213-19.779,43.841-44.182,43.841c-7.747,0-15.025-1.98-21.357-5.455L0,90l7.975-23.522 c-4.023-6.606-6.34-14.354-6.34-22.637C1.635,19.628,21.416,0,45.818,0C70.223,0,90,19.628,90,43.841z M45.818,6.982 c-20.484,0-37.146,16.535-37.146,36.859c0,8.065,2.629,15.534,7.076,21.61L11.107,79.14l14.275-4.537 c5.865,3.851,12.891,6.097,20.437,6.097c20.481,0,37.146-16.533,37.146-36.857S66.301,6.982,45.818,6.982z M68.129,53.938 c-0.273-0.447-0.994-0.717-2.076-1.254c-1.084-0.537-6.41-3.138-7.4-3.495c-0.993-0.358-1.717-0.538-2.438,0.537 c-0.721,1.076-2.797,3.495-3.43,4.212c-0.632,0.719-1.263,0.809-2.347,0.271c-1.082-0.537-4.571-1.673-8.708-5.333 c-3.219-2.848-5.393-6.364-6.025-7.441c-0.631-1.075-0.066-1.656,0.475-2.191c0.488-0.482,1.084-1.255,1.625-1.882 c0.543-0.628,0.723-1.075,1.082-1.793c0.363-0.717,0.182-1.344-0.09-1.883c-0.27-0.537-2.438-5.825-3.34-7.977 c-0.902-2.15-1.803-1.792-2.436-1.792c-0.631,0-1.354-0.09-2.076-0.09c-0.722,0-1.896,0.269-2.889,1.344 c-0.992,1.076-3.789,3.676-3.789,8.963c0,5.288,3.879,10.397,4.422,11.113c0.541,0.716,7.49,11.92,18.5,16.223 C58.2,65.771,58.2,64.336,60.186,64.156c1.984-0.179,6.406-2.599,7.312-5.107C68.398,56.537,68.398,54.386,68.129,53.938z" fill="#FFFFFF"></path></svg>';
        echo $texto_botao_whatsapp;
        echo '</a>';
        echo '</div>';
    }
  }
  add_action( 'woocommerce_single_product_summary', 'whatsapp_sob_encomenda_botao', 30 ); // Adiciona o botão após o preço
  
  function whatsapp_sob_encomenda_estilos() {
    echo '<style>
        .whatsapp-sob-encomenda{
            display: block;
            margin-top: 20px;
        }
            
        .whatsapp-sob-encomenda .whatsapp-sob-encomenda-botao {
            align-items: center;
            background: #25d366;
            border-radius: 5px;
            box-shadow: 0 2px 2px 0 rgba(45, 62, 79, .3) !important;
            color: #fff;
            display: inline-flex;
            font-size: 16px;
            padding: 12px 30px;
            position: relative;
            transition: all .3s !important;
            width: auto;
            text-decoration: none;
        }
              
        #svg_whatsapp {
            margin-right: 10px !important;
        }
  
  
    </style>';
  }
  add_action( 'wp_head', 'whatsapp_sob_encomenda_estilos' );
  

?>