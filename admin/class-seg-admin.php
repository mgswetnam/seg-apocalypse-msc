<?php

/**
 * Helper class for Beaver Builder child theme
 * @class SEG_Theme_Admin
 */

final class SEG_Theme_Admin {

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
    add_action( "admin_head", array( $this, "seg_cs_admin_setup" ) );
    // Filters
    //add_filter( "style_loader_tag", array( $this, "seg_remove_type_attr" ), 10, 2 );
    // Shortcodes
    //add_shortcode( "seg_render_title", array( $this, "seg_render_title" ) );
  }

  // -- Actions --

  public static function seg_cs_admin_setup(){
		// Scripts
    wp_enqueue_script( 'seg-cs-admin', SEG_THEME_URL . '/admin/assets/dist/js/admin.min.js', array('jquery'), SEG_VERSION );
    // Styles
    wp_enqueue_style( 'seg-cs-admin', SEG_THEME_URL . '/admin/assets/dist/css/admin.min.css', array(), SEG_VERSION );
  }

  // -- Filters --



  // -- Shortcodes --


}
