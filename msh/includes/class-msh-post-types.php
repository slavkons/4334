<?php

class Msh_Post_Types {

    public static function init() {
        self::post_types();
    }

    public static function post_types() {
        require_once MSH_DIR . 'includes/post-types/class-msh-album.php';
        require_once MSH_DIR . 'includes/post-types/class-msh-artist.php';
        require_once MSH_DIR . 'includes/post-types/class-msh-song.php';
        require_once MSH_DIR . 'includes/post-types/class-msh-chord.php';
        require_once MSH_DIR . 'includes/post-types/class-msh-slider.php';
        require_once MSH_DIR . 'includes/post-types/class-msh-post.php';
    }
}

Msh_Post_Types::init();