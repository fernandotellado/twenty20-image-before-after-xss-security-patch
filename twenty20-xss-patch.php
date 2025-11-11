<?php
/**
 * Plugin Name: Twenty20 XSS Patch
 * Plugin URI:  https://mantenimiento.ayudawp.com
 * Description: Parche temporal para la vulnerabilidad del plugin Twenty20 Image Before-After <= 2.0.4 para sanear los atributos del shortcode y evitar ataques XSS.
 * Version:     1.0.2
 * Author:      Fernando Tellado
 * Author URI:  https://ayudawp.com
 * License:     GPLv2 or later
 * Text Domain: twenty20-xss-patch
 */

// Salir si se accede directamente.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Función para sanear los atributos del shortcode Twenty20
add_filter( 'shortcode_atts_twenty20', function( $out, $pairs, $atts ) {

    // img1 / img2 pueden ser IDs o URLs
    foreach ( ['img1','img2'] as $key ) {
        if ( isset( $out[$key] ) ) {
            if ( is_numeric( $out[$key] ) ) {
                $out[$key] = intval( $out[$key] );
            } else {
                $out[$key] = esc_url_raw( $out[$key] );
            }
        }
    }

    if ( isset( $out['offset'] ) ) {
        $out['offset'] = floatval( $out['offset'] );
    }
    if ( isset( $out['orientation'] ) && ! in_array( $out['orientation'], array( 'horizontal','vertical' ), true ) ) {
        $out['orientation'] = 'horizontal';
    }
    if ( isset( $out['align'] ) ) {
        $out['align'] = sanitize_html_class( $out['align'] );
    }

    foreach ( array( 'move_slider_on_hover', 'move_with_handle_only', 'click_to_move' ) as $flag ) {
        if ( isset( $out[ $flag ] ) ) {
            $out[ $flag ] = ( $out[ $flag ] === 'true' ) ? 'true' : 'false';
        }
    }

    // before, after, width → se dejarán escapar por el plugin original
    return $out;

}, 10, 3 );