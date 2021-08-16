<?php

/**
 * Widget for displaying a list of specified links with icons
 * @class SEG_Featured_Links_Redux
 */

if ( ! class_exists( 'SEG_Featured_Links_Redux' ) ) {
  class SEG_Featured_Links_Redux extends WP_Widget
  {
    private $version;
    public static $_instance;

    static function init(){
      if ( !self::$_instance ) {
        self::$_instance = new self();
      }
      return self::$_instance;
    }

    public function __construct(){
      parent::__construct(
        'SEG_Featured_Links_Redux', // Base ID
        __('SEG Featured Links', 'theme-seg'), // Name
        array( 'classname' => 'featured-links-widget', 'description' =>__('Displays list of featured links in the sidebar w/ icons.', 'theme-seg'), ) // Args
      );
    }

    public function form( $instance ){
      // Get variables
      $instance = wp_parse_args( ( array ) $instance, array( 'heading' => '' ) );
      $heading = ( ( array_key_exists( 'heading', $instance ) )? $instance[ 'heading' ] : NULL );
      $title = ( ( array_key_exists( 'title', $instance ) )? $instance[ 'title' ] : NULL );
      $desc = ( ( array_key_exists( 'desc', $instance ) )? $instance[ 'desc' ] : NULL );
      $url = ( ( array_key_exists( 'url', $instance ) )? $instance[ 'url' ] : NULL );
      $icon = ( ( array_key_exists( 'icon', $instance ) )? $instance[ 'icon' ] : NULL );
      ?>
      <div class="field-wrapper">
        <label for="<?php echo esc_attr( $this->get_field_id( 'heading' ) ); ?>"><?php _e( 'Heading', 'theme-seg' ); ?>
          <input class="upcoming seg-featured-links text-field link-heading" id="<?php echo esc_attr( $this->get_field_id( 'heading' ) ); ?>" size='40' name="<?php echo esc_attr( $this->get_field_name( 'heading' ) ); ?>" type="text" value="<?php echo esc_attr( $heading ); ?>" />
        </label>
      </div>
      <!-- Begin Links -->
      <div id="<?php echo $this->get_field_id( 'seg-featured-links-wrapper' ); ?>" class="seg-admin seg-featured-link-wrapper">
        <?php
        if( $url && count( $url ) > 0 ){
          $i=0;
          foreach( $url as $item ){
        ?>
        <fieldset id="<?php echo esc_attr( $this->get_field_id( 'seg-featured-link' ) ); ?>|<?=$i+1?>" class="seg-admin seg-featured-link" data-link-number="<?=$i+1?>" data-id-prefix="<?php echo esc_attr( $this->get_field_id( '' ) ); ?>">
          <legend>
            Link <?=$i+1?>
            <span>
              <i id="<?php echo $this->get_field_id( 'seg-remove-link' ); ?>-<?=$i+1?>" class="seg-remove-link dashicons dashicons-dismiss"></i>
            </span>
            <span>
              <i id="<?php echo $this->get_field_id( 'seg-up-link' ); ?>-<?=$i+1?>" class="seg-up-link dashicons dashicons-arrow-up-alt2"></i>
            </span>
            <span>
              <i id="<?php echo $this->get_field_id( 'seg-down-link' ); ?>-<?=$i+1?>" class="seg-down-link dashicons dashicons-arrow-down-alt2"></i>
            </span>
          </legend>
          <div class="field-wrapper title-wrap">
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>-<?=$i+1?>"><?php _e( 'Title', 'theme-seg' ); ?>
              <input class="upcoming" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>-<?=$i+1?>" size='40' name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>[]" type="text" value="<?php echo esc_attr( $title[ $i ] ); ?>" />
            </label>
          </div>
          <div class="field-wrapper desc-wrap">
            <label for="<?php echo esc_attr( $this->get_field_id( 'desc' ) ); ?>-<?=$i+1?>"><?php _e( 'Desc.', 'theme-seg' ); ?>
              <input class="upcoming" id="<?php echo esc_attr( $this->get_field_id( 'desc' ) ); ?>-<?=$i+1?>" size='40' name="<?php echo esc_attr( $this->get_field_name( 'desc' ) ); ?>[]" type="text" value="<?php echo esc_attr( $desc[ $i ] ); ?>" />
            </label>
          </div>
          <div class="field-wrapper url-wrap">
            <label for="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>-<?=$i+1?>"><?php _e( 'URL', 'theme-seg' ); ?>
              <input class="upcoming" id="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>-<?=$i+1?>" size='40' name="<?php echo esc_attr( $this->get_field_name( 'url' ) ); ?>[]" type="text" value="<?php echo esc_attr( $url[ $i ] ); ?>" />
            </label>
          </div>
          <div class="field-wrapper icon-wrap">
            <label for="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>-<?=$i+1?>"><?php _e( 'Icon', 'theme-seg' ); ?>
              <input class="upcoming" id="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>-<?=$i+1?>" size='40' name="<?php echo esc_attr( $this->get_field_name( 'icon' ) ); ?>[]" type="text" value="<?php echo esc_attr( $icon[ $i ] ); ?>" />
            </label>
          </div>
        </fieldset>
        <?php
            $i++;
          }
        }
        ?>
      </div>
      <div class="seg-admin button-bar">
        <input type="button" id="<?php echo $this->get_field_id( 'seg-add-link' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'seg-add-link' ) ); ?>" class="seg-featured-links form-button" title="Add Link" value="+" />
      </div>
      <?php
    }

    public function update( $new_instance, $old_instance )
    {
      $instance = $old_instance;
      $instance['heading'] = $new_instance['heading'];
      // Links array
      $instance['title'] = $new_instance['title'];
      $instance['desc'] = $new_instance['desc'];
      $instance['url'] = $new_instance['url'];
      $instance['icon'] = $new_instance['icon'];

      return $instance;
    }

    public function widget( $args, $instance )
    {
      extract( $args, EXTR_SKIP );
      $tl = 6;
      $heading = ( ( empty( $instance[ 'heading' ] ) )? '' : apply_filters( 'widget_title', $instance[ 'heading' ] ) );
      $heading = htmlspecialchars_decode( stripslashes( $heading ) );
      // Get other variables
      $title = ( ( array_key_exists( "title", $instance ) )? $instance[ "title" ] : array() );
      $desc = ( ( array_key_exists( "desc", $instance ) )? $instance[ "desc" ] : array() );
      $url = ( ( array_key_exists( "url", $instance ) )? $instance[ "url" ] : array() );
      $icon = ( ( array_key_exists( "icon", $instance ) )? $instance[ "icon" ] : array() );

      ob_start();
      echo $args['before_widget'];
      /*echo "<pre>";
      print_r( $instance );
      echo "</pre>";*/
  		if ( !empty( $instance[ 'heading' ] ) ) {
  			echo $args['before_title'] . $instance[ 'heading' ] . $args['after_title'];
  		}
      if( count( $url ) > 0 ){ ?>
        <div class="seg-featured-links link-wrapper">
        <?php
        for( $i=0; $i<count( $url ); $i++ ){
          ?>
          <a href="<?=$url[ $i ]?>" class="outter-link" target="self">
            <div class="item-wrapper">
              <div class="item-icon-wrapper"><i class="<?=$icon[ $i ]?>"></i></div>
              <div class="item-text-wrapper">
                <div class="item-title"><?=$title[ $i ]?></div>
                <?php if( $desc[ $i ] != "" ){ ?>
                <div class="item-description"><?=$desc[ $i ]?></div>
                <?php } ?>
              </div>
            </div>
          </a>
          <?php
        }
        ?>
        </div>
        <?php
      }
      echo $args['after_widget'];
      $buffer =  ob_get_clean();
      echo $buffer;
    }
  }
}
$fl_callback = function(){ return register_widget( "SEG_Featured_Links_Redux" ); };
add_action( 'widgets_init', $fl_callback );

