<?php

class Msh_Tinymce_Buttons {

    public static function init()
    {
        add_action( 'init', array( __CLASS__, 'add_tiny_plugins' ) );
        add_action( 'wp_ajax_admin_chords_tinymce', array( __CLASS__, 'ajax_tinymce' ) );
       // add_action( 'init', array( __CLASS__, 'add_editor_styles' ) ); //podrobny text
      //  add_action( 'pre_get_posts', array( __CLASS__, 'add_editor_styles' ) ); //podrobny text
    }

    public static function add_tiny_plugins() {
    //    add_filter( 'tiny_mce_before_init', array( __CLASS__, 'my_mce_before_init_insert_formats' ) ); //podrobny text
        add_filter( 'mce_external_plugins', array( __CLASS__, 'buttons_script' ) );
        add_filter( 'mce_buttons_3', array( __CLASS__, 'register_buttons' ) );
        add_filter( 'mce_buttons_2', array( __CLASS__, 'gk_register_my_tc2_button' ) );
        
    }

    public static function buttons_script( $plugin_array ) {
        $plugin_array['admin_chords_script'] = MSH_URL . 'assets/js/tinymce-buttons.js?v=1';
        $plugin_array['gk_tc_button2'] = MSH_URL . 'assets/js/tinymce-buttons2.js?v=1';
        return $plugin_array;
    }

    public static function register_buttons( $buttons ) {

        // Extended lyrics
        // Chord buttons
        $args = array(
            'post_type'         => 'chord',
            'orderby'           => 'title',
            'order'             => 'ASC',
            'posts_per_page'    => -1,
        );

        $chords = get_posts( $args );

        foreach($chords as $chord) {
            $new_button = 'add_chord_' . $chord->post_name;
            array_push( $buttons, $new_button );
        }
        return $buttons;
    }
    
    function gk_register_my_tc2_button($buttons) {
   array_push($buttons, "gk_tc_button2");
   return $buttons;
    }

    public static function ajax_tinymce() {
        // Call TinyMCE window content via admin-ajax
        include_once( MSH_DIR . 'includes/ajax-chord-list.php');
        wp_die(); // this is required to terminate immediately and return a proper response
    }

    public static function add_editor_styles() {
        global $post;

        $my_post_type = 'song';

        // New post (init hook).
        if ( stristr( $_SERVER['REQUEST_URI'], 'post-new.php' ) !== false
            && ( isset( $_GET['post_type'] ) === true && $my_post_type == $_GET['post_type'] ) ) {
            add_editor_style( MSH_URL . 'assets/css/custom-editor-style.css' );
        }

        // Edit post (pre_get_posts hook).
        if ( stristr( $_SERVER['REQUEST_URI'], 'post.php' ) !== false
            && is_object( $post )
            && $my_post_type == get_post_type( $post->ID ) ) {
            add_editor_style( MSH_URL . 'assets/css/custom-editor-style.css' );
        }
    }

    /*
    * Callback function to filter the MCE settings
    */

    public static function my_mce_before_init_insert_formats( $init_array ) {

    // Define the style_formats array

        $style_formats = array(
            /*
            * Each array child is a format with it's own settings
            * Notice that each array has title, block, classes, and wrapper arguments
            * Title is the label which will be visible in Formats menu
            * Block defines whether it is a span, div, selector, or inline style
            * Classes allows you to define CSS classes
            * Wrapper whether or not to add a new block-level element around any selected elements
            */
            array(
                'title' => 'Podrobný text',
                'block' => 'p',
                'classes' => 'extended-lyrics',
                'wrapper' => false,

            ),
            array(
                'title' => 'Podrobný text (v riadku)',
                'inline' => 'span',
                'classes' => 'extended-lyrics-inline',
                'wrapper' => true,
            ),
            array(
                'title' => 'Názov sekcie',
                'block' => 'p',
                'classes' => 'lyrics-section',
                'wrapper' => false,
            ),
        );
        // Insert the array, JSON ENCODED, into 'style_formats'
        $init_array['style_formats'] = json_encode( $style_formats );

        return $init_array;

    }

}

Msh_Tinymce_Buttons::init();