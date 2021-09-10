<?php

class Msh_Song {

    public static function init() {
        add_action( 'init', array( __CLASS__, 'taxonomies' ) );
        add_action( 'init', array( __CLASS__, 'post_type' ) );
        add_filter( 'cmb2_meta_boxes', array( __CLASS__, 'metaboxes' ) );
//        add_action( 'wp_insert_post', array( __CLASS__, 'after_song_save' ) );
    }

    public static function post_type() {
        $labels = array(
            'name'               => __( 'Piesne', 'msh' ),
            'singular_name'      => __( 'Pieseň', 'msh' ),
            'add_new'            => __( 'Pridať novú pieseň', 'msh' ),
            'add_new_item'       => __( 'Pridať novú pieseň', 'msh' ),
            'edit_item'          => __( 'Upraviť pieseň', 'msh' ),
            'new_item'           => __( 'Nová pieseň', 'msh' ),
            'all_items'          => __( 'Všetky piesne', 'msh' ),
            'view_item'          => __( 'Zobraziť pieseň', 'msh' ),
            'search_items'       => __( 'Hľadať pieseň', 'msh' ),
            'not_found'          => __( 'Žiadne piesne sa nenašli', 'msh' ),
            'not_found_in_trash' => __( 'V koši neboli nájdené žiadne piesne', 'msh' ),
            'parent_item_colon'  => '',
            'menu_name'          => __( 'Piesne', 'msh' ),
        );

        register_post_type( 'song',
            array(
                'labels'        => $labels,
                'menu_icon'     => 'dashicons-format-audio',
                'supports'      => array( 'title', 'editor'),
                'public'        => true,
                'has_archive'   => true,
                'rewrite'       => array( 'slug' => 'piesne', 'with_front' => false ),
                'menu_position' => 29,
                'show_in_nav_menus' => true,
                'capability_type' => 'page',
                'show_in_rest' => true,
            )
        );
    }

    public static function taxonomies() {
        $labels = array(
            'name'              => __( 'Téma', 'msh' ),
            'singular_name'     => __( 'Téma', 'msh' ),
            'search_items'      => __( 'Hľadaj témy', 'msh' ),
            'all_items'         => __( 'Všetky témy', 'msh' ),
            'parent_item'       => __( 'Nadradená téma', 'msh' ),
            'parent_item_colon' => __( 'Nadradená témy:', 'msh' ),
            'edit_item'         => __( 'Upraviť tému', 'msh' ),
            'update_item'       => __( 'Aktualizovať tému', 'msh' ),
            'add_new_item'      => __( 'Pridať novú tému', 'msh' ),
            'new_item_name'     => __( 'Nová téma', 'msh' ),
            'menu_name'         => __( 'Témy', 'msh' ),
            'not_found'         => __( 'Žiadne témy sa nenašli.', 'msh' ),
        );

        register_taxonomy( 'song_theme', array( 'song' ), array(
            'labels'            => $labels,
            'hierarchical'      => false,
            'query_var'         => 'song_theme',
            'public'            => false,
            'show_ui'           => true,
            'show_in_menu'      => true,
            'show_in_nav_menus' => true,
            'show_admin_column' => true,
        ) );
    }

