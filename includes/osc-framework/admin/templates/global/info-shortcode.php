<?php if( empty( $shortcodes ) ) return; ?>

<div class="osc-fw-sc-shortcodes">
	<h3>Shortcodes</h3>
	<?php foreach ( $shortcodes as $key => $data ): ?>

		<div class="osc-fw-sc-container">
			<div>
				<span class="osc-fw-sc-name"><?php echo esc_html( $data['shortcode'] ) ?></span> - <span class="osc-fw-sc-desc"><?php echo esc_html( $data['desc'] ) ?></span>
			</div>
			<?php if( isset( $data['example'] ) ): ?>
				<span class="osc-fw-sc-example">Eg: <?php echo esc_html( $data['example'] ) ?></span>
			<?php endif; ?>

			<?php if( isset( $data['atts'] ) ): ?>
				<table class="osc-fw-sc-table">

					<tr>
						<th>Attribute</th>
						<th>Expected</th>
						<th>Default</th>
						<th>Description</th>
					</tr>

					<?php foreach ( $data['atts'] as $attData){
						echo '<tr>';
						foreach ( $attData as $keyTD => $valueTD ) {
							echo '<td>'.esc_html( $valueTD ).'</td>';	
						}
						echo '</tr>';
					} ?>


				</table>
			<?php endif; ?>
		</div>

	<?php endforeach; ?>
</div>