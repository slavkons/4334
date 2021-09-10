<?php

class Msh_Album {

    public static function init() {
        add_action( 'init', array( __CLASS__, 'post_type' ) );
        add_filter( 'cmb2_meta_boxes', array( __CLASS__, 'metaboxes' ) );
    }

    public static function post_type() {
        $labels = array(
            'name'               => __( 'Albumy', 'msh' ),
            'singular_name'      => __( 'Album', 'msh' ),
            'add_new'            => __( 'Pridať nový album', 'msh' ),
            'add_new_item'       => __( 'Pridať nový album', 'msh' ),
            'edit_item'          => __( 'Upraviť album', 'msh' ),
            'new_item'           => __( 'Nový album', 'msh' ),
            'all_items'          => __( 'Všetky albumy', 'msh' ),
            'view_item'          => __( 'Zobraziť album', 'msh' ),
            'search_items'       => __( 'Hľadať album', 'msh' ),
            'not_found'          => __( 'Žiadne albumy sa nenašli', 'msh' ),
            'not_found_in_trash' => __( 'V koši neboli nájdené žiadne albumy', 'msh' ),
            'parent_item_colon'  => '',
            'menu_name'          => __( 'Albumy', 'msh' ),
        );

        register_post_type( 'album',
            array(
                'labels'        => $labels,
                'menu_icon'     => 'dashicons-album',
                'supports'      => array( 'title', 'thumbnail', 'editor'),
                'public'        => true,
                'has_archive'   => true,
                'rewrite'       => array( 'slug' => 'albumy', 'with_front' => false ),
                'menu_position' => 29,
                'show_in_nav_menus' => true,
                'capability_type' => 'page',
                'show_in_rest' => true,
            )
        );
    }

    public static function metaboxes(array $metaboxes) {
        $prefix = 'album_';

        $metaboxes['album_info'] = array(
            'id'                    => 'album_info',
            'title'                 => __( 'Info', 'msh' ),
            'object_types'          => array( 'album' ),
            'context'               => 'normal',
            'priority'              => 'high',
            'show_names'            => true,
            'fields'                => array(
                array(
                    'name'          => __( 'Rok vydania', 'msh' ),
                    'type'          => 'text',
                    'id'            => $prefix . 'year',
                    'attributes'    => array(
                        'type'  => 'number',
                    ),
                ),
                array(
                    'name'          => __( 'Dĺžka (v minútach)', 'msh' ),
                    'type'          => 'text',
                    'id'            => $prefix . 'duration',
                    'attributes'    => array(
                        'type'  => 'number',
                    ),
                ),
                array(
                    'name'          => __( 'Alternatívne meno umelca', 'msh' ),
                    'type'          => 'text',
                    'id'            => $prefix . 'artist_alt',
                ),
            ),
        );

//        $metaboxes['album_media'] = array(
//            'id'                    => 'album_media',
//            'title'                 => __( 'Média', 'msh' ),
//            'object_types'          => array( 'album' ),
//            'context'               => 'normal',
//            'priority'              => 'high',
//            'show_names'            => true,
//            'fields'                => array(
//                array(
//                    'name'          => __( 'Video', 'msh' ),
//                    'type'          => 'oembed',
//                    'id'            => $prefix . 'video',
//                ),
//            ),
//        );

        return $metaboxes;
    }
}

function my_rest_prepare_album( $data, $album, $request ) {
    $_data = $data->data;

    $params = $request->get_params();
    if ( ! isset( $params['id'] ) ) {
        unset( $_data['yoast_head'] );
    }

    $data->data = $_data;

    return $data;
}
add_filter( 'rest_prepare_album', 'my_rest_prepare_album', 10, 3 );

Msh_Album::init();