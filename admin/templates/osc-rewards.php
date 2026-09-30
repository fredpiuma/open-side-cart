<div id="rewards_bars" class="osc-fw-ass-section osc-fw-ass-rewards-bars">

	<div class="osc-fw-asc-head osc-fw-asc-bars">

		<div>
			<span class="osc-fw-as-icon osc-fw-icon-gift"></span>
			<span class="osc-fw-asch-title osc-fw-as-is-pro">Progress Bars & Rewards</span>
		</div>

		
	</div>

					
	<div class="osc-rewards-cont">

		<div class="osc-rwenb-cont">
			<div class="osc-fw-as-field" bis_skin_checked="1">
				<div class="osc-fw-as-label">Enable</div>
				<label class="osc-fw-as-switch">
					<input type="hidden" name="osc-rewards-options[scbar-en]" value="no">
					<input name="osc-rewards-options[scbar-en]" type="checkbox" value="yes" <?php echo osc_helper()->get_rewards_option('scbar-en') === "yes" ? 'checked' : ''; ?>><span class="osc-fw-as-slider"></span>
				</label>
			</div>
		</div>

		<button type="button" class="osc-fw-btn osc-fw-btn-primary osc-add-bar">+ Add a new progress bar</button>

		<div class="osc-bars"></div>

	</div>

</div>