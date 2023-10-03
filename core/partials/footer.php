<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<section>
	<footer>
		<div class="card">
			<div class="copy">
				<div class="copy-text"><span><b><a href="https://leadee.io" target="_blank" rel="noopener noreferrer">Leadee</a></b> © <b
								class="b-date"><?php echo esc_html( date_i18n( 'Y' ) ); ?></b></span>
				</div>
				<div class="copy-addon-text"><span>Leads analytics and message storage plugin</span></div>
			</div>
		</div>
	</footer>
</section>
</div>
</div> 
</div>
<div class="leadee-preloader-area">
	<div class="leadee-preloader">
		<img src="<?php echo esc_url( LEADEE_PLUGIN_URL . '/core/assets/image/logo.png' ); ?>" alt="leadee">
	</div>
</div>
<?php
wp_admin_bar_render();
wp_print_styles();
wp_print_scripts();
?>
</body>
</html>
