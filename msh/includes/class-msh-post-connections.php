<?php

class Msh_Post_Connections {

    public static function init() {
        add_action( 'p2p_init', array( __CLASS__, 'post_connections' ) );
    }

    public static function post_connections() {
        p2p_register_connection_type( array(
            'name' => 'artist_to_song',
            'from' => 'artist',
            'to' => 'song',
            'sortable' => 'any',
            'cardinality' => 'one-to-many',
            'admin_column' => 'to',
//        'fields' => array(
//            'role' => array(
//                'title' => 'Role',
//                'type' => 'select',
//                'values' => array( 'main', 'featuring'),
//                'default' => 'main',
//            ),
//        ),
        ) );

        p2p_register_connection_type( array(
            'name' => 'album_to_song',
            'from' => 'album',
            'to' => 'song',
            'sortable' => 'any',
            'cardinality' => 'one-to-many',
            'admin_column' => 'to',
        ) );
    }
}

Msh_Post_Connections::init();