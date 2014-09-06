<?php
/**
 * The template used for displaying Aktiviteter.
 *
 * @package Seniorportalene
 * @subpackage Senioraktivitet
 */
$pod = pods( get_post_type(), get_the_ID() );
$slutt = $pod->display( 'sluttid' );
$slutt = $slutt ? '-' . $slutt : $slutt;
?>
		<tr class="<?php echo get_post_type(); ?>-row">
			<td class="dato-col"><?php echo mysql2date( 'd.m', $post->akt_date ); ?></td>
			<td class="tid-col"><?php echo $pod->display( 'starttid' ), $slutt; ?></td>
			<td class="aktivitetstype-col"><?php echo get_the_term_list( get_the_ID(), 'aktivitetstype', '', ', ', '' ); ?></td>
<?php
if ( ! is_tax( pp_kom_tax() ) ) {
?>
			<td class="<?php echo pp_kom_tax(); ?>-col"><?php echo get_the_term_list( get_the_ID(), pp_kom_tax(), '', ', ', '' ); ?></td>
<?php
}
?>
			<td class="the_title-col"><a rel="bookmark" href="<?php the_permalink(); echo '?date=', $post->akt_date; ?>" title="Se alle detaljene om aktiviteten &laquo;<?php the_title_attribute(); ?>&raquo;"><?php the_title(); ?></a>
		</tr>
