<?php

//
// Добавление кнопки "Наверх"
//
//


defined( 'ABSPATH' ) || exit;


if ( mif_wpc_options( 'button-to-top' ) ) 
    add_action( 'wp_print_scripts', 'mif_wpc_button_to_top' );

function mif_wpc_button_to_top()  
{  

    wp_register_script( 'mif-wpc-button-to-top', plugins_url( '../js/button-to-top.js', __FILE__ ) );
    wp_enqueue_script( 'mif-wpc-button-to-top' );

}  



?>
