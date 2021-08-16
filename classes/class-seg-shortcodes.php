<?php

/**
 * Stock shortcodes for SEG Theme
 * @class SEG_Theme_Shortcodes
 */

final class SEG_Theme_Shortcodes {

  private $version;
  public static $_instance;

  static function init(){
    if ( !self::$_instance ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public function __construct() {
    // shortcodes
    add_shortcode( "seg_year", array( $this, "seg_render_year" ) );
    add_shortcode( "seg_title", array( $this, "seg_render_title" ) );
    add_shortcode( "seg_social_icons", array( $this, "seg_render_social_icons" ) );
    add_shortcode( 'sitemap_pages', array( $this, 'seg_sitemap_pages' ) );
    add_shortcode( 'sitemap_posts', array( $this, 'seg_sitemap_posts' ) );
  }

  public function seg_render_year( $atts ){
    $a = shortcode_atts( array(
      'format' => 'Y',
      'wrapper' => 'span',
      'class' => ''
		), $atts );

    $search = array( '/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/<!--(.|\s)*?-->/' );
    $replace = array( '>', '<', '\\1', '' );
    $format = ( ( array_key_exists( 'format', $a ) )? $a[ 'format' ] : 'Y' );
    $wrapper = ( ( array_key_exists( 'wrapper', $a ) )? $a[ 'wrapper' ] : 'span' );
    $class = ( ( array_key_exists( 'class', $a ) )? ( ( $a[ 'class' ] != '' )? $a[ 'class' ] : NULL ) : NULL );
    $year = date( $format );
    ob_start();
    /* Begin content */
    ?>
    <<?=$wrapper?><?php echo ( ( $class )? ' class="'.$class.'"' : '' ) ?>><?=$year?></<?=$wrapper?>>
    <?php
    /* End content */
    $buffer =  ob_get_clean();
    $buffer = preg_replace( $search, $replace, $buffer );
    return $buffer;
  }

  public function seg_render_title( $atts ){
    global $post;
    $a = shortcode_atts( array(
			"post" => "",
      "formatted" => "true",
      'class' => ''
		), $atts );
    $title = ( ( array_key_exists( 'post', $a ) )? ( ( $a[ 'post' ] != '' )? get_the_title( $a[ 'post' ] ) : get_the_title( $post->ID ) ) : get_the_title( $post->ID ) );
    $formatted = ( ( array_key_exists( 'formatted', $a ) )? $a[ 'formatted' ] : 'true' );
    $class = ( ( array_key_exists( 'class', $a ) )? ( ( $a[ 'class' ] != '' )? $a[ 'class' ] : NULL ) : NULL );

    $search = array( '/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/<!--(.|\s)*?-->/' );
    $replace = array( '>', '<', '\\1', '' );

    ob_start();
    if( $formatted == "false" ){
      ?>
      <span<?php echo ( ( $class )? ' class="'.$class.'"' : '' ); ?>><?=$title?></span>
      <?php
      edit_post_link( _x( 'Edit', 'Edit page link text.', 'seg-theme' ) );
    } else {
      ?>
      <h1 class="seg-post-title<?php echo ( ( $class )? ' '.$class : '' ); ?>" itemprop="headline"><?=$title?></h1>
      <?php
      edit_post_link( _x( 'Edit', 'Edit page link text.', 'seg-theme' ) );
    }

    $buffer =  ob_get_clean();

    $buffer = preg_replace( $search, $replace, $buffer );

    return $buffer;
  }

  public function seg_render_social_icons( $atts ){
    $a = shortcode_atts( array(
      'bg' => 'square',
      'wrapper' => '',
      'class' => ''
		), $atts );
    // Set variables
    $bg = ( ( array_key_exists( 'bg', $a ) )? ( ( $a[ 'bg' ] !== '' )? $a[ 'bg' ] : NULL ) : NULL );
    $wrapper = ( ( array_key_exists( 'wrapper', $a ) )? $a[ 'wrapper' ] : '' );
    $class = ( ( array_key_exists( 'class', $a ) )? $a[ 'class' ] : '' );

    $search = array( '/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/<!--(.|\s)*?-->/' );
    $replace = array( '>', '<', '\\1', '' );

    $mods = get_theme_mods();
    $socials = array();
    foreach( $mods as $key=>$value ){
    	if( strpos( $key, 'seg_social_' ) !== false && $value !== '' ){
    		$socials[ $key ] = $value;
    	}
    }

    ob_start();
    ?>
    <div id="seg-social-icons" class="seg-social-icons <?=$a[ "class" ]?>">
      <?php
    	foreach( $socials as $k=>$v ){
        $platform = str_replace( "seg_social_", "", $k );
        $fab = "";
        $incrowd = array( 'facebook','youtube','instagram','tiktok','linkedin','snapchat','twitter','reddit','pinterest','discord','flickr','tumblr','twitch','yelp' );
        if( in_array( $platform, $incrowd ) === true ){
          switch( $platform ){
            case "facebook":{ $fab = "fab fa-facebook-f"; break; }
            case "youtube":{ $fab = "fab fa-youtube"; break; }
            case "instagram":{ $fab = "fab fa-instagram"; break; }
            case "tiktok":{ $fab = "fab fa-tiktok"; break; }
            case "linkedin":{ $fab = "fab fa-linkedin-in"; break; }
            case "snapchat":{ $fab = "fab fa-snapchat"; break; }
            case "twitter":{ $fab = "fab fa-linkedin-in"; break; }
            case "reddit":{ $fab = "fab fa-reddit"; break; }
            case "pinterest":{ $fab = "fab fa-pinterest-p"; break; }
            case "discord":{ $fab = "fab fa-discord"; break; }
            case "flickr":{ $fab = "fab fa-flickr"; break; }
            case "tumblr":{ $fab = "fab fa-tumblr"; break; }
            case "twitch":{ $fab = "fab fa-twitch"; break; }
            case "yelp":{ $fab = "fab fa-yelp"; break; }
          }
          ?>
          <a href="<?=$v?>" target="_blank" class="seg-icon-item-link" title="<?=ucfirst( $platform )?>">
            <?php
            if( $bg ){ ?>
              <span class="seg-icon-item-wrapper fa-stack fa-xs">
                <i class="seg-icon-bg <?=$platform?> fas fa-<?=$bg?> fa-stack-2x"></i>
                <i class="seg-icon-item <?=$platform?> <?=$fab?> fa-stack-1x"></i>
              </span>
              <?php
            } else {
            ?>
            <i class="<?=$fab?>"></i>
          <?php } ?>
          </a>
          <?php
        }
      }
      ?>
    </div>
    <?php
  	$buffer =  ob_get_clean();

    // We have to minimize the HTML because otherwise
    // line breaks are rendered incorrectly in widgets
    $buffer = preg_replace( $search, $replace, $buffer );
    return $buffer;
  }

  public function seg_sitemap_pages( $atts ){
    $a = shortcode_atts( array(
      'exclude' => '',
      'status' => 'publish'
		), $atts );
    $search = array( '/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/<!--(.|\s)*?-->/' );
    $replace = array( '>', '<', '\\1', '' );
    $exclude = ( ( array_key_exists( 'exclude', $a ) && $a[ 'exclude' ] != '' )? $a[ 'exclude' ] : NULL );
    if( is_null( $exclude ) ){
      global $wpdb;
      $posts_raw = $wpdb->get_results(
        $wpdb->prepare( "SELECT GROUP_CONCAT( post_id ) FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s", '_yoast_wpseo_meta-robots-noindex', '1' ),
        ARRAY_N
  		);
      $exclude = implode( ',', $posts_raw[ 0 ] );
    }
    $status = ( ( array_key_exists( 'status', $a ) && $a[ 'status' ] != '' )? $a[ 'status' ] : 'publish' );
    ob_start();
    /*echo "<pre>";
    var_dump( $posts_raw );
    echo "</pre>";*/
    ?>
    <ul>
    <?php
    wp_list_pages( array(
      'exclude' => $exclude,
      'title_li' => '',
      'post_status' => $status,
    ) );
    ?>
    </ul>
    <?php
    $buffer = ob_get_clean();
		// We have to minimize the HTML because otherwise
    // line breaks are rendered incorrectly in widgets
		$buffer = preg_replace( $search, $replace, $buffer );
    return $buffer;
  }

  public function seg_sitemap_posts( $atts ){
    $a = shortcode_atts( array(
      'exclude' => '',
      'status' => 'publish'
		), $atts );
    $search = array( '/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/<!--(.|\s)*?-->/' );
    $replace = array( '>', '<', '\\1', '' );

    ob_start();

    $args = array(
      'posts_per_page' => -1,
      'post_type' => 'post',
      'post_status' => 'publish',
      'orderby' => 'date',
      'order' => 'DESC'
    );
    $posts_raw = new WP_Query( $args );

    /*echo "<pre>";
    var_dump( $posts_raw );
    echo "</pre>";*/
    $year = '';
    $month = '';
    $articles = '';
    foreach( $posts_raw->posts as $item ){
      $newyear = ( ( $year == get_the_date( 'Y', $item->ID ) )? $year : get_the_date( 'Y', $item->ID ) );
      $newmonth = ( ( $month == get_the_date( 'F', $item->ID ) )? $month : get_the_date( 'F', $item->ID ) );

      if( $month != $newmonth && $month != '' ){
        ?>
        <ul><?=$articles?></ul>
        <?php
        $articles = '<li><a href="' . get_permalink( $item->ID ) . '">' . $item->post_title . '</a></li>';
      } else {
        $articles .= '<li><a href="' . get_permalink( $item->ID ) . '">' . $item->post_title . '</a></li>';
      }

      if( $year != $newyear ){
        $year = $newyear;
        ?>
        <h3><?=$year?></h3>
        <?php
      }

      if( $month != $newmonth ){
        ?>
        <h4><?=$month?></h4>
        <?php
      }

      if( $month != $newmonth ){
        $month = $newmonth;
      }
    }
    wp_reset_postdata();
    $buffer = ob_get_clean();
		// We have to minimize the HTML because otherwise
    // line breaks are rendered incorrectly in widgets
		$buffer = preg_replace( $search, $replace, $buffer );
    return $buffer;
  }

}
