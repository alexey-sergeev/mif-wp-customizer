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
include_once dirname( __FILE__ ) . '/inc/button-to-top.php';
include_once dirname( __FILE__ ) . '/inc/admin-settings-page.php';
include_once dirname( __FILE__ ) . '/inc/join-to-multisite.php';
include_once dirname( __FILE__ ) . '/inc/shortcodes.php';
// include_once dirname( __FILE__ ) . '/inc/cyrillic-to-latin.php';





// 
// Проверка опций
// 
// 

function mif_wpc_options( $key )
{
    $ret = false;
    $args = get_mif_wpc_options();

    if ( isset( $args[$key] ) ) $ret = $args[$key];

    return $ret;
}  

// 
// Получить опции
// 
// 

function get_mif_wpc_options()
{
    $default = array(
                'button-to-top' => false,
                'login-logout-menu' => true,
                'login-logout-widget' => true,
                'join-to-multisite' => true,
                'mif-wpc-shortcodes' => true,
                'join-to-multisite-default-role' => 'subscriber',
                'join-to-multisite-mode' => 'manual',
            );

    foreach ( $default as $key => $value ) $args[$key] = get_option( $key, $default[$key] );

    if ( ! is_multisite() ) {
        $args['join-to-multisite'] = false;
    }

    return $args;
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

	wp_register_style( 'font-awesome', plugins_url( '/css/font-awesome.min.css', __FILE__ ) );
	wp_enqueue_style( 'font-awesome' );
}



?>
