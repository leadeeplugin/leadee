<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
// dashboard template
$scripts_loader = new LEADEE_Scripts_Loader();
$scripts_loader->load_scripts_page_dashboard();
?>
<section>
	<div class="row section-row">
		<div class="col-100 medium-70">
			<div class="card" id="leads-stat">
				<div class="card-content">
					<div class="main-chart">
						<h2 class="title-medium"><?php esc_html_e( 'Leads statistics', 'leadee' ); ?></h2>
						<div class="title-detail"><?php esc_html_e( 'Average count of leads', 'leadee' ); ?></div>
						<canvas id="main-chart" width="400" height="400"></canvas>
					</div>
				</div>
			</div>
		</div>
		<div class="col-100 medium-30">
			<div class="card" id="new-leads">
				<div class="leads-container">
					<div>
						<img class="header-leads"
							src="<?php echo esc_url( LEADEE_PLUGIN_URL . '/core/assets/image/header-leads.png' ); ?>"
							alt="leads">
					</div>
					<div class="list">
						<h2 class="title-medium"><?php esc_html_e( 'Last leads', 'leadee' ); ?></h2>
						<ul class="leads-new-widget" id="leads-new-widget">
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<section>
	<div class="target-swiper">
		<div class="row section-row" id="leads-sources-block">
			<div class="col-100 medium-40">
				<div class="card" id="target-plan">
					<div class="target-plan">
						<h2 class="title-medium"><?php esc_html_e( 'Goals of leads', 'leadee' ); ?></h2>
						<small>% <?php esc_html_e( 'of goal achievement per current month', 'leadee' ); ?></small>
						<div class="row">
							<div class="col-100 medium-40 ord2-desktop">
								<div class="gauge target-gauge"></div>
							</div>
							<div class="col-100 medium-60 ord1-desktop">
								<div class="card-content" id="target-block">
									<div class="s"
										id="target-user"><?php esc_html_e( 'Goal amount', 'leadee' ); ?>:
										<b>0</b>
									</div>
									<div class="s"
										id="target-current"><?php esc_html_e( 'Leads month sum', 'leadee' ); ?>
										: <b>0</b>
									</div>
									<div class="s hidden"
										id="target-none"><?php esc_html_e( 'Goal not set!', 'leadee' ); ?></div>
									<a href="<?php echo esc_url( get_site_url() . '/?leadee-page=goals-settings' ); ?>"
										class="button button-fill button-target button-green-style hidden"
										id="target-button">
										<i
												class="leadee-icon icon-right-white"></i>
										<span><?php esc_html_e( 'Set goal', 'leadee' ); ?></span>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-100 medium-60">
				<div data-pagination='{"el": ".swiper-pagination"}' data-space-between="10"
					data-slides-per-view="auto" data-centered-slides="true"
					class="swiper-container swiper-init swiper-mult demo-swiper-multiple-auto">
					<div class="swiper-pagination"></div>
					<div class="swiper-wrapper">
						<div class="swiper-slide swiper-40 swiper-md-40">
							<div class="card" id="counter-1">

								<div class="card-content card-content-padding">
									<h2 class="title-medium"><?php esc_html_e( 'Today', 'leadee' ); ?></h2>
									<div class="block-counter" id="counter-today">
										<span><span>0</span><sup></sup></span>
									</div>
								</div>
							</div>
						</div>
						<div class="swiper-slide swiper-40 swiper-md-40">
							<div class="card" id="counter-2">
								<div class="card-content card-content-padding ">
									<h2 class="title-medium"><?php esc_html_e( 'Yesterday', 'leadee' ); ?></h2>
									<div class="block-counter" id="counter-yesterday">
										<span><span>0</span></span>
									</div>
								</div>
							</div>
						</div>
						<div class="swiper-slide swiper-40 swiper-md-40">
							<div class="card" id="counter-3">
								<div class="card-content card-content-padding">
									<h2 class="title-medium"><?php esc_html_e( 'Week', 'leadee' ); ?></h2>
									<div class="block-counter" id="counter-week">
										<span><span>0</span></span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<section>
	<div class="card">
		<div class="row section-row">
			<div class="col-100 medium-20 ord2-mobile col-md-100">
				<div id="source">
					<div class="card-content card-content-padding">
						<h2 class="title-medium"><?php esc_html_e( 'Leads sources', 'leadee' ); ?></h2>
						<div class=""><?php esc_html_e( 'Where do leads come from', 'leadee' ); ?></div>
						<div class="leads-from-block">
							<ul>
								<li class="row">
									<div class="col-10">
										<i class="icon-dot icon-dot-dark-blue"></i>
									</div>
									<div class="col-70 medium-80">
										<span><?php esc_html_e( 'Search engines', 'leadee' ); ?></span>
										<small><?php esc_html_e( 'Google, Bing, Yandex, etc', 'leadee' ); ?></small>
									</div>
									<div class="col-10 medium-40 hidden-lg">
										<div class="progressbar-block">
											<div class="amount-block">
												<span class="el1">0</span>
												<span class="el2">0%</span></div>
											<div class="progressbar color-dark-blue" data-progress="10">
												<span style="transform: translate3d(-80%, 0px, 0px);"></span>
											</div>
										</div>
									</div>
								</li>

								<li class="row">
									<div class="col-10">
										<i class="icon-dot icon-dot-green"></i>
									</div>
									<div class="col-70 medium-80">
										<span><?php esc_html_e( 'Advertising systems', 'leadee' ); ?></span>
										<small><?php esc_html_e( 'Google Ads, FB Ads, etc', 'leadee' ); ?></small>
									</div>

									<div class="col-10 medium-40 hidden-lg">
										<div class="progressbar-block">
											<div class="amount-block">
												<span class="el1">0</span>
												<span class="el2">0%</span></div>
											<div class="progressbar color-green" data-progress="10">
												<span style="transform: translate3d(-70%, 0px, 0px);"></span>
											</div>
										</div>
									</div>
								</li>
								<li class="row">
									<div class="col-10">
										<i class="icon-dot icon-dot-yellow"></i>
									</div>
									<div class="col-70 medium-80">
										<span><?php esc_html_e( 'Social networks', 'leadee' ); ?></span>
										<small><?php esc_html_e( 'Facebook, Instagram, VK, etc', 'leadee' ); ?></small>
									</div>
									<div class="col-10 medium-40  hidden-lg">
										<div class="progressbar-block">
											<div class="amount-block">
												<span class="el1">0</span>
												<span class="el2">0%</span></div>
											<div class="progressbar color-yellow" data-progress="10">
												<span style="transform: translate3d(-30%, 0px, 0px);"></span>
											</div>
										</div>
									</div>
								</li>

								<li class="row">
									<div class="col-10">
										<i class="icon-dot icon-dot-red"></i>
									</div>
									<div class="col-70 medium-80">
										<span><?php esc_html_e( 'Referral traffic', 'leadee' ); ?></span>
										<small><?php esc_html_e( 'Websites, forums, blogs, etc.', 'leadee' ); ?></small>
									</div>

									<div class="col-10 medium-40  hidden-lg">
										<div class="progressbar-block">
											<div class="amount-block">
												<span class="el1">0</span>
												<span class="el2">0%</span></div>
											<div class="progressbar color-red" data-progress="10">
												<span style="transform: translate3d(-50%, 0px, 0px);"></span>
											</div>
										</div>
									</div>
								</li>

								<li class="row">
									<div class="col-10">
										<i class="icon-dot icon-dot-gray"></i>
									</div>
									<div class="col-70 medium-80">
										<span><?php esc_html_e( 'Direct traffic', 'leadee' ); ?></span>
										<small><?php esc_html_e( 'From those who entered the site address', 'leadee' ); ?></small>
									</div>

									<div class="col-10 medium-40  hidden-lg">
										<div class="progressbar-block">
											<div class="amount-block">
												<span class="el1">0</span>
												<span class="el2">0%</span></div>
											<div class="progressbar color-gray" data-progress="10">
												<span style="transform: translate3d(-10%, 0px, 0px);"></span>
											</div>
										</div>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
					<div class="col-100 medium-80 ord1-mobile col-md-100 hidden-mobile">
						<div id="source-chart">
							<div class="card-content card-content-padding">
								<div class="uk-card uk-card-default uk-card-body source-chart">
									<canvas class="chart" id="sourceChart" width="400"
											height="400"></canvas>
								</div>
							</div>
						</div>
					</div>
		</div>
	</div>
