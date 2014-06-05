<?php
/**
 * Setting page
 *
 * @package   PT_Content_Views
 * @author    Palace Of Themes <palaceofthemes@gmail.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Palace Of Themes
 */
?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<style>
		.wrap * { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
		.wrap .row { border-bottom: 2px solid #ececec; }
		.wrap p, .wrap form { font-size: 14px; }
		.wrap h3 { font-size: 16px; font-weight: bold; color: #FF6A5A; }
		.wrap h6 { font-size: 15px; font-weight: bold; }
		.thumbnail { max-width: 100%; border: none !important; }
	</style>

	<?php

	ob_start();
	?>
		<p><br>Thank you for using Content Views!</p>
		<p>You are using <strong>Free</strong> version: <?php echo PT_CV_Functions::plugin_info( PT_CV_FILE, 'Version' ); ?></p>
		<p>Here is list of some of awesome features which are available at <a href="http://www.wordpressquery.com/?utm_source=settings_page&utm_medium=link&utm_campaign=content-views" target="_blank">Wordpress Query</a>.</p>

		<p>
			<a href="http://www.wordpressquery.com/pricing/?utm_source=settings_page&utm_medium=link&utm_campaign=content-views" target="_blank" class="btn btn-success">Upgrade now</a>
		</p>

		<div class="row">
			<div class="col-md-6">
				<h3>
					Query custom post types: Woocommerce products, FAQ...
				</h3>
				<div>
					<img class="thumbnail" src="<?php echo plugins_url( 'admin/assets/images/features/content-type.png', PT_CV_FILE ); ?>" />
				</div>
			</div>
			<div class="col-md-6">
				<h3>
					Post type specific order by options
				</h3>
				<div>
					<img class="thumbnail" src="<?php echo plugins_url( 'admin/assets/images/features/orderby.png', PT_CV_FILE ); ?>" />
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<h3>
					More beautiful & awesome layouts
				</h3>
				<div>
					<div class="row">
						<div class="col-md-6">
							<h6>Scrollable list</h6>
							<div>
								<img class="thumbnail" src="<?php echo plugins_url( 'admin/assets/images/features/scrollable.png', PT_CV_FILE ); ?>" />
							</div>
						</div>
						<div class="col-md-6">
							<h6>Collapsible list</h6>
							<div>
								<img class="thumbnail" src="<?php echo plugins_url( 'admin/assets/images/features/collapsible.png', PT_CV_FILE ); ?>" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<h6>Pinterest</h6>
							<div>
								<img class="thumbnail" src="<?php echo plugins_url( 'admin/assets/images/features/pinterest.png', PT_CV_FILE ); ?>" />
							</div>
						</div>
						<div class="col-md-6">
							<h6>Timeline</h6>
							<div>
								<img class="thumbnail" src="<?php echo plugins_url( 'admin/assets/images/features/timeline.png', PT_CV_FILE ); ?>" />
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<h3>
					Drag & drop to change display order of fields
				</h3>
				<div>
					<img class="thumbnail" src="<?php echo plugins_url( 'admin/assets/images/features/drag_drop.png', PT_CV_FILE ); ?>" />
				</div>
			</div>
			<div class="col-md-6">
				<h3>
					Read more settings
				</h3>
				<div>
					<img class="thumbnail" src="<?php echo plugins_url( 'admin/assets/images/features/readmore.png', PT_CV_FILE ); ?>" />
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<h3>
					Custom font settings
				</h3>
				<div>
					<img class="thumbnail" src="<?php echo plugins_url( 'admin/assets/images/features/font-settings.png', PT_CV_FILE ); ?>" />
				</div>
			</div>
			<div class="col-md-6">
				<h3>
					And more powerful options
				</h3>
				<div>
					<img class="thumbnail" src="<?php echo plugins_url( 'admin/assets/images/features/pagination.png', PT_CV_FILE ); ?>" />
					<img class="thumbnail" src="<?php echo plugins_url( 'admin/assets/images/features/openin.png', PT_CV_FILE ); ?>" />
					<br> <span style="font-size: 30px;">...</span>
				</div>
			</div>
		</div>

		<br>
		<p>Enjoy with Content Views!</p>
		<p>Plugin developed by PT guy (palaceofthemes@gmail.com)</p>
		<p>Copyright &COPY; 2014</p>
	<?php
	$text = ob_get_clean();

	$settings = apply_filters( PT_CV_PREFIX_ . 'page_settings', $text );

	echo $settings;
	?>
</div>
