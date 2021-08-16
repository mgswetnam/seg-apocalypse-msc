<?php

/**
 * Custom taxonomy factory for SEG Theme
 * @class SEG_Theme_Taxonomies
 */

final class SEG_Theme_Taxonomies {

  private $version;
  public static $_instance;

  static function init(){
    if ( !self::$_instance ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public function __construct() {
    // Nothing needed here
  }

	static public function seg_add_custom_taxonomy() {
    include SEG_THEME_DIR ."/defs/taxonomies.php";
    if( !empty( $taxonomies ) ){
      foreach( $taxonomies as $taxonomy ){
        // Define variables from array
        $singular = ( ( array_key_exists( "singular", $taxonomy ) )? $taxonomy[ "singular" ] : NULL );
        $plural = ( ( array_key_exists( "plural", $taxonomy ) )? $taxonomy[ "plural" ] : NULL );
        $labels = ( ( $singular && $plural )? self::define_taxonomy_labels( $singular, $plural ) : NULL );
        $object_type = ( ( array_key_exists( "object_type", $taxonomy ) )? $taxonomy[ "object_type" ] : 'post' );
        $public = ( ( array_key_exists( "public", $taxonomy ) )? $taxonomy[ "public" ] : true );
        $publicly_queryable = ( ( array_key_exists( "publicly_queryable", $taxonomy ) )? $taxonomy[ "publicly_queryable" ] : $public );
        $show_ui = ( ( array_key_exists( "show_ui", $taxonomy ) )? $taxonomy[ "show_ui" ] : $public );
        $show_in_menu = ( ( array_key_exists( "show_in_menu", $taxonomy ) )? $taxonomy[ "show_in_menu" ] : $show_ui );
        $show_in_nav_menus = ( ( array_key_exists( "show_in_nav_menus", $taxonomy ) )? $taxonomy[ "show_in_nav_menus" ] : $public );
        $show_in_rest = ( ( array_key_exists( "show_in_rest", $taxonomy ) )? $taxonomy[ "show_in_rest" ] : true );
        $rest_base = ( ( array_key_exists( "rest_base", $taxonomy ) )? $taxonomy[ "rest_base" ] : strtolower( $singular ) );
        //$rest_controller_class = ( ( array_key_exists( "rest_controller_class", $taxonomy ) )? $taxonomy[ "rest_controller_class" ] : WP_REST_Terms_Controller );
        $show_tagcloud = ( ( array_key_exists( "show_tagcloud", $taxonomy ) )? $taxonomy[ "show_tagcloud" ] : $show_ui );
        $show_in_quick_edit = ( ( array_key_exists( "show_in_quick_edit", $taxonomy ) )? $taxonomy[ "show_in_quick_edit" ] : $show_ui );
        //$meta_box_cb = ( ( array_key_exists( "meta_box_cb", $taxonomy ) )? $taxonomy[ "meta_box_cb" ] : NULL );
        $show_admin_column = ( ( array_key_exists( "show_admin_column", $taxonomy ) )? $taxonomy[ "show_admin_column" ] : false );
        $description = ( ( array_key_exists( "description", $taxonomy ) )? __( $taxonomy[ "description" ], 'seg_theme_'.strtolower( $singular ) ) : false );
        $hierarchical = ( ( array_key_exists( "hierarchical", $taxonomy ) )? $taxonomy[ "hierarchical" ] : false );
        //$update_count_callback = ( ( array_key_exists( "update_count_callback", $taxonomy ) )? $taxonomy[ "update_count_callback" ] : '' );
        $query_var = ( ( array_key_exists( "query_var", $taxonomy ) )? $taxonomy[ "query_var" ] : strtolower( $singular ) );
        $rewrite = ( ( array_key_exists( "rewrite", $taxonomy ) )? $taxonomy[ "rewrite" ] : true );
        $capabilities = ( ( array_key_exists( "capabilities", $taxonomy ) )? $taxonomy[ "capabilities" ] : array( 'manage_terms' => 'manage_categories', 'edit_terms' => 'manage_categories', 'delete_terms' => 'manage_categories', 'assign_terms' => 'edit_posts' ) );
        $sort = ( ( array_key_exists( "sort", $taxonomy ) )? $taxonomy[ "sort" ] : false );
        // Define arguments array
        $args = array(
    			'labels' => $labels,
          'public' => $public,
          'publicly_queryable' => $publicly_queryable,
          'show_ui' => $show_ui,
          'show_in_menu' => $show_in_menu,
          'show_in_nav_menus' => $show_in_nav_menus,
          'show_in_rest' => $show_in_rest,
          'rest_base' => $rest_base,
          //'rest_controller_class' => $rest_controller_class,
          'show_tagcloud' => $show_tagcloud,
          'show_in_quick_edit' => $show_in_quick_edit,
          //'meta_box_cb' => $meta_box_cb,
          'show_admin_column' => $show_admin_column,
          'description' => $description,
          'hierarchical' => $hierarchical,
          //'update_count_callback' => $update_count_callback,
          'query_var' => $query_var,
          'rewrite' => $rewrite,
          'capabilities' => $capabilities,
          'sort' => $sort,
        );
        register_taxonomy( strtolower( $singular ), $object_type, $args );
      }
    }
	}

  static public function define_taxonomy_labels( $singular, $plural ) {
    $labels = array(
      'name' => __( ucwords( $plural ), 'seg_theme_'.strtolower( $singular ) ),
      'singular_name' => __( ucwords( $singular ), 'seg_theme_'.strtolower( $singular ) ),
      'menu_name' => __( ucwords( $plural ), 'seg_theme_'.strtolower( $singular ) ),
      'all_items' => __( 'All '.ucwords( $plural ), 'seg_theme_'.strtolower( $singular ) ),
      'edit_item' => __( 'Edit '.ucwords( $singular ), 'seg_theme_'.strtolower( $singular ) ),
      'view_item' => __( 'View '.ucwords( $singular ), 'seg_theme_'.strtolower( $singular ) ),
      'update_item' => __( 'Update '.ucwords( $singular ), 'seg_theme_'.strtolower( $singular ) ),
      'add_new_item' => _x( 'Add New', strtolower( $singular ), 'seg_theme_'.strtolower( $singular ) ),
      'new_item_name' => _x( 'New', strtolower( $singular ), 'seg_theme_'.strtolower( $singular ) ),
      'parent_item' => __( 'Parent '.ucwords( $singular ), 'seg_theme_'.strtolower( $singular ) ),
      'parent_item_colon' => __( 'Parent '.ucwords( $plural ).':', 'seg_theme_'.strtolower( $singular ) ),
      'search_items' => __( 'Search '.ucwords( $plural ), 'seg_theme_'.strtolower( $singular ) ),
      'popular_items' => __( 'Popular '.ucwords( $plural ), 'seg_theme_'.strtolower( $singular ) ),
      'separate_items_with_commas' => __( 'Separate '.ucwords( $plural ).' With Commas', 'seg_theme_'.strtolower( $singular ) ),
      'add_or_remove_items' => __( 'Add or Remove '.ucwords( $plural ), 'seg_theme_'.strtolower( $singular ) ),
      'choose_from_most_used' => __( 'Choose '.ucwords( $singular ).' From Most Used', 'seg_theme_'.strtolower( $singular ) ),
      'not_found' => __( 'No '.strtolower( $plural ).' found.', 'seg_theme_'.strtolower( $singular ) ),
		);
    return $labels;
  }

  static public function seg_add_taxonomy_filter(){
    global $typenow;
    include SEG_THEME_DIR ."/defs/taxonomies.php";
    if( !empty( $taxonomies ) ){
      foreach( $taxonomies as $taxonomy ){
        $singular = ( ( array_key_exists( "singular", $taxonomy ) )? $taxonomy[ "singular" ] : NULL );
        $plural = ( ( array_key_exists( "plural", $taxonomy ) )? $taxonomy[ "plural" ] : NULL );
        $object_type = ( ( array_key_exists( "object_type", $taxonomy ) )? $taxonomy[ "object_type" ] : 'post' );
        $filter = ( ( array_key_exists( "filter", $taxonomy ) )? $taxonomy[ "filter" ] : false );
        if( $typenow == $object_type && $filter === true ){
          $taxonomy_names = array( strtolower( $singular ) );
          foreach( $taxonomy_names as $single_taxonomy ){
            $current_taxonomy = isset( $_GET[ $single_taxonomy ] ) ? $_GET[ $single_taxonomy ] : '';
            $taxonomy_object = get_taxonomy( $single_taxonomy );
            $taxonomy_name = strtolower( $taxonomy_object->labels->name );
            $taxonomy_terms = get_terms( $single_taxonomy );
            if( count( $taxonomy_terms ) > 0 ){
              echo "<select name='$single_taxonomy' id='$single_taxonomy' class='postform'>";
              echo "<option value=''>All $taxonomy_name</option>";
              foreach( $taxonomy_terms as $single_term ){
                echo '<option value='. $single_term->slug, $current_taxonomy == $single_term->slug ? ' selected="selected"' : '','>' . $single_term->name .' (' . $single_term->count .')</option>';
              }
              echo "</select>";
            }
          }
        }
      }
    }
  }

}
