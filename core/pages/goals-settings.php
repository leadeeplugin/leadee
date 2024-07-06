<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$scripts_loader = new LEADEE_Scripts_Loader();
$scripts_loader->load_scripts_page_goals_settings();
$functions = new LEADEE_Functions();
?>
<section>
	<div class="row">
		<div class="col-100 medium-70">
			<div class="card" id="form-set-goal-sum">
				<div class="settings-save-block">
					<div class="row">
						<div>
							<div class="content-title">
								<h2 class="title-medium"><?php esc_html_e( 'Goal sum setting for forms', 'leadee' ); ?></h2>
								<div class="title-detail"><?php esc_html_e( 'The table reflects the cost of the goals that will come from contact forms', 'leadee' ); ?></div>
							</div>
						</div>
						<div>
							<button class="button button-raised button-fill button-round big-button button-green"
									id="target-settings-save-button"><i
										class="leadee-icon icon-save-data-light"></i><span
										class=""><?php esc_html_e( 'Save data', 'leadee' ); ?></span>
							</button>
						</div>
					</div>

					<div class="card data-table goals-setting-table">

						<table id="leads-list-target-settings" class="dark-table">
							<thead>
							<tr>
								<th class="numeric-cell"><?php esc_html_e( 'Title', 'leadee' ); ?></th>
								<th class="numeric-cell"><?php esc_html_e( 'Type', 'leadee' ); ?></th>
								<th class="numeric-cell"><?php esc_html_e( 'Status', 'leadee' ); ?></th>
								<th class="numeric-cell"><?php esc_html_e( 'Sum', 'leadee' ); ?></th>
							</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-100 medium-30">
			<div class="card" id="goal-setting">
				<div class="target-chart">
					<?php require_once LEADEE_PLUGIN_DIR . '/core/pages/components/smile-bar.php'; ?>
				</div>
				<div class="content-title">
					<h2 class="title-medium"><?php esc_html_e( 'Enter goal amount', 'leadee' ); ?></h2>
					<div class="target-medium-info">
						<?php esc_html_e( 'How much do you want leads and conversions that which users will for 1 month?', 'leadee' ); ?>
					</div>

					<div class="item-target-form">
						<div class="row">
							<div class="item-input-wrap vert-center area-input">
								<input type="number" id="target-month-sum"
										placeholder="<?php esc_html_e( 'Enter amount in $', 'leadee' ); ?>" class="">
							</div>
									<div>
										<button class="button button-raised button-fill button-round big-button button-green"
												id="target-month-sum-save-button"><i
													class="leadee-icon icon-save-data-light"></i><span
													class=""><?php esc_html_e( 'Save data', 'leadee' ); ?></span>
										</button>
									</div>
								</div>
							</div>
							<div class="target-small-info">
								<div class="target-small-child">
									<i class="leadee-icon icon-info"></i>
									<span><?php esc_html_e( 'Goal = useful action on the site (enter message in form, etc.).', 'leadee' ); ?></span>
								</div>
								<div class="target-small-child">
									<i class="leadee-icon icon-info"></i>
									<span><?php esc_html_e( 'All goals are summarized in quantitative and monetary terms.', 'leadee' ); ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
