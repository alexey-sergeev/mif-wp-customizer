<?php
/*
 * Plugin Name: MIF WP Customizer
 * Description: Плагин дополнительной настройки WordPress для создания сайта социальной сети.
 * Plugin URI:  https://github.com/alexey-sergeev/mif-bp-customizer
 * Author:      Alexey Sergeev
 * Author URI:  https://github.com/alexey-sergeev
 * Version:     1.0.0
 * Text Domain: mif-wpc
 * Domain Path: /lang/
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

defined( 'ABSPATH' ) || exit;

include_once dirname( __FILE__ ) . '/inc/login-logout-menu.php';
include_once dirname( __FILE__ ) . '/inc/button-to-top.php';
include_once dirname( __FILE__ ) . '/inc/admin-settings-page.php';
include_once dirname( __FILE__ ) . '/inc/join-to-multisite.php';
include_once dirname( __FILE__ ) . '/inc/shortcodes.php';
include_once dirname( __FILE__ ) . '/inc/disable-admin-bar.php';
include_once dirname( __FILE__ ) . '/inc/login-logout-widget.php';


// Подключение языкового файла

load_plugin_textdomain( 'mif-wpc', false, basename( dirname( __FILE__ ) ) . '/lang' );



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
                'join-to-multisite' => true,
                'mif-wpc-shortcodes' => true,
                'mif-wpc-mime-types' => true,
                'login-logout-widget' => true,
                'disable-admin-bar' => true,
                // 'members-widget' => true,
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
	wp_register_style( 'mif-wpc-styles', plugins_url( 'mif-wpc-styles.css', __FILE__ ) );
	wp_enqueue_style( 'mif-wpc-styles' );

	wp_register_style( 'font-awesome', plugins_url( '/css/font-awesome.min.css', __FILE__ ) );
	wp_enqueue_style( 'font-awesome' );
}



//
// Проверка наличия BuddyPress
//
//

if ( ! function_exists('is_active_buddypress') ) {

    function is_active_buddypress()
    {


        if ( function_exists('bp_get_version') ) {
            return true;
        } else {
            return false;
        }
    }

}


?>