<?php
/**
 * The sub-template used for displaying Aktiviteter table head.
 *
 * @package Seniorportalen
 * @subpackage Senioraktivitet
 */
?>
				<thead>
					<tr>
						<th class="dato-col" scope="col">Dato</th>
						<th class="tid-col" scope="col">Tid</th>
						<th class="aktivitetstype-col" scope="col">Aktivitet</th>
<?php
if ( ! is_tax( pp_kom_tax() ) ) {
?>
						<th class="fylke-col" scope="col"><?php echo 7 == get_current_blog_id() ? 'Fylke' : 'Fylke/Land'; ?></th>
<?php
}
?>
						<th class="title-col" scope="col">Tittel</th>
					</tr>
				</thead>
