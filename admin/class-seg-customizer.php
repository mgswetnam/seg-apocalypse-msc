<?php

/**
 * Customizer class for Beaver Builder child theme
 * @class SEG_Theme_Customizer
 */

final class SEG_Theme_Customizer {

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
    add_action( "customize_register", array( $this, "seg_customizer" ) );
    add_action( "customize_controls_enqueue_scripts", array( $this, "seg_customizer_setup" ) );
    // Filters
    //add_filter( "style_loader_tag", array( $this, "seg_remove_type_attr" ), 10, 2 );
    // Shortcodes
    //add_shortcode( "seg_render_title", array( $this, "seg_render_title" ) );
  }

  // -- Actions --

  public static function seg_customizer_setup(){
		// Scripts
    wp_enqueue_script( 'seg-admin-customizer', SEG_THEME_URL . '/admin/assets/dist/js/customizer.min.js', array('jquery'), true );
  }

  public static function seg_customizer( $wp_customize ){
    // Get Customizer array
    include SEG_THEME_DIR ."/defs/customizer.php";
    // Iterate through the array
    foreach( $customizer as $pkey => $panel ){
      $panelid = $pkey;
      $ptitle = ( ( array_key_exists( "ptitle", $panel ) )? $panel[ "ptitle" ] : NULL );
      $pdomain = ( ( array_key_exists( "pdomain", $panel ) )? $panel[ "pdomain" ] : NULL );
      $pcapability = ( ( array_key_exists( "pcapability", $panel ) )? $panel[ "pcapability" ] : NULL );
      $psections = ( ( array_key_exists( "psections", $panel ) )? $panel[ "psections" ] : NULL );
      // Add Panel
      $wp_customize->add_panel( new WP_Customize_Panel( $wp_customize, "$panelid", array(
          "title" => __( "$ptitle", "$pdomain" ),
          "capability"  => "$pcapability",
      ) ) );

      // Add Section
      foreach( $psections as $skey => $section ){
        $sectionid = $skey;
        $stitle = ( ( array_key_exists( "stitle", $section ) )? $section[ "stitle" ] : NULL );
        $sdomain = ( ( array_key_exists( "sdomain", $section ) )? $section[ "sdomain" ] : NULL );
        $spriority = ( ( array_key_exists( "spriority", $section ) )? $section[ "spriority" ] : NULL );
        $scapability = ( ( array_key_exists( "scapability", $section ) )? $section[ "scapability" ] : NULL );
        $sdescription = ( ( array_key_exists( "sdescription", $section ) )? $section[ "sdescription" ] : NULL );
        $sfields = ( ( array_key_exists( "sfields", $section ) )? $section[ "sfields" ] : NULL );
        $wp_customize->add_section( $sectionid, array(
          "title" => __( "$stitle", "$sdomain" ),
          "priority" => $spriority,
          "capability" => "$scapability",
          "description" => "$sdescription",
          "panel" => "$panelid"
        ) );

        // Add Controls/Settings
        foreach( $sfields as $fkey => $field ){
          $fieldid = $fkey;
          $fdefault = ( ( array_key_exists( "fdefault", $field ) )? $field[ "fdefault" ] : NULL );
          $fcapability = ( ( array_key_exists( "fcapability", $field ) )? $field[ "fcapability" ] : NULL );
          $fstype = ( ( array_key_exists( "fstype", $field ) )? $field[ "fstype" ] : NULL );
          $ftype = ( ( array_key_exists( "ftype", $field ) )? $field[ "ftype" ] : NULL );
          $flabel = ( ( array_key_exists( "flabel", $field ) )? $field[ "flabel" ] : NULL );
          $fdomain = ( ( array_key_exists( "fdomain", $field ) )? $field[ "fdomain" ] : NULL );
          $fsettings = ( ( array_key_exists( "fsettings", $field ) )? $field[ "fsettings" ] : NULL );
          $fchoices = ( ( array_key_exists( "fchoices", $field ) )? $field[ "fchoices" ] : NULL );
          $fattributes = ( ( array_key_exists( "fattributes", $field ) )? $field[ "fattributes" ] : array() );
          // Add Setting
          $wp_customize->add_setting( "$fsettings", array(
            "default" => "$fdefault",
            "capability" => "$fcapability",
            "type" => "$fstype"
          ) );
          // Add Control
          switch( $ftype ){
            case "imagecontrol":{
              $wp_customize->add_control(
                new WP_Customize_Image_Control(
                  $wp_customize,
                  "$fieldid",
                  array(
                    "label" => __( "$flabel", "$fdomain" ),
                    "settings" => "$fsettings",
                    "section" => "$sectionid"
                  )
                )
              );
              break;
            }
            case "radio":
            case "select":{
              $wp_customize->add_control( "$fieldid", array(
                "label" => __( "$flabel", "$fdomain" ),
                "type" => "$ftype",
                "section" => "$sectionid",
                "settings" => "$fsettings",
                "choices" => $fchoices,
                "input_attrs" => $fattributes
              ) );
              break;
            }
            default:{
              $wp_customize->add_control( "$fieldid", array(
                "label" => __( "$flabel", "$fdomain" ),
                "type" => "$ftype",
                "section" => "$sectionid",
                "settings" => "$fsettings",
                "input_attrs" => $fattributes
              ) );
            }
          }
        }
      }
    }
  }
}
