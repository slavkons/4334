<?php

class Msh_Chord {

    public static function init() {
        add_action( 'init', array( __CLASS__, 'post_type' ) );
//        add_filter( 'cmb2_meta_boxes', array( __CLASS__, 'metaboxes' ) );
    }

    public static function post_type() {
        $labels = array(
            'name'               => __( 'Akordy', 'msh' ),
            'singular_name'      => __( 'Akord', 'msh' ),
            'add_new'            => __( 'Pridať nový akord', 'msh' ),
            'add_new_item'       => __( 'Pridať nový akord', 'msh' ),
            'edit_item'          => __( 'Upraviť akord', 'msh' ),
            'new_item'           => __( 'Nový akord', 'msh' ),
            'all_items'          => __( 'Všetky akordy', 'msh' ),
            'view_item'          => __( 'Zobraziť akord', 'msh' ),
            'search_items'       => __( 'Hľadať akordy', 'msh' ),
            'not_found'          => __( 'Žiadne akordy sa nenašli', 'msh' ),
            'parent_item_colon'  => '',
            'menu_name'          => __( 'Akordy', 'msh' ),
        );

        register_post_type( 'chord',
            array(
                'labels'        => $labels,
                'menu_icon'     => 'dashicons-playlist-audio',
                'supports'      => array( 'title', 'thumbnail'),
                'public'        => true,
                'has_archive'   => false,
                'menu_position' => 31,
                'capability_type' => 'page',
            )
        );
    }

    public static function metaboxes(array $metaboxes) {
        // TODO: chord metaboxes
    }
}

Msh_Chord::init();