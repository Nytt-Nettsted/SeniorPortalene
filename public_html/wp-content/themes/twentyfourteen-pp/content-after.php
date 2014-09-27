<?php
echo PHP_EOL, ' <div class="pp-relatedposts-post">';
echo PHP_EOL, '  <a class="pp-relatedposts-a" href="', get_permalink( $post->ID ), '" title="', esc_attr( get_the_title( $post->ID ) . ' (' . $post->src ), ')">';
echo PHP_EOL, get_the_post_thumbnail( $post->ID, 'medium', array( 'class' => 'pp-relatedposts-post-img') );
echo PHP_EOL, '   <h4 class="pp-relatedposts-post-title">', mb_substr( get_the_title( $post->ID ), 0, 50 ), '</h4>';
echo PHP_EOL, '  </a>';
echo PHP_EOL, '  <p class="pp-relatedposts-posts-context">', get_blog_details( $post->blog_id )->blogname, '</p>';
echo PHP_EOL, ' </div>';