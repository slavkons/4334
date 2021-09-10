<?php

class Msh_Artist {

    public static function init() {
        add_action( 'init', array( __CLASS__, 'post_type' ) );
        add_filter( 'cmb2_meta_boxes', array( __CLASS__, 'metaboxes' ) );
    }

    public static function post_type() {
        $labels = array(
            'name'               => __( 'Umelci', 'msh' ),
            'singular_name'      => __( 'Umelec', 'msh' ),
            'add_new'            => __( 'Pridať nového umelca', 'msh' ),
            'add_new_item'       => __( 'Pridať nového umelca', 'msh' ),
            'edit_item'          => __( 'Upraviť umelca', 'msh' ),
            'new_item'           => __( 'Nový umelec', 'msh' ),
            'all_items'          => __( 'Všetci umelci', 'msh' ),
            'view_item'          => __( 'Zobraziť umelca', 'msh' ),
            'search_items'       => __( 'Hľadať umelca', 'msh' ),
            'not_found'          => __( 'Žiadni umelci sa nenašli', 'msh' ),
            'not_found_in_trash' => __( 'V koši neboli nájdení žiadni umelci', 'msh' ),
            'parent_item_colon'  => '',
            'menu_name'          => __( 'Umelci', 'msh' ),
        );

        register_post_type( 'artist', array(
                'labels'        => $labels,
                'menu_icon'     => 'dashicons-groups',
                'supports'      => array( 'title', 'thumbnail', 'editor'),
                'public'        => true,
                'has_archive'   => true,
                'rewrite'       => array( 'slug' => 'umelci', 'with_front' => false ),
                'menu_position' => 28,
                'show_in_nav_menus' => true,
                'capability_type' => 'page',
                'show_in_rest' => true,
            )
        );
    }

    public static function metaboxes(array $metaboxes) {
        $prefix = 'artist_';

        $metaboxes['contact'] = array(
            'id'                    => 'contact',
            'title'                 => __( 'Kontakt', '4334' ),
            'object_types'          => array( 'artist' ),
            'context'               => 'normal',
            'priority'              => 'high',
            'show_names'            => true,
            'fields'                => array(
                array(
                    'name'          => __( 'Youtube', '4334' ),
                    'type'          => 'text_url',
                    'id'            => $prefix . 'youtube',
                ),
                array(
                    'name'          => __( 'Spotify', '4334' ),
                    'type'          => 'text_url',
                    'id'            => $prefix . 'spotify',
                ),
                array(
                    'name'          => __( 'Bandcamp', '4334' ),
                    'type'          => 'text_url',
                    'id'            => $prefix . 'bandcamp',
                ),
                array(
                    'name'          => __( 'SoundCloud', '4334' ),
                    'type'          => 'text_url',
                    'id'            => $prefix . 'soundcloud',
                ),
                array(
                    'name'          => __( 'Facebook', '4334' ),
                    'type'          => 'text_url',
                    'id'            => $prefix . 'facebook',
                ),
                array(
                    'name'          => __( 'Instagram', '4334' ),
                    'type'          => 'text_url',
                    'id'            => $prefix . 'instagram',
                ),
                array(
                    'name'          => __( 'Website', '4334' ),
                    'type'          => 'text_url',
                    'id'            => $prefix . 'website',
                ),
                array(
                    'name'          => __( 'E-mail', '4334' ),
                    'type'          => 'text_email',
                    'id'            => $prefix . 'email',
                ),
                array(
                    'name'          => __( 'Tel. č.', '4334' ),
                    'type'          => 'text_medium',
                    'id'            => $prefix . 'phone',
                    'attributes'    => array(
                        'type'  => 'tel',
                    ),
                ),
            ),
        );

        $metaboxes['members'] = array(
            'id'                    => 'members',
            'title'                 => __( 'Členovia', '4334' ),
            'object_types'          => array( 'artist' ),
            'context'               => 'normal',
            'priority'              => 'high',
            'show_names'            => true,
            'fields'                => array(
                array(
                    'id'                => $prefix . 'member_group',
                    'type'              => 'group',
                    'options'           => array(
                        'add_button'        => __( 'Pridať člena', '4334' ),
                        'remove_button'     => __( 'Odstrániť člena', '4334' ),
                        'sortable'          => true, // beta
                    ),
                    'fields'            => array(
                        array(
                            'id'                => $prefix . 'member_name',
                            'name'              => __( 'Meno', 'realia' ),
                            'type'              => 'text',
                        ),
                        array(
                            'id'                => $prefix . 'member_position',
                            'name'              => __( 'Nástroj', 'realia' ),
                            'type'              => 'text',
                        ),
                    ),
                ),
            ),
        );

        return $metaboxes;
    }
}
function my_rest_prepare_artist( $data, $artist, $request ) {
    $_data = $data->data;

    $params = $request->get_params();
    if ( ! isset( $params['id'] ) ) {
        unset( $_data['yoast_head'] );
    }

    $data->data = $_data;

    return $data;
}
add_filter( 'rest_prepare_artist', 'my_rest_prepare_artist', 10, 3 );
Msh_Artist::init();