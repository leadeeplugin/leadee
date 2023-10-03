<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div id="progress-bar">
	<div id="bar">
		<div id="main-info">
			<div class="smile-chart-smile">
				<div id="image">
					<img src="<?php echo esc_url( LEADEE_PLUGIN_URL . '/core/assets/libs/graf-target/img/first.png' ); ?>"
						alt="" id="leadee-graf-emotion">
				</div>
				<div id="target-progress-text-block">
					<div id="target-progress-num" class="progress-text">0</div>
					<div id="target-progress-text" class="progress-text"></div>
				</div>
			</div>
		</div>
		<div class="smile-chart-body">
			<svg>
				<g stroke-linecap="round">
					<path id="main-line" d="M 20 250 C 35 25 375 25 390 250"
							stroke="#E5F3D6"></path>
					<path id="indicator-line" d="M 20 250 C 35 25 375 25 390 250"></path>
				</g>
				<g fill="#DDDDDD">
					<circle class="circle"></circle>
					<circle class="circle"></circle>
					<circle class="circle"></circle>
					<circle class="circle"></circle>
					<circle class="circle"></circle>
					<circle class="circle"></circle>
					<circle class="circle"></circle>
					<circle class="circle"></circle>
					<circle class="circle"></circle>
					<circle class="circle"></circle>
					<circle class="circle"></circle>
					<circle class="circle"></circle>
					<circle class="circle"></circle>
					<circle class="circle"></circle>
					<circle class="circle"></circle>
					<circle class="circle"></circle>
					<circle class="circle"></circle>
					<circle class="circle"></circle>
					<circle class="circle"></circle>
					<circle class="circle"></circle>

				</g>
			</svg>
		</div>
		<div class="progress-text percent" id="current-percent">0%</div>
		<div class="progress-text percent" id="hundrend-percent">100%</div>
	</div>
</div>
