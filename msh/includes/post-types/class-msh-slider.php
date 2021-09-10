<?php

class Msh_Slider {

    public static function init() {
        add_action( 'init', array( __CLASS__, 'post_type' ) );
        add_filter( 'cmb2_meta_boxes', array( __CLASS__, 'metaboxes' ) );
    }

    public static function post_type() {
        $labels = array(
            'name'               => __( 'Slide', 'msh' ),
            'singular_name'      => __( 'Slide', 'msh' ),
            'add_new'            => __( 'Pridať nový slide', 'msh' ),
            'add_new_item'       => __( 'Pridať nový slide', 'msh' ),
            'edit_item'          => __( 'Upraviť slide', 'msh' ),
            'new_item'           => __( 'Nový slide', 'msh' ),
            'all_items'          => __( 'Všetky slide-y', 'msh' ),
            'view_item'          => __( 'Zobraziť slide', 'msh' ),
            'search_items'       => __( 'Hľadať slide', 'msh' ),
            'not_found'          => __( 'Žiadne slide-y sa nenašli', 'msh' ),
            'not_found_in_trash' => __( 'V koši neboli nájdené žiadne slide-y', 'msh' ),
            'parent_item_colon'  => '',
            'menu_name'          => __( 'Slider', 'msh' ),
        );

        register_post_type( 'slide',
            array(
                'labels'        => $labels,
                'menu_icon'     => 'dashicons-images-alt2',
                'supports'      => array( 'title', 'editor', 'thumbnail'),
                'public'        => true,
                'has_archive'   => false,
                'rewrite'       => array( 'slug' => __( 'slides', 'msh' ) ),
                'menu_position' => 27,
                'show_in_nav_menus' => true,
                'capability_type' => 'page',
            )
        );
    }

    public static function metaboxes(array $metaboxes) {
        $prefix = 'slide_';

        $metaboxes['slide_subtitle'] = array(
            'id'                    => 'slide_subtitle',
            'title'                 => __( 'Podtitulok', 'msh' ),
            'object_types'          => array( 'slide' ),
            'context'               => 'normal',
            'priority'              => 'high',
            'show_names'            => true,
            'fields'                => array(
                array(
                    'name'          => __( 'Podtitulok', 'msh' ),
                    'type'          => 'text',
                    'id'            => $prefix . 'subtitle',
                ),
            ),
        );

        $metaboxes['slide_link'] = array(
            'id'                    => 'slide_link',
            'title'                 => __( 'Link', 'msh' ),
            'object_types'          => array( 'slide' ),
            'context'               => 'normal',
            'priority'              => 'high',
            'show_names'            => true,
            'fields'                => array(
                array(
                    'name'          => __( 'Text', 'msh' ),
                    'type'          => 'text',
                    'id'            => $prefix . 'link_text',
                ),
                array(
                    'name'          => __( 'URL', 'msh' ),
                    'type'          => 'text_url',
                    'id'            => $prefix . 'link_url',
                ),
            ),
        );

        $metaboxes['slide_media'] = array(
            'id'                    => 'slide_media',
            'title'                 => __( 'Média', 'msh' ),
            'object_types'          => array( 'slide' ),
            'context'               => 'normal',
            'priority'              => 'high',
            'show_names'            => true,
            'fields'                => array(
                array(
                    'name'          => __( 'Image', 'msh' ),
                    'type'          => 'file',
                    'id'            => $prefix . 'image',
                ),
                array(
                    'name'          => __( 'Video', 'msh' ),
                    'type'          => 'oembed',
                    'id'            => $prefix . 'video',
                ),
            ),
        );

        return $metaboxes;
    }
}

Msh_Slider::init();