<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $current_user;
wp_get_current_user();
$avatar_url = get_avatar_url(
	$GLOBALS['current_user'],
	array(
		'size'    => 48,
		'default' => 'wavatar',
	)
);
?>
<style>
	.leadee-preloader-area{
		background: #E5E5E5;
		overflow: hidden;
		position: fixed;
		left: 0;
		top: 0;
		right: 0;
		bottom: 0;
		z-index: 9999999999999;
	}
	.leadee-preloader{
		position: absolute;
		height: 233px;
		background-size: 40px;
		z-index: 9999999999999;
		left: 50%;
		top: 50%;
		margin: -125px 0 0 -125px;
	}
</style>
<body class="body-light">
<div id="app">
	<div class="top-navbar navbar navbar-large navbar-transparent navbar-large-collapsed">
		<div class="navbar-bg" style=""></div>
		<div class="navbar-inner navbar-inner-centered-title">
			<div class="left"><a href="#" class="link icon-only panel-open" data-panel=".panel-left"><i
							class="leadee-icon icon-hamburger"></i></a></div>
			<div class="header-content">
				<div id="header-calend" class="calend">
				</div>
				<a class="donate-top-mobile" href="https://donate.leadee.io" target="_blank"> <i
							class="leadee-icon leadee-icon-big icon-donate"></i>
					<span><?php esc_html_e( 'Donate us', 'leadee' ); ?></span> </a>
			</div>
			<div class="header-desktop-content">
				<div class="h-left">
					<div class="desc-logo">
						<div class="sidebar-logo">
							<svg width="210" height="50" viewBox="0 0 250 50" fill="none" xmlns="http://www.w3.org/2000/svg">
								<g clip-path="url(#clip0_2139_7928)">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M4.86758 11.4161C2.17929 11.4161 0 13.5954 0 16.2837L0 28.5756C0 35.1784 3.77298 41.1595 9.73516 44L9.73516 16.2837C9.73516 13.5954 7.55587 11.4161 4.86758 11.4161Z" fill="#FF6E42"/>
									<path fill-rule="evenodd" clip-rule="evenodd" d="M20.2919 18.0109C17.6036 18.0109 15.4243 20.1902 15.4243 22.8785L15.4243 43.9786C18.6703 43.9786 21.9135 43.9786 25.1595 43.9786V22.8785C25.1595 20.1902 22.9802 18.0109 20.2919 18.0109Z" fill="#FCC02A"/>
									<path fill-rule="evenodd" clip-rule="evenodd" d="M35.7173 4.00008C33.0298 4.00008 30.8511 6.17876 30.8511 8.86631L30.8511 43.9786C36.2262 43.9786 40.5835 39.6212 40.5835 34.2461V8.86631C40.5835 6.17876 38.4048 4.00008 35.7173 4.00008Z" fill="#8AC44B"/>
									<path d="M61.098 44.4256C57.8705 44.4256 55.3524 43.5744 53.5436 41.872C51.7348 40.1341 50.8304 37.6869 50.8304 34.5304V4.5256H59.1296V34.0516C59.1296 35.2575 59.4311 36.1973 60.034 36.8712C60.6724 37.5096 61.5591 37.8288 62.694 37.8288C63.1196 37.8288 63.5452 37.7756 63.9708 37.6692C64.4319 37.5628 64.7865 37.4387 65.0348 37.2968L65.4072 43.734C64.024 44.1951 62.5876 44.4256 61.098 44.4256ZM83.3792 37.7756C84.8688 37.7756 86.1811 37.5628 87.316 37.1372C88.4864 36.6761 89.5682 35.9668 90.5612 35.0092L94.9768 39.7972C92.2814 42.8828 88.3446 44.4256 83.1664 44.4256C79.939 44.4256 77.0839 43.8049 74.6012 42.5636C72.1186 41.2868 70.2034 39.5312 68.8556 37.2968C67.5079 35.0624 66.834 32.5265 66.834 29.6892C66.834 26.8873 67.4902 24.3692 68.8024 22.1348C70.1502 19.8649 71.9767 18.1093 74.282 16.868C76.6228 15.5912 79.2474 14.9528 82.1556 14.9528C84.8866 14.9528 87.3692 15.538 89.6036 16.7084C91.838 17.8433 93.6114 19.5103 94.9236 21.7092C96.2714 23.8727 96.9452 26.444 96.9452 29.4232L75.7716 33.5196C76.3746 34.9383 77.3144 36.0023 78.5912 36.7116C79.9035 37.4209 81.4995 37.7756 83.3792 37.7756ZM82.1556 21.2304C80.0631 21.2304 78.3607 21.9043 77.0484 23.252C75.7362 24.5997 75.0446 26.4617 74.9736 28.838L88.912 26.1248C88.5219 24.6352 87.7239 23.4471 86.518 22.5604C85.3122 21.6737 83.858 21.2304 82.1556 21.2304ZM131.571 15.3784V44H123.644V40.7016C121.587 43.1843 118.608 44.4256 114.707 44.4256C112.011 44.4256 109.564 43.8227 107.365 42.6168C105.201 41.4109 103.499 39.6908 102.258 37.4564C101.016 35.222 100.396 32.6329 100.396 29.6892C100.396 26.7455 101.016 24.1564 102.258 21.922C103.499 19.6876 105.201 17.9675 107.365 16.7616C109.564 15.5557 112.011 14.9528 114.707 14.9528C118.36 14.9528 121.215 16.1055 123.272 18.4108V15.3784H131.571ZM116.143 37.616C118.235 37.616 119.973 36.9067 121.357 35.488C122.74 34.0339 123.431 32.1009 123.431 29.6892C123.431 27.2775 122.74 25.3623 121.357 23.9436C119.973 22.4895 118.235 21.7624 116.143 21.7624C114.015 21.7624 112.259 22.4895 110.876 23.9436C109.493 25.3623 108.801 27.2775 108.801 29.6892C108.801 32.1009 109.493 34.0339 110.876 35.488C112.259 36.9067 114.015 37.616 116.143 37.616ZM168.408 4.5256V44H160.481V40.7016C158.424 43.1843 155.445 44.4256 151.544 44.4256C148.848 44.4256 146.401 43.8227 144.202 42.6168C142.039 41.4109 140.336 39.6908 139.095 37.4564C137.854 35.222 137.233 32.6329 137.233 29.6892C137.233 26.7455 137.854 24.1564 139.095 21.922C140.336 19.6876 142.039 17.9675 144.202 16.7616C146.401 15.5557 148.848 14.9528 151.544 14.9528C155.197 14.9528 158.052 16.1055 160.109 18.4108V4.5256H168.408ZM152.98 37.616C155.073 37.616 156.811 36.9067 158.194 35.488C159.577 34.0339 160.269 32.1009 160.269 29.6892C160.269 27.2775 159.577 25.3623 158.194 23.9436C156.811 22.4895 155.073 21.7624 152.98 21.7624C150.852 21.7624 149.097 22.4895 147.713 23.9436C146.33 25.3623 145.639 27.2775 145.639 29.6892C145.639 32.1009 146.33 34.0339 147.713 35.488C149.097 36.9067 150.852 37.616 152.98 37.616Z" fill="#343A40"/>
									<path d="M190.507 37.7756C191.996 37.7756 193.308 37.5628 194.443 37.1372C195.614 36.6761 196.696 35.9668 197.689 35.0092L202.104 39.7972C199.409 42.8828 195.472 44.4256 190.294 44.4256C187.066 44.4256 184.211 43.8049 181.729 42.5636C179.246 41.2868 177.331 39.5312 175.983 37.2968C174.635 35.0624 173.961 32.5265 173.961 29.6892C173.961 26.8873 174.618 24.3692 175.93 22.1348C177.278 19.8649 179.104 18.1093 181.409 16.868C183.75 15.5912 186.375 14.9528 189.283 14.9528C192.014 14.9528 194.497 15.538 196.731 16.7084C198.965 17.8433 200.739 19.5103 202.051 21.7092C203.399 23.8727 204.073 26.444 204.073 29.4232L182.899 33.5196C183.502 34.9383 184.442 36.0023 185.719 36.7116C187.031 37.4209 188.627 37.7756 190.507 37.7756ZM189.283 21.2304C187.19 21.2304 185.488 21.9043 184.176 23.252C182.864 24.5997 182.172 26.4617 182.101 28.838L196.039 26.1248C195.649 24.6352 194.851 23.4471 193.645 22.5604C192.44 21.6737 190.985 21.2304 189.283 21.2304ZM224.068 37.7756C225.558 37.7756 226.87 37.5628 228.005 37.1372C229.176 36.6761 230.257 35.9668 231.25 35.0092L235.666 39.7972C232.97 42.8828 229.034 44.4256 223.856 44.4256C220.628 44.4256 217.773 43.8049 215.29 42.5636C212.808 41.2868 210.892 39.5312 209.545 37.2968C208.197 35.0624 207.523 32.5265 207.523 29.6892C207.523 26.8873 208.179 24.3692 209.492 22.1348C210.839 19.8649 212.666 18.1093 214.971 16.868C217.312 15.5912 219.936 14.9528 222.845 14.9528C225.576 14.9528 228.058 15.538 230.293 16.7084C232.527 17.8433 234.3 19.5103 235.613 21.7092C236.96 23.8727 237.634 26.444 237.634 29.4232L216.461 33.5196C217.064 34.9383 218.004 36.0023 219.28 36.7116C220.593 37.4209 222.189 37.7756 224.068 37.7756ZM222.845 21.2304C220.752 21.2304 219.05 21.9043 217.738 23.252C216.425 24.5997 215.734 26.4617 215.663 28.838L229.601 26.1248C229.211 24.6352 228.413 23.4471 227.207 22.5604C226.001 21.6737 224.547 21.2304 222.845 21.2304Z" fill="#343A40"/>
								</g>
							</svg>
						</div>
					</div>
				</div>
				<div class="h-right">
					<div id="header-calend"></div>
					<div class="header-right">
						<button id="export-button"
								class="button button-raised button-fill button-round big-button button-green popover-open hidden"
								data-popover=".popover-export"><i
									class="leadee-icon icon-export"></i><span><?php esc_html_e( 'Export', 'leadee' ); ?></span>
						</button>
						<div class="top-icon-block" id="start-tour">
							<i class="leadee-icon leadee-icon-big icon-question pulse-button"></i>
						</div>
						<a class="donate-top" href="https://donate.leadee.io" target="_blank">
							<i class="leadee-icon leadee-icon-big icon-donate"></i>
							<span><?php esc_html_e( 'Donate us', 'leadee' ); ?></span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="popover popover-export">
		<div class="popover-angle"></div>
		<div class="popover-inner">
			<div class="list">
				<ul>
					<li><button class="list-button popover-close export-button" data-type="xls"><i class="icon-excel" style="background-image: url('<?php echo esc_url( LEADEE_PLUGIN_URL . '/core/assets/image/excel.png' ); ?>')"></i><span><?php esc_html_e( 'Excel table', 'leadee' ); ?></span></button></li>
					<li><button class="list-button popover-close export-button" data-type="csv"><i class="icon-csv" style="background-image: url('<?php echo esc_url( LEADEE_PLUGIN_URL . '/core/assets/image/csv.png' ); ?>')"></i><span><?php esc_html_e( 'CSV table', 'leadee' ); ?></span></button></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="panel panel-left panel-cover panel-init elevation-3">
		<div class="page no-swipe-panel">
			<div class="page-content sidebar-content" id="sidebar-links">
				<img class="sidebar-logo hidden-desktop"
					src="<?php echo esc_url( LEADEE_PLUGIN_URL . '/core/assets/image/logo.png' ); ?>"
					alt="">
				<div class="list links-list">
					<ul>
						<li>
							<a href="<?php echo esc_url( get_site_url() ); ?>/?leadee-page=dashboard"><i
										class="menu-icon icon-bar"></i><span><?php esc_html_e( 'Dashboard', 'leadee' ); ?></span></a>
						</li>
					</ul>
				</div>
				<div class="block-title"><?php esc_html_e( 'Work space', 'leadee' ); ?></div>
				<div class="list links-list">
					<ul>
						<li>
							<a href="<?php echo esc_url( get_site_url() ); ?>/?leadee-page=leads"><i
										class="menu-icon icon-leads"></i><span><?php esc_html_e( 'Leads', 'leadee' ); ?></span></a>
						</li>
						<li>
							<a href="<?php echo esc_url( get_site_url() ); ?>/?leadee-page=goals"><i
										class="menu-icon icon-goals"></i><span><?php esc_html_e( 'Goals', 'leadee' ); ?></span></a>
						</li>
					</ul>
				</div>
				<div class="block-title"><?php esc_html_e( 'Plugin settings', 'leadee' ); ?></div>
				<div class="list links-list">
					<ul>
						<li>
							<a href="<?php echo esc_url( get_site_url() ); ?>/?leadee-page=leads-table-settings"><i
										class="menu-icon icon-leads-table"></i><span><?php esc_html_e( 'Leads table settings', 'leadee' ); ?></span></a>
						</li>
						<li>
							<a href="<?php echo esc_url( get_site_url() ); ?>/?leadee-page=goals-settings"><i
										class="menu-icon icon-ref"></i><span><?php esc_html_e( 'Goals settings', 'leadee' ); ?></span></a>
						</li>
					</ul>
				</div>
				<div class="block-title"><?php esc_html_e( 'Support', 'leadee' ); ?></div>
				<div class="list links-list">
					<ul>
						<li>
							<a href="https://leadee.io/documentation/" target="_blank" rel="noopener noreferrer"><i
										class="menu-icon icon-support"></i><span><?php esc_html_e( 'Support & Faq', 'leadee' ); ?></span></a>
						</li>
						<li>
							<a href="https://donate.leadee.io" target="_blank"
								class=""><i
										class="menu-icon icon-donate"></i><span><?php esc_html_e( 'Donate us', 'leadee' ); ?></span></a>
						</li>
					</ul>
				</div>
				<div class="news-block">
					<div class="news-block-title"><h4><i class="menu-icon icon-news"></i> <span><?php esc_html_e( 'Leadee news', 'leadee' ); ?></span></h4></div>
					<ul class="news-list" id="news-feed">
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="content-block stroll-content">
		<div>
