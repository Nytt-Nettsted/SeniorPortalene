<?php
/**
 * The template for displaying featured content.
 *
 */
function pp_get_the_categories( $cats ) {
	return array( reset( $cats ) );
}

$include_one  = true; // false;		// Set to false not to show posts from main site

if ( pp_featured_content_pos()[ get_current_blog_id() ] == 'top' ) {
?>
<div id="featured-content" class="featured-content">
<?php
	do_action( 'twentyfourteen_featured_posts_before' );
	if ( get_current_blog_id() == 1 ) {	/* Special featured posts section on main site */
		$src = '';
 		add_filter( 'the_category', 'pp_the_category', 10, 1 );
		foreach ( pp_sites( $src ) as $site_id => $site ) {
			switch_to_blog( $site_id );
			$featured_posts = get_posts( array( 'posts_per_page' => 1, 'suppress_filters' => false ) );
			$post = $featured_posts[0];
			$post->featured = true;
			$post->src = $src;
			setup_postdata( $post );
			set_transient( 'twentyfourteen_category_count', PP_CATEGORY_COUNT );
			get_template_part( 'content', 'featured-post' );
			restore_current_blog();
		}
		//query_posts();
	} elseif ( get_current_blog_id() == 2 ) {
		add_filter( 'get_the_categories', 'pp_get_the_categories' );
//		$featured_posts = array_slice( array_merge( get_posts( array( 'post_type' => 'post', 'posts_per_page' => 1 ) ), twentyfourteen_get_featured_posts() ), 0, 2 );
		$featured_posts = twentyfourteen_get_featured_posts();
		get_template_part( 'content', 'featured-forums' );
		foreach ( $featured_posts as $post ) {
			$post->featured = true;
			add_filter( 'the_category', 'pp_the_category', 10, 1 );
			setup_postdata( $post );
			get_template_part( 'content', 'featured-cpt' );
		}
		remove_filter( 'get_the_categories', 'pp_get_the_categories' );
	} elseif ( get_current_blog_id() == 7 || get_current_blog_id() == 9 ) {
		$featured_posts = array_slice( array_merge( get_posts( array( 'post_type' => 'post', 'posts_per_page' => 1 ) ), twentyfourteen_get_featured_posts() ), 0, 2 );
		get_template_part( 'content', 'featured-forums' );
		foreach ( $featured_posts as $post ) {
			$post->featured = true;
			add_filter( 'the_category', 'pp_the_category', 10, 1 );
			setup_postdata( $post );
			get_template_part( 'content', 'featured-cpt' );
		}
	} elseif ( get_current_blog_id() == 3 ) {
		$featured_posts = array_slice( array_merge( get_posts( array( 'post_type' => pp_pro_type(), 'posts_per_page' => 1 ) ), twentyfourteen_get_featured_posts() ), 0, 3 );
		$first = true;
		foreach ( $featured_posts as $post ) {
			setup_postdata( $post );
			if ( $first ) {
				$post->featured = true;
				add_filter( 'the_category', 'pp_the_category', 10, 1 );
				$first = false;
			} else {
				remove_filter( 'the_category', 'pp_the_category', 10, 1 );
			}
			get_template_part( 'content', 'featured-cpt' );
		}
	} elseif ( get_current_blog_id() == 5 ) {
		$featured_posts = array_slice( array_merge( get_posts( array( 'post_type' => pp_akt_type(), 'posts_per_page' => 1 ) ), get_posts( array( 'post_type' => 'post', 'posts_per_page' => 1 ) ), twentyfourteen_get_featured_posts() ), 0, 3 );
		$first = true;
		foreach ( $featured_posts as $post ) {
			setup_postdata( $post );
			if ( $first ) {
				$post->featured = true;
				add_filter( 'the_category', 'pp_the_category', 10, 1 );
				$first = false;
			} else {
				remove_filter( 'the_category', 'pp_the_category', 10, 1 );
			}
			get_template_part( 'content', 'featured-cpt' );
		}
	} else {	/* Normal featured posts section on other sites */
		$featured_posts = twentyfourteen_get_featured_posts();
		foreach ( (array) $featured_posts as $order => $post ) {
			setup_postdata( $post );
			get_template_part( 'content', 'featured-post' );
		}
	}
	do_action( 'twentyfourteen_featured_posts_after' );
	wp_reset_postdata();
?>
</div><!-- #featured-content .featured-content -->
<?php
}
?>
