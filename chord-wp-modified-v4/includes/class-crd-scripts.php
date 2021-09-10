<?php

class CRD_Scripts {

    public static function register() {
        add_action ( 'wp_enqueue_scripts', array ( 'CRD_Scripts', 'load_scripts' ) );
    }

    public static function load_scripts () {
        $source = ChordWP::plugin_url() . 'assets/js/chordwp.js?v=142';
        
        wp_register_script( 'chordwp_scripts', $source, array( 'jquery' ) );
        wp_enqueue_script ( 'chordwp_scripts' );
    }

}
