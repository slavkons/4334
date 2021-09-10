<?php
/*
Plugin Name: 4334 plugin morä
Description: Venované Matejovi Hudáčkovi.
Version: 1.0.1
Author: pavolkovalik, slavomirdzubak
*/

final class Msh {

    public function __construct() {
        $this->constants();
        $this->includes();
    }

    public function constants() {
        define( 'MSH_DIR', plugin_dir_path( __FILE__ ) );
        define( 'MSH_URL', plugin_dir_url( __FILE__ ) );
    }

    public function includes() {
        require_once MSH_DIR . 'includes/class-msh-scripts.php';
//        require_once MSH_DIR . 'includes/class-msh-import-export.php';
        require_once MSH_DIR . 'includes/class-msh-post-types.php';
        require_once MSH_DIR . 'includes/class-msh-post-connections.php';
        require_once MSH_DIR . 'includes/class-msh-shortcodes.php';
        require_once MSH_DIR . 'includes/class-msh-query.php';
        require_once MSH_DIR . 'includes/class-msh-utils.php';
        require_once MSH_DIR . 'includes/class-msh-tinymce-buttons.php';
    }
}

new Msh();