</section>
<section>
	<div data-pagination='{"el": ".swiper-pagination"}' data-space-between="10"
		data-slides-per-view="auto" data-centered-slides="true"
		class="swiper-container swiper-init swiper-mult demo-swiper-multiple-auto bottom-slider">
		<div class="swiper-pagination"></div>
		<div class="swiper-wrapper section-row" id="popular-screen-sizes">
			<div class="swiper-slide swiper-80 swiper-md-80 swiper-lg-40">
				<div class="card" id="popular-screen">
					<div class="card-content card-content-padding">
						<h2 class="title-medium"><?php esc_html_e( 'Popular screen sizes top 5', 'leadee' ); ?></h2>
						<div><?php esc_html_e( 'With these screen sizes, requests are left most often. Top 5', 'leadee' ); ?></div>
						<canvas class="chart" id="chartScreenSize" width="400" height="400"></canvas>
					</div>
				</div>
			</div>
			<div class="swiper-slide swiper-80 swiper-md-40 swiper-lg-20">
				<div class="card" id="popular-os">
					<div class="card-content card-content-padding">
						<h2 class="title-medium"><?php esc_html_e( 'OS clients', 'leadee' ); ?></h2>
						<div><?php esc_html_e( 'Operating systems. Top 5', 'leadee' ); ?></div>
						<ul class="os-clients" id="os-clients">
						</ul>
					</div>
				</div>
			</div>
			<div class="swiper-slide swiper-80 swiper-md-40 swiper-lg-40">
				<div class="card" id="popular-pages">
					<div class="card-content card-content-padding popular-widget ">
						<h2 class="title-medium"><?php esc_html_e( 'Popular Pages', 'leadee' ); ?></h2>
						<div class=""><?php esc_html_e( 'This is popular pages of clients leads. Top 5', 'leadee' ); ?></div>
						<div class="leads-from-block">
							<ul id="popular-pages-data">
								<li class="row">
									<div class="col-10">
									</div>
									<div class="col-50">
										<a class="button button-fill button-target button-green-style"><i
													class="leadee-icon icon-right-black"></i> <?php esc_html_e( 'View all', 'leadee' ); ?>
										</a>
									</div>
									<div class="col-40">
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
