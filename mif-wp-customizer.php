<?php
/*
 * Plugin Name: MIF WP Customizer
 * Plugin URI:  https://github.com/alexey-sergeev/mif-bp-customizer
 * Author:      Alexey Sergeev
 * Author URI:  https://github.com/alexey-sergeev
 * License:     MIT License
 * Description: Плагин WordPress для тонкой настройки сайтов.
 * Version:     0.0.1
 * Text Domain: mif-wp-customizer
 * Domain Path: /lang/
 */

defined( 'ABSPATH' ) || exit;

include_once dirname( __FILE__ ) . '/inc/login-logout-menu.php';
include_once dirname( __FILE__ ) . '/inc/login-logout-widget.php';
// include_once dirname( __FILE__ ) . '/inc/cyrillic-to-latin.php';





// 
// Настройка опций
// 
// 

function mif_wpc_options( $key )
{
    
    switch ( $key ) {
        case 'mif_wpc_login_logout_menu':
            $ret = true;
            break;
        case 'mif_wpc_user_login_widget':
            $ret = true;
            break;
        // case 'mif_wpc_cyrillic_to_latin':
        //     $ret = true;
        //     break;
        default:
            $ret = false;
            break;
    }

    return $ret;
}  


//
// Подключаем свой файл CSS
//
//

add_action( 'wp_enqueue_scripts', 'mif_wp_customizer_styles' );

function mif_wp_customizer_styles() 
{
	wp_register_style( 'mif-wp-customizer-styles', plugins_url( 'mif-wp-customizer-styles.css', __FILE__ ) );
	wp_enqueue_style( 'mif-wp-customizer-styles' );
}



?>