    public static function metaboxes(array $metaboxes) {
        $prefix = 'song_';

        $metaboxes['song_info'] = array(
            'id'                    => 'song_info',
            'title'                 => __( 'Metadáta', 'msh' ),
            'object_types'          => array( 'song' ),
            'context'               => 'normal',
            'priority'              => 'high',
            'show_names'            => true,
            'fields'                => array(
                array(
                    'name'          => __( 'Detail piesne', 'msh' ),
                    'id'            => $prefix . 'detail',
                    'type'             => 'select',
                    'show_option_none' => false,
                    'default'          => 'enable',
                    'options'          => array(
                        'enable'   => __( 'Povoliť', 'msh' ),
                        'disable' => __( 'Zakázať', 'msh' ),
                    ),
                    'description'      => __( 'Pre inštrumentálky, intrá a ine glupoty daj "Zakázať"', 'msh' ),
                ),
                array(
                    'name'          => __( 'Originál', 'msh' ),
                    'type'          => 'text',
                    'id'            => $prefix . 'original',
                ),
                array(
                    'name'          => __( 'Text', 'msh' ),
                    'type'          => 'text',
                    'id'            => $prefix . 'text',
                ),
                array(
                    'name'          => __( 'Preklad', 'msh' ),
                    'type'          => 'text',
                    'id'            => $prefix . 'translation',
                ),
                array(
                    'name'          => __( 'Referencia', 'msh' ),
                    'type'          => 'text',
                    'id'            => $prefix . 'bible_ref',
                ),
            ),
        );

        $metaboxes['song_music_info'] = array(
            'id'                    => 'song_music_info',
            'title'                 => __( 'Metadáta pre hudobníkov', 'msh' ),
            'object_types'          => array( 'song' ),
            'context'               => 'normal',
            'priority'              => 'high',
            'show_names'            => true,
            'fields'                => array(
                array(
                    'name'          => __( 'Tónina', 'msh' ),
                    'id'            => $prefix . 'key',
                    'type'          => 'text',
                ),
                array(
                    'name'          => __( 'Tempo (BPM)', 'msh' ),
                    'type'          => 'text',
                    'id'            => $prefix . 'tempo',
                ),
                array(
                    'name'          => __( 'Capo', 'msh' ),
                    'type'          => 'text',
                    'id'            => $prefix . 'capo',
                ),
            ),
        );

        $metaboxes['song_media'] = array(
            'id'                    => 'song_media',
            'title'                 => __( 'Média', 'msh' ),
            'object_types'          => array( 'song' ),
            'context'               => 'normal',
            'priority'              => 'high',
            'show_names'            => true,
            'fields'                => array(
                array(
                    'name'          => __( 'Video', 'msh' ),
                    'type'          => 'oembed',
                    'desc'          => 'Enter a youtube URL.',
                    'id'            => $prefix . 'video',
                ),
                array(
                    'name'          => __( 'Spotify', 'msh' ),
                    'type'          => 'oembed',
                    'id'            => $prefix . 'spotify',
                ),
                array(
                    'name'          => __( 'Bandcamp', 'msh' ),
                    'type'          => 'text',
                    'id'            => $prefix . 'bandcamp',
                    'sanitization_cb' => 'my_sanitization_func',
                ),
            ),
        );

        return $metaboxes;
    }

//    public static function after_song_save($song_id)
//    {
//        if ( '' == get_post_meta( $song_id, 'song_year', true ) ) {
//            $song_album_id = Msh_Query::get_song_album($song_id);
//
//            if ( $song_album_id ) {
//                $album_year = get_post_meta( $song_album_id, 'album_year', true );
//
//                update_post_meta($song_id, 'song_year', $album_year);
//            }
//        }
//    }
}

function my_sanitization_func( $original_value, $args, $cmb2_field ) {
    return $original_value; // Unsanitized value.
}

function song_get_artist_cb($object, $field_name, $request){
        $song_album_id = Msh_Query::get_song_album($object['id']);
        return $song_album_id;
        //return get_post_meta($object['id'], $field_name, true); 
}

function song_update_artist_cb($value, $object, $field_name){
  return update_post_meta($object['id'], $field_name, $value); 
}

add_action('rest_api_init', function(){

  register_rest_field('song', 'album', 
    array(
    'get_callback' => 'song_get_artist_cb', 
    'update_callback' => 'song_update_artist_cb', 
    'schema' => null

    )
  ); 
});

function song_get_chordpro_cb($object, $field_name, $request){
        return get_the_content( $object['id'] );
        //return get_post_meta($object['id'], $field_name, true); 
}

function song_update_chordpro_cb($value, $object, $field_name){
  return update_post_meta($object['id'], $field_name, $value); 
}

add_action('rest_api_init', function(){

  register_rest_field('song', 'chordpro', 
    array(
    'get_callback' => 'song_get_chordpro_cb', 
    'update_callback' => 'song_update_chordpro_cb', 
    'schema' => null

    )
  ); 
});

function song_get_album_cb($object, $field_name, $request){
        $artist_id = Msh_Query::get_album_artist($object['id']);
        return $artist_id;
        //return get_post_meta($object['id'], $field_name, true); 
}

function song_update_album_cb($value, $object, $field_name){
  return update_post_meta($object['id'], $field_name, $value); 
}

add_action('rest_api_init', function(){

  register_rest_field('album', 'artist', 
    array(
    'get_callback' => 'song_get_album_cb', 
    'update_callback' => 'song_update_album_cb', 
    'schema' => null

    )
  ); 
});

function song_get_original_cb($object, $field_name, $request){
        $original = get_post_meta( get_the_ID(), 'song_original', true );
        return $original;
        //return get_post_meta($object['id'], $field_name, true); 
}

function song_update_original_cb($value, $object, $field_name){
  return update_post_meta($object['id'], $field_name, $value); 
}

add_action('rest_api_init', function(){

  register_rest_field('song', 'original', 
    array(
    'get_callback' => 'song_get_original_cb', 
    'update_callback' => 'song_update_original_cb', 
    'schema' => null

    )
  ); 
});

function song_get_text_cb($object, $field_name, $request){
        $text = get_post_meta( get_the_ID(), 'song_text', true );
        return $text;
        //return get_post_meta($object['id'], $field_name, true); 
}

function song_update_text_cb($value, $object, $field_name){
  return update_post_meta($object['id'], $field_name, $value); 
}

add_action('rest_api_init', function(){

  register_rest_field('song', 'text', 
    array(
    'get_callback' => 'song_get_text_cb', 
    'update_callback' => 'song_update_text_cb', 
    'schema' => null

    )
  ); 
});

function song_get_translation_cb($object, $field_name, $request){
        $translation = get_post_meta( get_the_ID(), 'song_translation', true );
        return $translation;
        //return get_post_meta($object['id'], $field_name, true); 
}

