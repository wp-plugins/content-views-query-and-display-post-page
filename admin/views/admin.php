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

	<?php
	//	Do_action( PT_CV_PREFIX_ . 'page_settings' );

	ob_start();
	?>
<p><br>Thank you for using Content Views!</p>
<p>You are using Free version: <?php echo PT_CV_Functions::plugin_info( PT_CV_FILE, 'Version' ); ?></p>
<p>More awesome features are available at <a href="http://wordpressquery.com" target="_blank">Wordpress Query</a>.</p>
<p><br>Enjoy with Content Views!</p>
<p>---<br>
Plugin developed by PT guy.<br>
Copyright © 2014</p>
	<?php
	$pro_introduction = ob_get_clean();

	$settings = apply_filters( PT_CV_PREFIX_ . 'page_settings', $pro_introduction );

	echo $settings;
	?>
</div>
