<?php
/**
 * The template for displaying Kommune pages.
 *
 * @package SeniorPortalene
 * @subpackage FrittBrukervalgPortalen BPA-Portalen
 */
if ( in_array( get_current_blog_id(), array( 4, 7 ) ) ) {
	$quo  = get_queried_object();
	$pto  = get_post_type_object( pp_lev_type() );
	$ptn  = $pto->labels->name . ' i ';
	if ( ( 4 == get_current_blog_id() && 8 == $quo->parent ) || ( 7 == get_current_blog_id() && 12 == $quo->parent ) )
		$kom = ', Oslo ' . pp_kom_tax();
	else
		$kom = ' ' . pp_kom_tax();
} else {
	$ptn = '';
	$kom = '';
}
get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php if ( have_posts() ) : ?>

			<header class="archive-header">
				<h1 class="archive-title"><?php echo $ptn; single_cat_title(); echo $kom; ?></h1>
			</header><!-- .archive-header -->
			<table id="<?php echo pp_lev_type(); ?>-table">
				<colgroup class="<?php echo pp_lev_type(); ?>-col"><col class="<?php echo pp_lev_type(); ?>-col"></col></colgroup><colgroup class="janei-col" span="3"><col class="hjemmesykepleie-col"></col><col class="praktisk-bistand-col"></col><col class="privat-col"></col></colgroup>
				<thead>
					<th  class="<?php echo pp_lev_type(); ?>-col" scope="col"><?php echo $pto->labels->singular_name; ?></th>
<?php
		if ( 4 == get_current_blog_id() ) {
?>
					<th class="hjemmesykepleie-col janei-col" scope="col">Hjemme&shy;sykepleie</th>
					<th class="praktisk-bistand-col janei-col" scope="col">Praktisk bistand</th>
<?php
		} else {
?>
					<th class="bpa-col janei-col" scope="col">BPA</th>
<?php
		}
?>
					<th class="privat-col janei-col" scope="col">Privat</th>
				</thead>
				<tbody>
<?php
					while ( have_posts() ) {
						the_post();
						if ( pp_akt_type() != get_post_type() )
							get_template_part( 'content', get_post_type() );
					}
?>
				</tbody>
			</table>
<?php
//					twentyfourteen_paging_nav();

				else :
					get_template_part( 'content', 'none' );

				endif;
			?>
			<?php get_sidebar( 'content-after' ); ?>
		</div><!-- #content -->
	</section><!-- #primary -->

<?php
get_sidebar( 'content' );
get_sidebar();
get_footer();