/**
 * Widget for displaying a list of recent articles with featured images
 * @class SEG_Latest_Articles
 */

if (! class_exists('SEG_Latest_Articles')) {
  class SEG_Latest_Articles extends WP_Widget{
    private $version;
    public static $_instance;

    static function init(){
      if ( !self::$_instance ) {
        self::$_instance = new self();
      }
      return self::$_instance;
    }

    public function __construct(){
      parent::__construct(
        'SEG_Latest_Articles', // Base ID
        __('SEG Latest Articles', 'theme-seg'), // Name
        array( 'classname' => 'latest_articles_widget', 'description' =>__( 'Displays a specified number of recent posts in descending date order with thumbnail.', 'theme-seg' ), ) // Args
      );
    }

    public function form( $instance ){
      $instance = wp_parse_args( ( array ) $instance, array( 'title' => '' ) );
      $title = ( ( array_key_exists( 'title', $instance ) )? $instance[ 'title' ] : NULL );
      $postnum = ( ( array_key_exists( 'postnum', $instance ) )? $instance[ 'postnum' ] : NULL );

      ?>
      <!-- Title -->
      <p>
        <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title', 'theme-seg'); ?>
          <input class="upcoming" id="<?php echo esc_attr($this->get_field_id('title')); ?>" size='40' name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
          <i>Title is not displayed: only for admin UI</i>
        </label>
      </p>
      <div style="display:table; width:100%; height:100%; position:relative;">
        <hr style="display: table-cell; text-align: center; vertical-align: middle; width:auto; height:auto;">
      </div><br/>
      <!-- Number of Posts -->
      <div class="seg-admin-fields post-num">
        <label for="<?php echo esc_attr($this->get_field_id('postnum')); ?>">Number of Posts to Display<br/>
          <input type="number" id="<?php echo esc_attr($this->get_field_id('postnum')); ?>" style="width:50px;" name="<?php echo esc_attr($this->get_field_name('postnum')); ?>" value="<?php echo esc_attr( $postnum ); ?>" />
        </label>
      </div><br/>

      <?php
    }

    public function update($new_instance, $old_instance){
      $instance = $old_instance;
      $instance[ 'title' ] = $new_instance[ 'title' ];
      $instance[ 'postnum' ] = $new_instance[ 'postnum' ];

      return $instance;
    }

    public function widget($args, $instance){
      extract($args, EXTR_SKIP);
      $tl = 6;
      $title = empty( $instance[ 'title' ] ) ? '' : apply_filters( 'widget_title', $instance[ 'title' ] );
      $title = htmlspecialchars_decode( stripslashes( $title ) );
      $postnum = ( ( array_key_exists( 'postnum', $instance ) )? $instance[ 'postnum' ] : NULL );

      $arguments = array(
        'post_type' => 'post',
    		'posts_per_page' => $postnum,
        'orderby' => array(
          'date' => 'DESC',
        )
    	);
      $post_query = new WP_Query();
    	$all_wp_posts = $post_query->query( $arguments );
      // Render
      ob_start();
      echo $args['before_widget'];

  		if ( !empty( $instance[ 'title' ] ) ) {
  			echo $args['before_title'] . $instance[ 'title' ] . $args['after_title'];
  		}
      if( $postnum ){
        foreach( $all_wp_posts as $post ){
          $title = $post->post_title;
          $link = get_permalink( $post->ID );
          $thumb = get_the_post_thumbnail_url( $post->ID, array( 150,150 ) );
          $thumb = ( ( $thumb == false )? 'https://via.placeholder.com/150?text=Swetnam+Entertainment+Group' : $thumb );
        ?>
        <div class="seg-blog-articles latest-articles">
          <div class="blog-article">
            <div class="blog-thumb">
              <a href="<?=$link?>" target="self"><img src="<?=$thumb?>" border="0"></a>
            </div>
            <div class="blog-title">
              <a href="<?=$link?>" target="self"><?=$title?></a>
            </div>
          </div>
        </div>
        <?php
        }
      }
      echo $args['after_widget'];
      $buffer =  ob_get_clean();
      echo $buffer;
    }
  }
}
$la_callback = function(){ return register_widget("SEG_Latest_Articles"); };
add_action('widgets_init', $la_callback );
?>
