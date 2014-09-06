<?php
echo PHP_EOL, ' <div class="jp-relatedposts-post">';
echo PHP_EOL, '  <a class="jp-relatedposts-a" href="', get_permalink( $post->ID ), '" title="', esc_attr( get_the_title( $post->ID ) . ' (' . $post->src ), ')">';
echo PHP_EOL, get_the_post_thumbnail( $post->ID, 'thumbnail', array( 'class' => 'jp-relatedposts-post-img') );
echo PHP_EOL, '   <h4 class="jp-relatedposts-post-title">', get_the_title( $post->ID ) , '</h4>';
echo PHP_EOL, '  </a>';
echo PHP_EOL, ' </div>';