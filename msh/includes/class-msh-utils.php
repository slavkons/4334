<?php

class Msh_Utils {

    public static function song_navigation( $song_id = null ) {

        if ( is_null( $song_id ) ) {
            $song_id = get_the_ID();
        }

        $song_album = Msh_Query::get_song_album( $song_id );
        if ( ! $song_album ) {
            return;
        }

        $songs = Msh_Query::get_album_songs( $song_album );

        $index = 0;
        foreach ( $songs as $song ) {
            if ( $song->ID == $song_id ) {
                if ( $index > 0 ) {
                    $prev_song_id = $songs[$index - 1];
                } else {
                    $prev_song_id = null;
                }

                if ( $index < sizeof( $songs ) - 1 ) {
                    $next_song_id = $songs[$index + 1];
                } else {
                    $next_song_id = null;
                }
            }
            $index++;
        }
        ?>
<!-- .navigation -->
    <?php
    }

    public static function song_has_detail( $song_id = null ) {

        if ( get_post_meta( $song_id, 'song_detail', true ) == 'disable' ) {
            return false;
        }

        return true;
    }

    public static function get_artist_songs_with_video( $artist_id, $count = -1 ) {
        $has_video = array();

        $songs = Msh_Query::get_artist_songs( $artist_id );

        $index = 1;

        foreach( $songs as $song ) {
            $video_url = get_post_meta( $song->ID, 'song_video', true );

            if ($video_url) {
                $has_video[] = $song->ID;

                if ( $index == $count ) break;

                $index++;
            }
        }

        return $has_video;
    }

    public static function get_album_songs_with_video( $album_id, $count = -1 ) {
        $has_video = array();

        $songs = Msh_Query::get_album_songs( $album_id );

        $index = 1;

        foreach( $songs as $song ) {
            $video_url = get_post_meta( $song->ID, 'song_video', true );

            if ($video_url) {
                $has_video[] = $song->ID;

                if ( $index == $count ) break;

                $index++;
            }
        }

        return $has_video;
    }

    public static function get_youtube_thumbnail ( $youtube_url ) {
        $youtube_id = explode('v=', $youtube_url)[1];


        return "http://img.youtube.com/vi/" . $youtube_id . "/0.jpg";
    }

    public static function get_post_number( $post_id, $taxonomy, $term ) {
        $args = array(
            'order' => 'ASC',
            'post_type' => 'post',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'tax_query' => array(
                array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'slug',
                    'terms'    => $term,
                ),
            ),
        );
        $query = new WP_Query($args);
        if ( $query->have_posts() ) {
            $post_number = array_search($post_id, $query->get_posts()) + 1;
            return $post_number;
        } else {
            return null;
        }
    }
}
