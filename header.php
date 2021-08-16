<?php
/**
 * The header for Astra Theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?><!DOCTYPE html>
<?php astra_html_before(); ?>
<html <?php language_attributes(); ?>>
<head>
<?php echo ( ( get_option( "seg_priorityheader" ) != "" )? get_option( "seg_priorityheader" ) : "" ); ?>
<?php astra_head_top(); ?>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php
// Begin Facebook Pixel Code
$custom = ( ( $post )? get_post_custom( $post->ID ) : NULL );
$fbpixel = ( ( $post )? ( ( array_key_exists( "csa_field_custom_fbpixel", $custom ) === true )? ( ( array_key_exists( 0, $custom[ "csa_field_custom_fbpixel" ] ) === true )? unserialize( $custom[ "csa_field_custom_fbpixel" ][ 0 ] ) : NULL ) : NULL ) : NULL );
$fbpixel = ( ( is_array( $fbpixel ) )? $fbpixel : array( $fbpixel ) );
$fbpixelstring = "";
foreach( $fbpixel as $tag ){
	if( $tag != "0" ){
		$fbpixelstring .= ( ( $tag != "" )? "fbq('track','".$tag."');\n" : "" );
	}
}
if( get_option( 'canadensis_radio_facebook' ) == 'yes' ){
	echo "<!-- Begin Facebook Pixel -->";
	echo ( ( get_option( "csa_tracking_fbpixelcode" ) != "" )? sprintf( get_option( "csa_tracking_fbpixelcode" ), $fbpixelstring ) : "" );
	echo "<!-- End Facebook Pixel -->";
}
// End Facebook Pixel Code
?>
<?php wp_head(); ?>
<?php echo ( ( get_option( "seg_regularheader" ) != "" )? get_option( "seg_regularheader" ) : "" ); ?>
<?php astra_head_bottom(); ?>
</head>

<body <?php astra_schema_body(); ?> <?php body_class(); ?>>
<?php echo ( ( get_option( "seg_bodyheading" ) != "" )? get_option( "seg_bodyheading" ) : "" ); ?>
<?php astra_body_top(); ?>
<?php wp_body_open(); ?>
<div <?php
	echo astra_attr(
		'site',
		array(
			'id'    => 'page',
			'class' => 'hfeed site',
		)
	);
	?>
>
	<a class="skip-link screen-reader-text" href="#content"><?php echo esc_html( astra_default_strings( 'string-header-skip-link', false ) ); ?></a>
	<?php
	astra_header_before();

	astra_header();

	astra_header_after();

	astra_content_before();
	?>
	<div id="content" class="site-content">
		<div class="ast-container seg-container">
		<?php astra_content_top(); ?>
