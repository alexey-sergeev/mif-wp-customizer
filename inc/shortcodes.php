<?php

//
// Shortcodes
//
//


defined( 'ABSPATH' ) || exit;

if ( mif_wpc_options( 'mif-wpc-shortcodes' ) ) {

    add_shortcode( 'redirect', 'mif_wpc_shortcode_redirect' );

}

//
// redirect
// [redirect url='http://yandex.ru' sec='5']
//

function mif_wpc_shortcode_redirect( $atts )
{
    $out = '';

	$url = ( isset( $atts['url'] ) && ! empty( $atts['url'] ) ) ? esc_url( $atts['url'] ) : '';
	$sec = ( isset( $atts['sec'] ) && ! empty( $atts['sec'] ) ) ? esc_attr( $atts['sec'] ) : '0';

	if( ! empty( $url ) ) $out = '<meta http-equiv="refresh" content="' . $sec . '; url=' . $url . '">';

	return $out;
}



?>