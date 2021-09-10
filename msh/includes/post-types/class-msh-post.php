<?php

class Msh_Post {

    public static function init() {
        add_action( 'init', array( __CLASS__, 'taxonomies' ) );
    }

    public static function taxonomies() {

        /**
         * Register series
         */
        $labels = array(
            'name'              => __( 'Seriály', 'msh' ),
            'singular_name'     => __( 'Seriál', 'msh' ),
            'search_items'      => __( 'Hľadaj seriál', 'msh' ),
            'all_items'         => __( 'Všetky seriály', 'msh' ),
            'edit_item'         => __( 'Upraviť seriál', 'msh' ),
            'update_item'       => __( 'Aktualizovať seriál', 'msh' ),
            'add_new_item'      => __( 'Pridať nový seriál', 'msh' ),
            'new_item_name'     => __( 'Nový seriál', 'msh' ),
            'menu_name'         => __( 'Seriály', 'msh' ),
            'not_found'         => __( 'Žiadne seriály sa nenašli.', 'msh' ),
        );

        register_taxonomy( 'series', array( 'post' ), array(
            'labels'            => $labels,
            'hierarchical'      => false,
            'query_var'         => 'series',
            'public'            => true,
            'show_ui'           => true,
            'show_in_menu'      => true,
            'show_in_nav_menus' => true,
            'show_admin_column' => true,
            'rewrite'           => array( 'slug' => 'serial'),
        ) );

        /**
         * Unregister categories
         */
        register_taxonomy_for_object_type( 'category', 'post' );
    }
}

Msh_Post::init();