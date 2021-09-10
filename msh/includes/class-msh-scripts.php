<?php

class Msh_Scripts {

    public static function init() {
        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_backend' ) );
    }

    public static function enqueue_backend() {
        wp_register_script( 'msh-admin', plugins_url( '/msh/assets/js/msh-admin.js' ), array( 'jquery' ) );
        wp_enqueue_script( 'msh-admin' );
    }
}

Msh_Scripts::init();