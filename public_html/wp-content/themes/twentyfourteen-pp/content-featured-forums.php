<?php
/**
 * The template for displaying forum page as featured post on the front page
 *
 * @package Seniorportalene
 */
$att_ids = pp_forum_thumbnails();
$url = 2 == get_current_blog_id() ? '/forums/forum/diskusjonsforum-pa-seniorernaering/' : '/forums';
?>

<article id="forums" <?php post_class( 'has-post-thumbnail' ); ?>>
	<a class="post-thumbnail" href="<?php echo $url; ?>">
		<?php echo wp_get_attachment_image( $att_ids[ get_current_blog_id() ], 'post-thumbnail', array( 'class' => 'attachment-post-thumbnail' ) ); ?>
	</a>
	<header class="entry-header">
		<div class="entry-meta">
			<span class="cat-links">Forum</span>
		</div><!-- .entry-meta -->
		<h1 class="entry-title"><a href="<?php echo $url; ?>" rel="bookmark">Diskusjonsforum</a></h1>
	</header><!-- .entry-header -->
</article><!-- #post-## -->
