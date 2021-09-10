<?php

$args = array(
    'post_type'         => 'chord',
    'orderby'           => 'title',
    'order'             => 'ASC',
    'posts_per_page'    => -1,
);

$chords = get_posts( $args );

wp_send_json($chords);
