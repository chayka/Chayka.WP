<?php
/**
 * This is a base template used by our \Chayka\WP\Query to enable our MVC processing for WP
 */

global $post;
the_post();
get_header();
echo $post->post_content;
get_footer();