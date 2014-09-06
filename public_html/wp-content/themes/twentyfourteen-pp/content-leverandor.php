<?php
/**
 * The template used for displaying Leverandør content.
 *
 * @package SeniorPortalene
 * @subpackage BPA-Portalen
 * @subpackage FrittBrukervalgPortalen
 */
?>
		<tr class="privat-<?php echo strtolower( do_shortcode( '[pods]{@privat}[/pods]' ) );?>">
<?php
	$ptn  = strtolower( get_post_type_object( pp_lev_type() )->labels->singular_name );
	$imgsrc = wp_upload_dir( '2014/06' )['url'] . '/edit.png';
	if ( strlen( trim( do_shortcode( '[pods]{@website}[/pods]' ) ) ) ) {
?>
			<td class="leverandor-col"><a href="<?php echo do_shortcode( '[pods]{@website}[/pods]' ); ?>" title="Besøk <?php echo $ptn; ?>ens hjemmeside for mer informasjon (åpnes i ny fane)" target="_blank"><?php the_title(); ?></a><?php edit_post_link( '<img src="' . $imgsrc . '" alt=" ' . __( 'Edit', 'twentyfourteen' ) . ' " style="height: .9em;" title="Rediger ' . $ptn . 'en ' . get_the_title() . '" />', ' &nbsp; <span class="edit-link">', '</span>', get_the_id() ); ?></td>
<?php
	} else {
?>
			<td class="leverandor-col"><?php echo pp_lev_type() == get_post_type() ? '' : '<a href="' . get_permalink() . '" rel="bookmark">'; the_title(); echo strlen( trim( do_shortcode( '[pods]{@tlf}[/pods]' ) ) ) ? '<small> - <a href="tel:+47' . str_replace( ' ', '', do_shortcode( '[pods]{@tlf}[/pods]' ) ) . '" title="Ring til ' . get_the_title() . '" style="white-space: nowrap;">tlf ' . do_shortcode( '[pods]{@tlf}[/pods]' ) . '</a></small>' : ''; echo pp_lev_type() == get_post_type() ? '' : '</a>'; edit_post_link( '<img src="' . $imgsrc . '" alt=" ' . __( 'Edit', 'twentyfourteen' ) . ' " style="height: .9em;" title="Rediger ' . pp_lev_type() . 'en ' . get_the_title() . '" />', ' &nbsp; <span class="edit-link">', '</span>', get_the_id() ); ?></td>
<?php
	}
	if ( 4 == get_current_blog_id() ) {
?>
			<td class="hjemmesykepleie-col janei-col"><?php echo do_shortcode( '[pods]{@hjemmesykepleie}[/pods]' ) == 'Ja' ? '<span title="Ja">&#10004;</span>' : '<span title="Nei"> &nbsp; &nbsp; </span>'; ?></td>
			<td class="praktisk-bistand-col janei-col"><?php echo do_shortcode( '[pods]{@praktisk_bistand}[/pods]' ) == 'Ja' ? '<span title="Ja">&#10004;</span>' : '<span title="Nei"> &nbsp; &nbsp; </span>'; ?></td>
<?php
	} else {
		if ( pp_lev_type() == get_post_type() ) {
?>
			<td class="bpa-col janei-col"><span title="Ja">&#10004;</span></td>
<?php
		} else {
?>
			<td class="bpa-col janei-col">&nbsp;</td>
<?php
		}
	}
?>
			<td class="privat-col janei-col"><?php echo do_shortcode( '[pods]{@privat}[/pods]' ) == 'Ja' ? '<span title="Ja">&#10004;</span>' : '<span title="Nei"> &nbsp; &nbsp; </span>'; ?></td>
		</tr>