<?php

/**
 * Helper class for Beaver Builder child theme
 * @class SEG_Theme_Core
 */

final class SEG_Theme_Core {

  private $version;
  public static $_instance;

  static function init(){
    if ( !self::$_instance ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public function __construct() {
    // Actions
    add_action( "wp_print_scripts", array( $this, "seg_theme_setup" ) );
		add_action( 'add_meta_boxes', array( $this, 'seg_add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'seg_save_custom' ) );
    // Filters
    add_filter( "style_loader_tag", array( $this, "seg_remove_type_attr" ), 10, 2);
    add_filter( "script_loader_tag", array( $this, "seg_remove_type_attr" ), 10, 2);
    // Shortcodes
    add_shortcode( "seg_title", array( $this, "seg_render_title" ) );
  }

  public static function seg_theme_setup(){
		// Scripts
    wp_enqueue_script( "jquery" );
    wp_enqueue_script( "seg-ca-modernizr", SEG_THEME_URL . "/assets/dist/js/vendor/modernizr.min.js", array(), SEG_VERSION );
    wp_enqueue_script( "seg-ca-main", SEG_THEME_URL . "/assets/dist/js/main.min.js", array( "jquery" ), SEG_VERSION );
    // Styles
    wp_enqueue_style( "seg-ca-main", SEG_THEME_URL . "/assets/dist/css/main.min.css", array(), SEG_VERSION );
    if( get_option( "seg_include_bootstrap" ) == "yes" ){
      wp_enqueue_style( "bootstrap", SEG_THEME_URL . "/assets/dist/css/vendor/bootstrap/bootstrap.min.css", array(), SEG_VERSION );
    }
    if( get_option( "seg_include_fontawesome" ) == "yes" ){
      wp_enqueue_style( "font-awesome", SEG_THEME_URL . "/assets/dist/css/vendor/fontawesome/css/fontawesome.min.css", array(), SEG_VERSION );
    }
  }

	public function seg_add_meta_boxes(){
    $metabox = new SEG_Theme_Metaboxes();
    $metabox->seg_add_meta_boxes();
	}

	public function seg_save_custom(){
    $metabox = new SEG_Theme_Metaboxes();
    $metabox->seg_save_custom_fields();
	}

  // This is necessary to remove type tag from scripts and styles
  // Revisit if Wordpress changes the way they load scripts and styles
  public static function seg_remove_type_attr($tag, $handle) {
    return preg_replace( "/type=['\"]text\/(javascript|css)['\"]/", '', $tag );
  }

  public function seg_render_title( $atts ){
    $a = shortcode_atts( array(
			"post" => "",
      "formatted" => "true"
		), $atts );

    $search = array( '/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/<!--(.|\s)*?-->/' );
    $replace = array( '>', '<', '\\1', '' );

    ob_start();
    $title = get_the_title( $a[ "post" ] );
    if( $a[ "formatted" ] == "false" ){
      ?>
      <span><?=$title?></span>
      <?php
      edit_post_link( _x( 'Edit', 'Edit page link text.', 'seg-theme' ) );
    } else {
      ?>
      <h1 class="seg-post-title" itemprop="headline"><?=$title?></h1>
      <?php
      edit_post_link( _x( 'Edit', 'Edit page link text.', 'seg-theme' ) );
    }

    $buffer =  ob_get_clean();

    $buffer = preg_replace( $search, $replace, $buffer );

    return $buffer;
  }

	/**
	 * Returns a response container to assure uniform response arrays
	 *
	 * @since    1.0.0
	 */
	public static function seg_theme_response_container() {
		$response = array(
			"error" => false,
			"message" => array(),
			"content" => array()
		);
		return $response;
	}
}
