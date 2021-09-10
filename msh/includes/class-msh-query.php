<?php

class Msh_Query {

    /**
     * @param $song_id
     * @return int | bool
     */
    public static function get_song_album($song_id) {
        $args = array(
            'connected_type' => 'album_to_song',
            'connected_items' => $song_id,
            'connected_direction' => 'to',
            'posts_per_page' => 1,
        );

        $connected_album = get_posts( $args );

        if ( ! empty( $connected_album[0] ) ) {
            return $connected_album[0]->ID;
        }
        return false;
    }

    public static function get_song_artist( $song_id ) {


        $args = array(
            'connected_type' => 'artist_to_song',
            'connected_items' => $song_id,
            'connected_direction' => 'to',
            'posts_per_page' => 6,
        );

        $song_artists = get_posts( $args );

        $song_artist_ids = array();

        foreach( $song_artists as $song_artist ) {
            $song_artist_ids[] = $song_artist->ID;
        }

        if ( ! empty( $song_artist_ids[0] ) ) {
            return $song_artist_ids[0];
        }
        return false;
    }

    public static function get_album_artist( $album_id ) {

        $album_songs = get_posts( array(
            'connected_type' => 'album_to_song',
            'connected_items' => $album_id,
            'posts_per_page' => 1,
        ) );

        if ( ! empty ( $album_songs[0] ) ) {
            $first_album_song = $album_songs[0];
            $album_artist = self::get_song_artist( $first_album_song->ID );
            return $album_artist;
        }
        return false;
    }

    public static function get_artist_albums( $artist_id ) {

        $artist_songs = get_posts( array(
            'connected_type' => 'artist_to_song',
            'connected_items' => $artist_id,
            'posts_per_page' => -1,
        ) );

        $artist_album_ids = array();

        foreach ( $artist_songs as $artist_song ) {
            $song_album_id = self::get_song_album( $artist_song->ID );
            if ( ! empty ( $song_album_id ) ) {
                if ( ! in_array( $song_album_id, $artist_album_ids ) ) {
                    $artist_album_ids[] = $song_album_id;
                }
            }
        }

        if ( ! empty ( $artist_album_ids ) ) {
            return $artist_album_ids;
        }

        return false;
    }

    public static function get_album_songs( $album_id ) {

        $args = array(
            'connected_type'        => 'album_to_song',
            'connected_items'       => $album_id,
            'connected_direction'   => 'from',
            'posts_per_page'        => -1,
        );

        $album_songs = get_posts( $args );

        return $album_songs;
    }

    public static function get_artist_songs( $artist_id ) {

        $args = array(
            'connected_type'        => 'artist_to_song',
            'connected_items'       => $artist_id,
            'connected_direction'   => 'from',
            'posts_per_page'        => -1,
        );

        $artist_songs = get_posts( $args );

        return $artist_songs;
    }

    public static function get_artists_another_albums( $artist_id, $album_id ) {
        $artist_album_ids = self::get_artist_albums($artist_id);

        if ( is_array( $artist_album_ids ) ) {
            if (($key = array_search($album_id, $artist_album_ids)) !== false) {
                unset($artist_album_ids[$key]);
            }
        }

        if (!empty ($artist_album_ids)) {
            return true;
        } else {
            return false;
        }
    }

    public static function get_album_songs_count( $album_id) {
        $args = array(
            'connected_type'        => 'album_to_song',
            'connected_items'       => $album_id,
            'connected_direction'   => 'from',
            'posts_per_page'        => -1,
        );

        $album_songs = get_posts( $args );
        return count($album_songs);
    }
}