function song_update_translation_cb($value, $object, $field_name){
  return update_post_meta($object['id'], $field_name, $value); 
}

add_action('rest_api_init', function(){

  register_rest_field('song', 'translation', 
    array(
    'get_callback' => 'song_get_translation_cb', 
    'update_callback' => 'song_update_translation_cb', 
    'schema' => null

    )
  ); 
});

function song_get_bible_ref_cb($object, $field_name, $request){
        $bible_ref = get_post_meta( get_the_ID(), 'song_bible_ref', true );
        return $bible_ref;
        //return get_post_meta($object['id'], $field_name, true); 
}

function song_update_bible_ref_cb($value, $object, $field_name){
  return update_post_meta($object['id'], $field_name, $value); 
}

add_action('rest_api_init', function(){

  register_rest_field('song', 'bible_ref', 
    array(
    'get_callback' => 'song_get_bible_ref_cb', 
    'update_callback' => 'song_update_bible_ref_cb', 
    'schema' => null

    )
  ); 
});

function song_get_key_cb($object, $field_name, $request){
        $key = get_post_meta( get_the_ID(), 'song_key', true );
        return $key;
        //return get_post_meta($object['id'], $field_name, true); 
}

function song_update_key_cb($value, $object, $field_name){
  return update_post_meta($object['id'], $field_name, $value); 
}

add_action('rest_api_init', function(){

  register_rest_field('song', 'key', 
    array(
    'get_callback' => 'song_get_key_cb', 
    'update_callback' => 'song_update_key_cb', 
    'schema' => null

    )
  ); 
});

function song_get_tempo_cb($object, $field_name, $request){
        $tempo = get_post_meta( get_the_ID(), 'song_tempo', true );
        return $tempo;
        //return get_post_meta($object['id'], $field_name, true); 
}

function song_update_tempo_cb($value, $object, $field_name){
  return update_post_meta($object['id'], $field_name, $value); 
}

add_action('rest_api_init', function(){

  register_rest_field('song', 'tempo', 
    array(
    'get_callback' => 'song_get_tempo_cb', 
    'update_callback' => 'song_update_tempo_cb', 
    'schema' => null

    )
  ); 
});

function song_get_capo_cb($object, $field_name, $request){
        $capo = get_post_meta( get_the_ID(), 'song_capo', true );
        return $capo;
        //return get_post_meta($object['id'], $field_name, true); 
}

function song_update_capo_cb($value, $object, $field_name){
  return update_post_meta($object['id'], $field_name, $value); 
}

add_action('rest_api_init', function(){

  register_rest_field('song', 'capo', 
    array(
    'get_callback' => 'song_get_capo_cb', 
    'update_callback' => 'song_update_capo_cb', 
    'schema' => null

    )
  ); 
});

function song_get_bandcamp_cb($object, $field_name, $request){
        $bandcamp = get_post_meta( get_the_ID(), 'song_bandcamp', true );
        return $bandcamp;
        //return get_post_meta($object['id'], $field_name, true); 
}

function song_update_bandcamp_cb($value, $object, $field_name){
  return update_post_meta($object['id'], $field_name, $value); 
}

add_action('rest_api_init', function(){

  register_rest_field('song', 'bandcamp', 
    array(
    'get_callback' => 'song_get_bandcamp_cb', 
    'update_callback' => 'song_update_bandcamp_cb', 
    'schema' => null

    )
  ); 
});

function song_get_spotify_cb($object, $field_name, $request){
        $spotify = get_post_meta( get_the_ID(), 'song_spotify', true );
        return $spotify;
        //return get_post_meta($object['id'], $field_name, true); 
}

function song_update_spotify_cb($value, $object, $field_name){
  return update_post_meta($object['id'], $field_name, $value); 
}

add_action('rest_api_init', function(){

  register_rest_field('song', 'spotify', 
    array(
    'get_callback' => 'song_get_spotify_cb', 
    'update_callback' => 'song_update_spotify_cb', 
    'schema' => null

    )
  ); 
});

function song_get_video_cb($object, $field_name, $request){
        $video = get_post_meta( get_the_ID(), 'song_video', true );
        return $video;
        //return get_post_meta($object['id'], $field_name, true); 
}

function song_update_video_cb($value, $object, $field_name){
  return update_post_meta($object['id'], $field_name, $value); 
}

add_action('rest_api_init', function(){

  register_rest_field('song', 'video', 
    array(
    'get_callback' => 'song_get_video_cb', 
    'update_callback' => 'song_update_video_cb', 
    'schema' => null

    )
  ); 
});








function my_rest_prepare_song( $data, $song, $request ) {
    $_data = $data->data;

    $params = $request->get_params();
    if ( ! isset( $params['id'] ) ) {
        unset( $_data['content'] );
        unset( $_data['yoast_head'] );
    }

    $data->data = $_data;

    return $data;
}
add_filter( 'rest_prepare_song', 'my_rest_prepare_song', 10, 3 );

Msh_Song::init();