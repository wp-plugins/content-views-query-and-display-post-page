<?php
/**
 * Layout Name: Collapsible List
 *
 * @package   PT_Content_Views
 * @author    PT Guy <palaceofthemes@gmail.com>
 * @license   GPL-2.0+
 * @link      http://www.contentviewspro.com/
 * @copyright 2014 PT Guy
 */
$html = array();

$layout = $dargs[ 'layout-format' ];

// Prevent the case: there are 2 columns but have not setting for thumbnail position
if ( $layout == '2-col' && !isset( $dargs[ 'field-settings' ][ 'thumbnail' ] ) ) {
	$layout = '1-col';
}

// Get title
$heading = isset( $fields_html[ 'title' ] ) ? $fields_html[ 'title' ] : '';
unset( $fields_html[ 'title' ] );

switch ( $layout ) {
	case '1-col':
		foreach ( $fields_html as $field_html ) {
			$html[] = $field_html;
		}
		break;
	case '2-col':

		// Thumbnail html
		$thumbnail_html = $fields_html[ 'thumbnail' ];

		// Other fields html
		unset( $fields_html[ 'thumbnail' ] );
		$others_html = implode( "\n", $fields_html );

		$html[]	 = $thumbnail_html;
		$html[]	 = $others_html;

		break;
}

$random_id	 = PT_CV_Functions::string_random();
?>
<div class="panel panel-default pt-cv-content-item">
	<div class="panel-heading">
		<a class="panel-title" data-toggle="collapse" data-parent="#<?php echo esc_attr( PT_CV_PREFIX_UPPER . 'ID' ); ?>" href="#<?php echo esc_attr( $random_id ); ?>">
			<?php echo balanceTags( strip_tags( $heading ) ); ?>
		</a>
		<?php
		// Custom toggle icon
		$toggle_icon = apply_filters( PT_CV_PREFIX_ . 'scrollable_toggle_icon', '' );
		echo balanceTags( $toggle_icon );
		?>
	</div>
	<div id="<?php echo esc_attr( $random_id ); ?>" class="panel-collapse collapse <?php echo esc_attr( PT_CV_PREFIX_UPPER . 'CLASS' ); ?>">
		<div class="panel-body">
			<?php
			echo balanceTags( implode( "\n", $html ) );
			?>
		</div>
	</div>
</div>