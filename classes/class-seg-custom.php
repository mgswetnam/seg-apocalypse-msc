<?php

/**
 * Helper class for Beaver Builder child theme
 * Use this file for any custom coding needed
 * @class SEG_Theme_Custom
 */

final class SEG_Theme_Custom {

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
    add_action( "wp_print_scripts", array( $this, "seg_ajax_setup" ) );
    // Filters

    // Shortcodes
    add_shortcode( "cost_calculator", array( $this, "seg_add_cost_calculator" ) );
		// AJAX
		add_action( 'wp_ajax_seg_fetch_calc_settings', array( $this, 'seg_fetch_calc_settings' ) );
		add_action( 'wp_ajax_nopriv_seg_fetch_calc_settings', array( $this, 'seg_fetch_calc_settings' ) );
  }

  public static function seg_ajax_setup(){
		wp_localize_script( 'seg-ca-main', 'seg_ajaxobject', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
  }

  public function theme_upgrade(){
    // Update version option
		update_option( 'seg_theme_version', SEG_VERSION, true );
		// Since v1.0.0
  }

  public static function add_common_patterns(){
    register_block_pattern_category(
      'seg',
      array( 'label' => __( 'SEG Modules', 'seg-apocalypse' ) )
    );
    /* Standard */
    register_block_pattern(
      'seg-apocalypse-hs/seg-standard',
      array(
        'title' => __( 'SEG Standard', 'seg-apocalypse' ),
        'categories' => array( 'seg' ),
        'description' => _x( 'Standard module for generic use.', 'Block pattern description', 'seg-apocalypse' ),
        'content' => '<!-- wp:group {"className":"seg-module seg-standard"} --> <div class="wp-block-group seg-module seg-standard"><div class="wp-block-group__inner-container"><!-- wp:paragraph --> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eu fermentum metus, et aliquet velit. Quisque laoreet lacinia lorem non porta. Proin eu enim in dolor porttitor semper et eu augue. Sed blandit est leo, vitae euismod augue tincidunt id. Duis vestibulum iaculis tortor ut porta. Cras auctor hendrerit metus sed semper. Fusce sit amet nulla nec tellus sodales pulvinar. Phasellus id tortor est. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Pellentesque sed sem interdum, gravida mi non, ultrices mauris. Pellentesque sit amet condimentum lorem, sed posuere nibh. Integer molestie felis elit, vel vulputate magna finibus ac. Cras lacus orci, pharetra ac nisi sit amet, sodales tempus elit. Vestibulum pulvinar a magna vitae rhoncus. Ut lacinia venenatis nisi sit amet maximus. Integer dignissim justo sodales nulla suscipit volutpat.</p> <!-- /wp:paragraph --></div></div> <!-- /wp:group -->',
      )
    );
    /* News Flow */
    register_block_pattern(
      'seg-apocalypse-hs/seg-newsflow',
      array(
        'title' => __( 'SEG Newsflow', 'seg-apocalypse' ),
        'categories' => array( 'seg' ),
        'description' => _x( 'Module for displaying text in balanced columns like a newspaper.', 'Block pattern description', 'seg-apocalypse' ),
        'content' => '<!-- wp:group {"className":"seg-module seg-newsflow"} --> <div class="wp-block-group seg-module seg-newsflow"><div class="wp-block-group__inner-container"><!-- wp:heading {"textAlign":"center","className":"newsflow-heading underscore"} --> <h2 class="has-text-align-center newsflow-heading underscore">Lorem ipsum dolor sit amet, consectetur adipiscing elit</h2> <!-- /wp:heading --> <!-- wp:group {"className":"newsflow-content four-col"} --> <div class="wp-block-group newsflow-content four-col"><div class="wp-block-group__inner-container"><!-- wp:paragraph --> <p>Sed diam dolor, pellentesque eu vehicula vestibulum, laoreet id dui. Donec in ullamcorper mauris. Aenean interdum nisi sapien, ut rhoncus mi sodales rutrum. Phasellus id eleifend enim, gravida finibus diam. Nulla malesuada bibendum diam, nec dapibus magna feugiat nec. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Praesent suscipit velit a congue commodo. Nam gravida turpis convallis quam tristique, ac gravida justo pulvinar. Sed id nibh nec turpis tincidunt commodo a eu ante. Cras efficitur non lectus et convallis. In posuere at libero id semper.</p> <!-- /wp:paragraph --> <!-- wp:paragraph --> <p>Nunc bibendum dapibus ex et tempor. Aenean laoreet, turpis a pulvinar bibendum, est felis blandit mauris, vehicula vehicula massa lacus at metus. In justo arcu, posuere vel mauris eget, egestas condimentum risus. Etiam et scelerisque dui. Fusce convallis enim vel lorem pellentesque, vel vestibulum turpis aliquet. Phasellus ante leo, finibus dignissim purus at, dignissim facilisis sapien. Aliquam placerat neque nulla, id lobortis nulla pulvinar at. Donec semper varius feugiat. Nulla ultricies sodales rutrum. Nam dapibus quam quam, vitae ullamcorper tellus vestibulum non. Duis euismod dolor sit amet luctus lacinia. Quisque lacus leo, tincidunt sed lacus non, congue dapibus enim. Vivamus congue sed nisi ornare venenatis. Suspendisse ut consequat turpis. Donec urna est, lobortis et sagittis tristique, fermentum vel purus.</p> <!-- /wp:paragraph --> <!-- wp:paragraph --> <p>Nulla egestas dignissim posuere. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Etiam laoreet tempus dui, vel pharetra est molestie at. Mauris gravida vitae purus in aliquam. Donec eleifend suscipit volutpat. Vestibulum tincidunt est quis augue rhoncus hendrerit. Quisque vitae laoreet mi, nec pellentesque odio. Mauris sapien ex, volutpat ut vestibulum id, tincidunt et purus. Nulla finibus ullamcorper dui at commodo.</p> <!-- /wp:paragraph --></div></div> <!-- /wp:group --></div></div> <!-- /wp:group -->',
      )
    );
    /* Lead In */
    register_block_pattern(
      'seg-apocalypse-hs/seg-lead-in',
      array(
        'title' => __( 'SEG Lead-In', 'seg-apocalypse' ),
        'categories' => array( 'seg' ),
        'description' => _x( 'Primary section page lead-in.', 'Block pattern description', 'seg-apocalypse' ),
        'content' => '<!-- wp:group {"className":"seg-module seg-image-lead-in"} --> <div class="wp-block-group seg-module seg-image-lead-in"><div class="wp-block-group__inner-container"><!-- wp:heading {"textAlign":"center","className":"image-lead-in-heading underscore"} --> <h2 class="has-text-align-center image-lead-in-heading underscore">Opening Section - Standard</h2> <!-- /wp:heading --> <!-- wp:columns {"className":"image-lead-in-cols"} --> <div class="wp-block-columns image-lead-in-cols"><!-- wp:column {"verticalAlignment":"center","className":"left-col"} --> <div class="wp-block-column is-vertically-aligned-center left-col"><!-- wp:group {"className":"ili-content"} --> <div class="wp-block-group ili-content"><div class="wp-block-group__inner-container"><!-- wp:paragraph --> <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi non eros mollis, imperdiet sapien at, aliquet lectus. Nulla id mauris in nisi faucibus sodales. </p> <!-- /wp:paragraph --> <!-- wp:list --> <ul><li>Praesent dapibus odio sit amet venenatis dignissim.</li><li>Nulla imperdiet sagittis mi, ut tempus lacus volutpat at.</li><li>Sed ex magna, blandit sed mauris vel, luctus bibendum leo.</li><li>Vivamus accumsan aliquet est ut sollicitudin.</li></ul> <!-- /wp:list --> <!-- wp:paragraph --> <p>Sed dignissim vel diam vel consequat. Nunc consectetur arcu nec lectus luctus, vel ultricies sapien rutrum. Fusce ac auctor leo. Aenean nisl metus, efficitur ut mauris ut, malesuada blandit tellus. </p> <!-- /wp:paragraph --> <!-- wp:block {"ref":1980} /--></div></div> <!-- /wp:group --></div> <!-- /wp:column --> <!-- wp:column {"verticalAlignment":"center","className":"right-col"} --> <div class="wp-block-column is-vertically-aligned-center right-col"><!-- wp:image {"sizeSlug":"large","className":"ili-image"} --> <figure class="wp-block-image size-large ili-image"><img src="https://via.placeholder.com/600x400.png?text=Homespun+Image+Template" alt=""/></figure> <!-- /wp:image --></div> <!-- /wp:column --></div> <!-- /wp:columns --></div></div> <!-- /wp:group -->',
      )
    );
    /* Expectations/Problems */
    register_block_pattern(
      'seg-apocalypse-hs/seg-expectations-problems',
      array(
        'title' => __( 'SEG Expectations/Problems', 'seg-apocalypse' ),
        'categories' => array( 'seg' ),
        'description' => _x( 'Sub-page copy block.', 'Block pattern description', 'seg-apocalypse' ),
        'content' => '<!-- wp:group {"className":"seg-module seg-expectations-problems"} --> <div class="wp-block-group seg-module seg-expectations-problems"><div class="wp-block-group__inner-container"><!-- wp:heading {"textAlign":"center","className":"expectations-problems-heading underscore"} --> <h2 class="has-text-align-center expectations-problems-heading underscore">Lorem ipsum dolor sit amet, consectetur adipiscing elit</h2> <!-- /wp:heading --> <!-- wp:columns {"className":"expectations-problems-cols"} --> <div class="wp-block-columns expectations-problems-cols"><!-- wp:column {"className":"left-col"} --> <div class="wp-block-column left-col"><!-- wp:group {"className":"ep-content"} --> <div class="wp-block-group ep-content"><div class="wp-block-group__inner-container"><!-- wp:heading {"level":3,"className":"epc-heading"} --> <h3 class="epc-heading">Lorem ipsum dolor sit amet</h3> <!-- /wp:heading --> <!-- wp:paragraph {"className":"epc-text"} --> <p class="epc-text">Integer vel dolor tristique justo gravida volutpat. Maecenas ipsum est, porttitor quis varius in, porttitor rutrum neque. Sed imperdiet consequat nunc, sed bibendum mauris laoreet et.</p> <!-- /wp:paragraph --> <!-- wp:list {"className":"epc-list"} --> <ul class="epc-list"><li><strong>Maecenas vel</strong></li><li><strong>Lacus lectus</strong></li><li><strong>Orci varius</strong></li><li><strong>Natoque penatibus</strong></li><li><strong>Et magnis dis parturient</strong></li></ul> <!-- /wp:list --> <!-- wp:paragraph {"className":"epc-callout"} --> <p class="epc-callout">Praesent semper molestie pulvinar. Sed rhoncus pretium ligula, in egestas mi sollicitudin maximus.</p> <!-- /wp:paragraph --></div></div> <!-- /wp:group --></div> <!-- /wp:column --> <!-- wp:column {"className":"right-col"} --> <div class="wp-block-column right-col"><!-- wp:group {"className":"ep-content"} --> <div class="wp-block-group ep-content"><div class="wp-block-group__inner-container"><!-- wp:heading {"level":3,"className":"epc-heading"} --> <h3 class="epc-heading">Vivamus accumsan semper quam</h3> <!-- /wp:heading --> <!-- wp:paragraph {"className":"epc-text"} --> <p class="epc-text">Nam massa magna, imperdiet quis fermentum ut, gravida vel mi. Suspendisse urna augue, posuere sagittis consequat tempor, faucibus vitae elit.</p> <!-- /wp:paragraph --> <!-- wp:list {"className":"epc-list epcl-expanded"} --> <ul class="epc-list epcl-expanded"><li><strong>Suspendisse potenti</strong><br>Nunc tempor porta suscipit. Quisque nec velit eros. Proin eu metus vitae sem condimentum tempus a eget nulla.</li><li><strong>Curabitur sit amet</strong><br>Mauris placerat risus eu erat viverra, id facilisis tortor feugiat.</li><li><strong>Maecenas et suscipit est</strong><br>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae</li><li><strong>Quisque tincidunt ornare</strong><br>Suspendisse convallis in mauris vitae volutpat. Cras id venenatis est.</li></ul> <!-- /wp:list --> <!-- wp:paragraph {"className":"epc-text"} --> <p class="epc-text">Aliquam risus lectus, luctus eget volutpat sit amet, semper a augue. Donec consectetur mollis ex, ac dignissim leo eleifend sollicitudin. Integer nec tristique quam. In nec vulputate justo, id aliquam lorem.</p> <!-- /wp:paragraph --> <!-- wp:buttons {"className":"epc-button epcb-schedule seg-button-common"} --> <div class="wp-block-buttons epc-button epcb-schedule seg-button-common"><!-- wp:button --> <div class="wp-block-button"><a class="wp-block-button__link" href="https://devbc9usj6t.csadigital.io/schedule-service-online/">Schedule Now</a></div> <!-- /wp:button --></div> <!-- /wp:buttons --></div></div> <!-- /wp:group --></div> <!-- /wp:column --></div> <!-- /wp:columns --></div></div> <!-- /wp:group -->',
      )
    );
    /* CTA Banner */
    register_block_pattern(
      'seg-apocalypse-hs/seg-cta-banner',
      array(
        'title' => __( 'SEG CTA Banner', 'seg-apocalypse' ),
        'categories' => array( 'seg' ),
        'description' => _x( 'Call To Action banner', 'Block pattern description', 'seg-apocalypse' ),
        'content' => '<!-- wp:group {"className":"seg-module seg-cta-fullwidth seg-module-last"} --> <div class="wp-block-group seg-module seg-cta-fullwidth seg-module-last"><div class="wp-block-group__inner-container"><!-- wp:image {"sizeSlug":"large","className":"cta-image-background ctab-pos-center"} --> <figure class="wp-block-image size-large cta-image-background ctab-pos-center"><img src="https://via.placeholder.com/1500x250.jpg?text=Homespun+Banner+Template" alt=""/></figure> <!-- /wp:image --> <!-- wp:group {"className":"cta-content"} --> <div class="wp-block-group cta-content"><div class="wp-block-group__inner-container"><!-- wp:heading {"textAlign":"center","className":"ctac-heading"} --> <h2 class="has-text-align-center ctac-heading">Schedule Service</h2> <!-- /wp:heading --> <!-- wp:paragraph {"align":"center","className":"ctac-text"} --> <p class="has-text-align-center ctac-text"><strong>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi non eros mollis, imperdiet sapien at, aliquet lectus. Nulla id mauris in nisi faucibus sodales. Aliquam erat volutpat. Sed interdum, magna nec fringilla iaculis, <strong><strong>[csa_client_dni]</strong></strong></strong> <strong>orci risus hendrerit magna, eget lacinia arcu neque in magna. Sed mattis malesuada nisi, et consectetur mi pharetra a. </strong></p> <!-- /wp:paragraph --> <!-- wp:block {"ref":1982} /--></div></div> <!-- /wp:group --></div></div> <!-- /wp:group -->',
      )
    );
  }
  public function seg_add_cost_calculator( $atts ){
    $a = shortcode_atts( array(
      'id' => ''
		), $atts );

    $search = array( '/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/<!--(.|\s)*?-->/' );
    $replace = array( '>', '<', '\\1', '' );
    /*$format = ( ( array_key_exists( 'format', $a ) )? $a[ 'format' ] : 'Y' );*/
    ob_start();
    /* Begin content */
    ?>
    <div id="seg-web-cost-calculator" class="seg-calc-wrapper container">
      <!-- Refreshing -->
      <div id="seg-calc-refreshing-row" class="row">
        <div class="calc-label col-sm-12 col-md-4">
          <label for="cf-radio-option-refreshing-1">Updating Look of Site?</label>
        </div>
        <div class="calc-field col-sm-12 col-md-8">
          <div class="cf-radio-wrap container">
            <div class="row">
              <div class="cf-radio-option col-sm-12 col-md-4">
                <label for="cf-radio-option-refreshing-1"><input type="radio" id="cf-radio-option-refreshing-1" name="seg-refreshing" class="seg-calc-field cf-radio" value="N" checked>&nbsp;No</label>
              </div>
              <div class="cf-radio-option col-sm-12 col-md-8">
                <label for="cf-radio-option-refreshing-2"><input type="radio" id="cf-radio-option-refreshing-2" name="seg-refreshing" class="seg-calc-field cf-radio" value="Y">&nbsp;Yes</label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Templates -->
      <div id="seg-calc-templates-row" class="row">
        <div class="calc-label col-sm-12 col-md-4">
          <label for="seg-calc-templates">Templates</label>
        </div>
        <div class="calc-field col-sm-12 col-md-8">
          <input type="number" id="seg-calc-templates" name="seg-templates" class="seg-calc-field cf-number" value="0" placeholder="Number of differently structured pages">
        </div>
      </div>
      <!-- Pages -->
      <div id="seg-calc-pages-row" class="row">
        <div class="calc-label col-sm-12 col-md-4">
          <label for="seg-calc-pages">Pages</label>
        </div>
        <div class="calc-field col-sm-12 col-md-8">
          <input type="number" id="seg-calc-pages" name="seg-pages" class="seg-calc-field cf-number" value="0" placeholder="Total number of pages">
        </div>
      </div>
      <!-- Images -->
      <div id="seg-calc-images-row" class="row">
        <div class="calc-label col-sm-12 col-md-4">
          <label for="seg-calc-images">Images</label>
        </div>
        <div class="calc-field col-sm-12 col-md-8">
          <input type="number" id="seg-calc-images" name="seg-images" class="seg-calc-field cf-number" value="0" placeholder="Total number of images">
        </div>
      </div>
      <!-- Content -->
      <div id="seg-calc-content-row" class="row">
        <div class="calc-label col-sm-12 col-md-4">
          <label for="seg-calc-content">Page Content</label>
        </div>
        <div class="calc-field col-sm-12 col-md-8">
          <input type="number" id="seg-calc-content" name="seg-content" class="seg-calc-field cf-number" value="0" placeholder="Number of pages which require content">
        </div>
      </div>
      <!-- Social Media -->
      <div id="seg-calc-social-row" class="row">
        <div class="calc-label col-sm-12 col-md-4">
          <label for="cf-radio-option-social-1">Include Social Media Sharing</label>
        </div>
        <div class="calc-field col-sm-12 col-md-8">
          <div class="cf-radio-wrap container">
            <div class="row">
              <div class="cf-radio-option col-sm-12 col-md-4">
                <label for="cf-radio-option-social-1"><input type="radio" id="cf-radio-option-social-1" name="seg-social" class="seg-calc-field cf-radio" value="N" checked>&nbsp;No</label>
              </div>
              <div class="cf-radio-option col-sm-12 col-md-8">
                <label for="cf-radio-option-social-2"><input type="radio" id="cf-radio-option-social-2" name="seg-social" class="seg-calc-field cf-radio" value="Y">&nbsp;Yes</label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Google Analytics -->
      <div id="seg-calc-analytics-row" class="row">
        <div class="calc-label col-sm-12 col-md-4">
          <label for="cf-radio-option-analytics-1">Include Google Analytics</label>
        </div>
        <div class="calc-field col-sm-12 col-md-8">
          <div class="cf-radio-wrap container">
            <div class="row">
              <div class="cf-radio-option col-sm-12 col-md-4">
                <label for="cf-radio-option-analytics-1"><input type="radio" id="cf-radio-option-analytics-1" name="seg-analytics" class="seg-calc-field cf-radio" value="N" checked>&nbsp;No</label>
              </div>
              <div class="cf-radio-option col-sm-12 col-md-8">
                <label for="cf-radio-option-analytics-2"><input type="radio" id="cf-radio-option-analytics-2" name="seg-analytics" class="seg-calc-field cf-radio" value="Y">&nbsp;Yes</label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Newsletter Signup -->
      <div id="seg-calc-newsletter-row" class="row">
        <div class="calc-label col-sm-12 col-md-4">
          <label for="cf-radio-option-newsletter-1">Include Newsletter Signup</label>
        </div>
        <div class="calc-field col-sm-12 col-md-8">
          <div class="cf-radio-wrap container">
            <div class="row">
              <div class="cf-radio-option col-sm-12 col-md-4">
                <label for="cf-radio-option-newsletter-1"><input type="radio" id="cf-radio-option-newsletter-1" name="seg-newsletter" class="seg-calc-field cf-radio" value="N" checked>&nbsp;No</label>
              </div>
              <div class="cf-radio-option col-sm-12 col-md-8">
                <label for="cf-radio-option-newsletter-2"><input type="radio" id="cf-radio-option-newsletter-2" name="seg-newsletter" class="seg-calc-field cf-radio" value="Y">&nbsp;Yes</label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Online Store -->
      <div id="seg-calc-store-row" class="row">
        <div class="calc-label col-sm-12 col-md-4">
          <label for="cf-radio-option-store-1">Include Online Store</label>
        </div>
        <div class="calc-field col-sm-12 col-md-8">
          <div class="cf-radio-wrap container">
            <div class="row">
              <div class="cf-radio-option col-sm-12 col-md-4">
                <label for="cf-radio-option-store-1"><input type="radio" id="cf-radio-option-store-1" name="seg-store" class="seg-calc-field cf-radio" value="N" checked>&nbsp;No</label>
              </div>
              <div class="cf-radio-option col-sm-12 col-md-8">
                <label for="cf-radio-option-store-2"><input type="radio" id="cf-radio-option-store-2" name="seg-store" class="seg-calc-field cf-radio" value="Y">&nbsp;Yes</label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Store Items -->
      <div id="seg-calc-storeitems-row" class="row">
        <div class="calc-label col-sm-12 col-md-4">
          <label for="seg-calc-storeitems">Items In Store</label>
        </div>
        <div class="calc-field col-sm-12 col-md-8">
          <input type="number" id="seg-calc-storeitems" name="seg-storeitems" class="seg-calc-field cf-number" value="0" placeholder="Number of Items to set up in store">
        </div>
      </div>
      <div id="seg-calc-total-wrapper" class="seg-total-wrap row">
        <div class="calc-label col-sm-4 col-md-4">Total</div>
        <div class="calc-display col-sm-8 col-md-8"></div>
      </div>
    </div>
    <?php
    /* End content */
    $buffer =  ob_get_clean();
    $buffer = preg_replace( $search, $replace, $buffer );
    return $buffer;
  }

  public function seg_fetch_calc_settings(){
    $response = array(
      "rate" => ( ( get_option( "seg_calc_hourlyrate" ) && get_option( "seg_calc_hourlyrate" ) != "" )? get_option( "seg_calc_hourlyrate" ) : "0" ),
      "image" => 	( ( get_option( "seg_calc_imagerate" ) && get_option( "seg_calc_imagerate" ) != "" )? get_option( "seg_calc_imagerate" ) : "0" ),
      "time" => array(
        "base" => ( ( get_option( "seg_calc_basetime" ) && get_option( "seg_calc_basetime" ) != "" )? get_option( "seg_calc_basetime" ) : "0" ),
      	"temp" => ( ( get_option( "seg_calc_timepagetemp" ) && get_option( "seg_calc_timepagetemp" ) != "" )? get_option( "seg_calc_timepagetemp" ) : "0" ),
      	"page" => ( ( get_option( "seg_calc_timepagebuild" ) && get_option( "seg_calc_timepagebuild" ) != "" )? get_option( "seg_calc_timepagebuild" ) : "0" ),
      	"content" => ( ( get_option( "seg_calc_timecontent" ) && get_option( "seg_calc_timecontent" ) != "" )? get_option( "seg_calc_timecontent" ) : "0" ),
      	"social" => ( ( get_option( "seg_calc_timesocial" ) && get_option( "seg_calc_timesocial" ) != "" )? get_option( "seg_calc_timesocial" ) : "N" ),
      	"ga" => ( ( get_option( "seg_calc_timeanalytics" ) && get_option( "seg_calc_timeanalytics" ) != "" )? get_option( "seg_calc_timeanalytics" ) : "N" ),
      	"news" => ( ( get_option( "seg_calc_timenewsletter" ) && get_option( "seg_calc_timenewsletter" ) != "" )? get_option( "seg_calc_timenewsletter" ) : "N" ),
      	"store" => ( ( get_option( "seg_calc_timestoresetup" ) && get_option( "seg_calc_timestoresetup" ) != "" )? get_option( "seg_calc_timestoresetup" ) : "N" ),
      	"item" => ( ( get_option( "seg_calc_timestoreitem" ) && get_option( "seg_calc_timestoreitem" ) != "" )? get_option( "seg_calc_timestoreitem" ) : "0" ),
      )
    );
    $response = json_encode( $response );
		wp_send_json( $response );
  }
}
