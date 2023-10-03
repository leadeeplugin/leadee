<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
// leads template
$scripts_loader = new LEADEE_Scripts_Loader();
$scripts_loader->load_scripts_page_leads();
$functions = new LEADEE_Functions();
?>
<section>
	<div class="card" id="leads-stat">
		<div class="card-content">
			<div class="main-chart">
				<h2 class="title-medium"><?php esc_html_e( 'Leads statistics', 'leadee' ); ?></h2>
				<div class="title-detail"><?php esc_html_e( 'Average count of leads', 'leadee' ); ?></div>
				<canvas id="main-chart" width="400" height="400" class=""></canvas>
			</div>
		</div>
	</div>
	<div class="card table-leads" id="table-leads">
		<div id="leads-filter-block"></div>
		<div class="row">
			<div class="col-100">
				<table id="leads-list" class="table leads-table">
					<thead>
					<tr>
						<?php if ( $functions->get_setting_option_value( 'leads-table-columns', 'dt' ) === '1' ) { ?>
							<th><?php esc_html_e( 'Date', 'leadee' ); ?></th><?php } ?>
						<th><?php esc_html_e( 'Form fields', 'leadee' ); ?></th>
						<th><?php esc_html_e( 'Source type', 'leadee' ); ?></th>
						<th><?php esc_html_e( 'Device type', 'leadee' ); ?></th>
						<th><?php esc_html_e( 'Page', 'leadee' ); ?></th>
						<th><?php esc_html_e( 'Device OS', 'leadee' ); ?></th>
						<th><?php esc_html_e( 'Form type', 'leadee' ); ?></th>
						<th><?php esc_html_e( 'Form', 'leadee' ); ?></th>
						<?php if ( $functions->is_enable_column( 'source' ) ) { ?>
							<th><?php esc_html_e( 'Source', 'leadee' ); ?></th><?php } ?>
						<?php if ( $functions->is_enable_column( 'first_url_parameters' ) ) { ?>
							<th><?php esc_html_e( 'First visit params', 'leadee' ); ?></th><?php } ?>
						<?php if ( $functions->is_enable_column( 'device_browser' ) ) { ?>
							<th><?php esc_html_e( 'Browser', 'leadee' ); ?></th><?php } ?>
						<?php if ( $functions->is_enable_column( 'device_screen_size' ) ) { ?>
							<th><?php esc_html_e( 'Screen', 'leadee' ); ?></th><?php } ?>
						<th><?php esc_html_e( 'Cost', 'leadee' ); ?></th>
					</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>
