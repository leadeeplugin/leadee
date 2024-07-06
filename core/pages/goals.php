<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
// goals template
$scripts_loader = new LEADEE_Scripts_Loader();
$scripts_loader->load_scripts_page_goals();
$functions = new LEADEE_Functions();
?>
<section>
	<div class="row section-row">
		<div class="col-70">
			<div class="card" id="leads-stat">
				<div class="card-content">
					<div class="main-chart">
						<h2 class="title-medium"><?php esc_html_e( 'Goals Statistics', 'leadee' ); ?></h2>
						<div class="title-detail"><?php esc_html_e( 'Average count of goals', 'leadee' ); ?></div>
						<canvas id="main-chart" width="400" height="400" class=""></canvas>
					</div>
				</div>
			</div>
		</div>
		<div class="col-30">
			<div class="card" id="goals-status">
				<div class="target-chart">
					<?php require_once LEADEE_PLUGIN_DIR . '/core/pages/components/smile-bar.php'; ?>
				</div>
				<div class="content-title">
					<h2 class="title-medium"><?php esc_html_e( 'Conversion goals', 'leadee' ); ?></h2>
					<div class="target-medium-info"><?php esc_html_e( 'How close to the target for the current month', 'leadee' ); ?></div>
				</div>
				<div class="target-small-info">
					<div class="target-small-child">
						<i class="leadee-icon icon-target-green"></i>
						<span class=""><?php esc_html_e( 'Your goal', 'leadee' ); ?>:<span id="month-target">0</span></span>
					</div>
					<div class="target-small-child">
						<i class="leadee-icon icon-info"></i>
						<span class=""><?php esc_html_e( 'Goal = useful action on the site (enter message in form, etc.).', 'leadee' ); ?></span>
					</div>
					<div class="target-small-child">
						<i class="leadee-icon icon-info"></i>
						<span><?php esc_html_e( 'All goals are summarized in quantitative and monetary terms.', 'leadee' ); ?></span>
					</div>
					<a href="<?php echo esc_url( get_site_url() ); ?>/?leadee-page=goals-settings" id="goals-button"
						class="button button-raised button-fill button-round big-button button-green">
						<i class="leadee-icon icon-right-white"></i> <span
								class=""><?php esc_html_e( 'Set goal', 'leadee' ); ?></span>
					</a>
				</div>
			</div>
		</div>
	</div>

	<div class="card table-leads" id="table-leads-target">
		<div class="row">
			<div class="col-100">
				<table id="leads-list-target" class="table leads-table">
					<thead>
					<tr>
						<th><?php esc_html_e( 'Title', 'leadee' ); ?></th>
						<th><?php esc_html_e( 'Type', 'leadee' ); ?></th>
						<th><?php esc_html_e( 'Count', 'leadee' ); ?></th>
						<th><?php esc_html_e( 'Sum', 'leadee' ); ?></th>
					</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>
