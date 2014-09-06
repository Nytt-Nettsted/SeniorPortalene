<?php
/**
 * The sub-template used for displaying Aktiviteter table head.
 *
 * @package Seniorportalen
 * @subpackage FrittBrukervalgPortalen
 */
?>
			<table id="<?php pp_lev_type(); ?>-table">
				<colgroup class="leverandor-col"><col class="leverandor-col"></col></colgroup><colgroup class="janei-col" span="3"><col class="hjemmesykepleie-col"></col><col class="praktisk-bistand-col"></col><col class="privat-col"></col></colgroup>
				<thead>
					<tr>
						<th  class="leverandor-col" scope="col"><?php echo is_search() ? 'Innlegg / leverandør': 'Leverandør'; ?></th>
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
					</tr>
				</thead>
				<tbody>
