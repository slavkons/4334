<?php

class Msh_Shortcodes {

    public static function init() {
        add_shortcode( 'chord', array( __CLASS__, 'shortcode_chord' ) );
    }

    public static function shortcode_chord( $atts ) {
        $chord_id = $atts['id'];
        $chord_title = get_the_title( $chord_id );

        return '<span class="chord">' . $chord_title . '</span>';
    }
}

Msh_Shortcodes::init();