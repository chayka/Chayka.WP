<?php
global $post;
the_post();
get_header();
echo $post->post_content;
get_footer();