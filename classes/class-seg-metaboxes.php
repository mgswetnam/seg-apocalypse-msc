<?php

/**
 * Metabox factory for SEG Theme
 * @class SEG_Theme_Metaboxes
 */

final class SEG_Theme_Metaboxes {

  private $version;
  public static $_instance;

  static function init(){
    if ( !self::$_instance ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public function __construct() {
    // AJAX
		add_action( 'wp_ajax_seg_pagelist_create', array( $this, 'seg_pagelist_create' ) );
		add_action( 'wp_ajax_seg_pagelist_delete', array( $this, 'seg_pagelist_delete' ) );
  }

	static public function seg_add_meta_boxes(){
		global $post;
    include SEG_THEME_DIR ."/defs/metaboxes.php";
		foreach( $metaboxes as $value ){
			add_meta_box( $value[ "mbid" ], $value[ "mbtitle" ], array( __CLASS__, 'seg_add_custom_fields' ), $value[ "mbscreen" ], $value[ "mbcontext" ], $value[ "mbpriority" ], $value[ "fields" ] );
		}
	}

  public static function seg_add_custom_fields( $post, $args ){
		global $post;
		$custom = get_post_custom( $post->ID );
    $mbjeeves = new SEG_Theme_Metaboxes();

		ob_start();
    ?>
    <div class="seg-admin">
    <?php
		foreach( $args[ "args" ] as $field ){
			$fieldval = ( ( array_key_exists( "fid", $field ) )? $field[ "fid" ] : NULL );
      $fielddef = ( ( array_key_exists( "fdefault", $field ) )? $field[ "fdefault" ] : "" );
			$value = ( ( $fieldval && array_key_exists( $fieldval, $custom ) )? ( ( array_key_exists( 0, $custom[ $fieldval ] ) )? $custom[ $fieldval ][ 0 ] : "" ) : "" );
			$value = ( ( $value == "" && $fielddef != "" )? $fielddef  : $value );
      $wrapclass = ( ( array_key_exists( "fwrapclass", $field ) )? $field[ 'fwrapclass' ] : "" );
      $dataatt = ( ( array_key_exists( 'fdata', $field ) )? " data-field-data=\"".$field[ 'fdata' ]."\"" : "" );
      $atts = "";
      $options = array();
      $settings = array();
			foreach( $field[ "attributes" ] as $k=>$v ){
        if( $k == "options" ){
          $options = $v;
        } elseif( $k == "settings" ){
          $settings = $v;
        } else {
          $atts .= $k . "=\"" . $v . "\" ";
        }
			}
      ?>
      <div class="seg-admin-field <?=$wrapclass?>">
        <label for="<?=$field[ 'fid' ]?>"><?=$field[ 'flabel' ]?>  </label></br>
        <?php
  			echo $mbjeeves->seg_fetch_field( $field, $custom, $atts, $options, $settings, $value, $dataatt );
        ?>
      </div>
      <?php
		}
    ?>
    </div>
    <?php
    $buffer = ob_get_clean();

    //$buffer = preg_replace( $search, $replace, $buffer );
    echo $buffer;
    unset( $mbjeeves );
	}

  public function seg_fetch_field( $field, $custom, $atts, $options, $settings, $value, $dataatt ){
    $mbfjeeves = new SEG_Theme_Metaboxes();
    $function = NULL;
    switch( $field[ "ftype" ] ){
      case "input":{
        switch( $field[ "fsubtype" ] ){
          case "text":
          case "password":
          case "email":
          case "color":
          case "date":
          case "file":
          case "number":
          case "time":
          case "url":{ $function = "seg_add_custom_field_input_general"; break; }
          case "radio":{ $function = "seg_add_custom_field_input_radio"; break; }
          case "checkbox":{ $function = "seg_add_custom_field_input_checkbox"; break; }
        }
        break;
      }
      case "select":{ $function = "seg_add_custom_field_select"; break; }
      case "textarea":{ $function = "seg_add_custom_field_textarea"; break; }
      case "editor":{ $function = "seg_add_custom_field_editor"; break; }
      case "pagelist":{ $function = "seg_add_custom_field_pagelist"; break; }
      case "keyval":{ $function = "seg_add_custom_field_keyval"; break; }
      case "hours":{ $function = "seg_add_custom_field_hours"; break; }
      case "shortcodes":{ $function = "seg_add_custom_field_shortcodes"; break; }
    }
    if( $function ){
      return $mbfjeeves->$function( $field, $custom, $atts, $options, $settings, $value, $dataatt );
    } else {
      return false;
    }
    unset( $mbfjeeves );
  }

  public function seg_add_custom_field_input_general( $field, $custom, $atts, $options, $settings, $value, $dataatt ){
    ob_start();
    ?>
    <input type="<?=$field[ 'fsubtype' ]?>" name="<?=$field[ 'fid' ]?>" <?=$atts?><?=$dataatt?> data-field-value="<?=$value?>" value="<?=$value?>" />
    <?php
    $buffer = ob_get_clean();
    return $buffer;
  }

  public function seg_add_custom_field_input_radio( $field, $custom, $atts, $options, $settings, $value, $dataatt ){
    ob_start();
    $i=0;
    foreach( $options as $kitem => $vitem ){
      $checked = ( ( $value == $kitem )? 'checked' : '' );
      ?>
      <label for="<?=$field[ 'fid' ]?>-<?=$i?>"><input type="<?=$field[ 'fsubtype' ]?>" id="<?=$field[ 'fid' ]?>-<?=$i?>" name="<?=$field[ 'fid' ]?>" <?=$atts?> <?=$checked?> value="<?php echo strtolower( $kitem ); ?>" /><?=$vitem?></label><br/>
      <?php
      $i++;
    }
    $buffer = ob_get_clean();
    return $buffer;
  }

  public function seg_add_custom_field_input_checkbox( $field, $custom, $atts, $options, $settings, $value, $dataatt ){
    ob_start();
    $i=0;
    $value = unserialize( $value );
    foreach( $options as $kitem => $vitem ){ ?>
      <label for="<?=$field[ 'fid' ]?>-<?=$i?>"><input type="<?=$field[ 'fsubtype' ]?>" id="<?=$field[ 'fid' ]?>-<?=$i?>" name="<?=$field[ 'fid' ]?>[]" <?=$atts?> <?php echo ( ( is_array( $value ) )? ( ( in_array( strtolower( $kitem ), $value ) )? 'checked' : '' ) : '' ); ?> value="<?php echo strtolower( $kitem ); ?>" /><?=ucfirst( $vitem )?></label><br/>
      <?php
      $i++;
    }
    $buffer = ob_get_clean();
    return $buffer;
  }

  public function seg_add_custom_field_select( $field, $custom, $atts, $options, $settings, $value, $dataatt ){
    $selected = ( ( $value != "" )? $value : ( ( $field[ 'fdefault' ] != "" )? $field[ 'fdefault' ] : "" ) );
    ob_start();
    ?>
    <select name="<?=$field[ 'fid' ]?>" <?=$atts?><?=$dataatt?> data-field-value="<?=$value?>" />
      <option value="">Select</option>
      <?php foreach( $options as $kitem => $vitem ){
        $picked = ( ( $selected == strtolower( $kitem ) )? 'selected' : '' );
        ?>
      <option value="<?php echo strtolower( $kitem ); ?>" <?=$picked?>><?=$vitem?></option>
      <?php } ?>
    </select>
    <?php
    $buffer = ob_get_clean();
    return $buffer;
  }

  public function seg_add_custom_field_textarea( $field, $custom, $atts, $options, $settings, $value, $dataatt ){
    ob_start();
    ?>
    <textarea name="<?=$field[ 'fid' ]?>"<?=$dataatt?> <?=$atts?> data-field-value="<?=$value?>" ><?=$value?></textarea>
    <?php
    $buffer = ob_get_clean();
    return $buffer;
  }

  public function seg_add_custom_field_editor( $field, $custom, $atts, $options, $settings, $value, $dataatt ){
    ob_start();
    $field[ "attributes" ][ "textarea_name" ] = $field[ 'fid' ];
    wp_editor( $value, $field[ 'fid' ], $field[ "attributes" ] );
    $buffer = ob_get_clean();
    return $buffer;
  }

  public function seg_add_custom_field_pagelist( $field, $custom, $atts, $options, $settings, $value, $dataatt ){
    global $post;
    $value = ( ( $value )? unserialize( $value ) : array() );
    //$title = ( ( array_key_exists( "title", $settings ) )? sprintf( $settings[ "title" ], $post->post_title ) : "Page from ".$post->post_title );
    //$name = ( ( array_key_exists( "slug", $settings ) )? sprintf( $settings[ "slug" ], $post->post_name ) : $post->post_name."_copy" );
    $linktext = ( ( array_key_exists( "add_link", $settings ) )? $settings[ "add_link" ] : "Add New" );
    $limit = ( ( array_key_exists( "limit", $settings ) )? $settings[ "limit" ] : "" );
    $args = ( ( array_key_exists( "args", $settings ) )? $settings[ "args" ] : NULL );
    $parent = $post->ID;;
    if( $args ){
      $args[ "post_content" ] = ( ( array_key_exists( "post_content", $args ) )? $args[ "post_content" ] : "" );
      $args[ "post_title" ] = ( ( array_key_exists( "post_title", $args ) )? sprintf( $args[ "post_title" ], $post->post_title ) : "Page from ".$post->post_title );
      $args[ "post_excerpt" ] = ( ( array_key_exists( "post_excerpt", $args ) )? $args[ "post_excerpt" ] : "" );
      $args[ "post_status" ] = ( ( array_key_exists( "post_status", $args ) )? $args[ "post_status" ] : "draft" );
      $args[ "post_type" ] = ( ( array_key_exists( "post_type", $args ) )? $args[ "post_type" ] : "page" );
      $args[ "post_name" ] = ( ( array_key_exists( "post_name", $args ) )? sprintf( $args[ "post_name" ], $post->post_name ) : $post->post_name."-copy" );
      $args[ "menu_order" ] = ( ( array_key_exists( "menu_order", $args ) )? $args[ "menu_order" ] : "" );
      $args[ "meta_input" ] = ( ( array_key_exists( "meta_input", $args ) )? $args[ "meta_input" ] : "" );
      $args[ "post_parent" ] = $post->ID;
    }
    ob_start();
    ?>
    <fieldset class="seg-pagelink-panel">
      <legend>Pages</legend>
      <ul class="seg-pagelink-list">
        <?php
        if( $value && $value != "" && is_array( $value ) && !empty( $value ) ){
          $i=1;
          foreach( $value as $lid ){
            ?>
            <li id="seg-pagelink-li-<?=$lid?>">
              <a href="<?=get_edit_post_link( $lid )?>" id="<?=str_replace( "_", "-", $field[ 'fid' ] )?>-item-<?=$i?>"><?=get_the_title( $lid )?></a>
              <ul class="seg-pagelink-li-tools">
                <li><a href="<?=get_edit_post_link( $lid )?>" id="<?=str_replace( "_", "-", $field[ 'fid' ] )?>-edit-<?=$i?>" class="seg-pltool edit">Edit</a></li>
                <li><a href="#" id="<?=str_replace( "_", "-", $field[ 'fid' ] )?>-delete-<?=$i?>" class="seg-pltool delete" data-pl-did="<?=$lid?>" data-pl-pid="<?=$parent?>" data-pl-dname="<?=$field[ 'fid' ]?>">Delete</a></li>
              </ul>
            </li>
            <?php
            $i++;
          }
        } else { ?>
          <li id="seg-pagelink-li-empty">No pages currently exist.</li>
        <?php } ?>
      </ul>
    </fieldset>
    <div>
      <a href="#" id="seg-pl-addbtn-<?=str_replace( "_", "-", $field[ 'fid' ] )?>" class="seg-pagelist-add-btn <?=str_replace( "_", "-", $field[ 'fid' ] )?>-add" data-pl-name="<?=$field[ 'fid' ]?>" data-pl-limit="<?=$limit?>" data-pl-args="<?=htmlspecialchars( json_encode( $args ) )?>"><?=$linktext?></a>
    </div>
    <div class="seg-pl-fields">
      <?php
      if( $value && $value != "" && is_array( $value ) && !empty( $value ) ){
        $i=1;
        foreach( $value as $lid ){
          ?>
          <input type="hidden"  id="seg-pagelink-hidden-<?=$lid?>" name="<?=$field[ 'fid' ]?>[]" data-field-value="<?=$lid?>" value="<?=$lid?>" />
          <?php
        }
      } ?>
    </div>
    <div class="seg-pl-manage-all"><a href="/wp-admin/edit.php?post_type=offer_landing">Manage All Pages</a></div>
    <?php
    $buffer = ob_get_clean();
    return $buffer;
  }

  public function seg_add_custom_field_keyval( $field, $custom, $atts, $options, $settings, $value, $dataatt ){
    $value = ( ( $value )? unserialize( $value ) : array() );
    $i = 0;
    $pairs = array();
    foreach( $value as $key => $val ){
      if( $key%2 == 0 ){
        $i++;
        $pairs[ $i-1 ][ "key" ] = $val;
      } else {
        $pairs[ $i-1 ][ "val" ] = $val;
      }
    }
    //$value = ( ( $value )? array_combine( range( 1, count( $value ) ), array_values( $value ) ) : array() );
    $kvfield = ( ( array_key_exists( 'fsubtype', $field ) )? $field[ 'fsubtype' ] : NULL );
    ob_start();
    ?>
    <div id="keyval-outter-wrapper" class="seg-keyval keyval-outter-wrapper">
      <div class="keyval-label-wrapper">
        <div class="keyval-field">Key</div>
        <div class="keyval-field">Value</div>
      </div>
      <?php
      $i=1;
      if( !empty( $pairs ) ){
        foreach( $pairs as $theval ){
          ?>
          <div id="keyval-container-<?=$i?>" class="keyval-container">
            <?php
            foreach( $kvfield as $thefield ){
              foreach( $thefield as $k => $v ){ ?>
                <div class="keyval-field"><?php
                $side = ( ( $i % 2 == 0)? 'val' : 'key' );
                switch( $k ){
                  case "input":{ echo "<input type=\"".$v."\" id=\"".$field[ 'fid' ]."-".$side."-".$i."\" class=\"seg-keyval ".$side."\" name=\"".$field[ 'fid' ]."[]\" value=\"".$theval[ $side ]."\" >"; break; }
                  case "select":{
                    echo "<select id=\"".$field[ 'fid' ]."-".$side."-".$i."\" class=\"seg-keyval ".$side."\" name=\"".$field[ 'fid' ]."[]\" >";
                    echo "<option value=\"\">Select</option>";
                    foreach( $v as $kitem => $vitem ){
                      echo "<option value=\"".strtolower( $kitem )."\" ".( ( strtolower( $kitem ) == $theval[ $side ] )? 'selected' : '' )." >".$vitem."</option>";
                    }
                    echo "</select>";
                    break;
                  }
                  case "textarea":{ echo "<textarea name=\"".$field[ 'fid' ]."-".$side."-".$i."\" />".$theval[ $side ]."</textarea>"; break; }
                }
              } ?>
              </div>
              <?php
              $i++;
            } ?>
            <div class="keyval-revoker"><div class="seg-offer-team-revoker-icon dashicons dashicons-dismiss" data-revoker-field="keyval-container" data-revoker-id="1"></div></div>
            <div class="keyval-repeater"><div class="seg-offer-team-repeater-icon dashicons dashicons-plus-alt" data-repeater-field="keyval-container" data-repeater-id="1"></div></div>
          </div>
          <?php
        }
      } else {
      ?>
      <div id="keyval-container-1" class="keyval-container">
        <?php
        $i=1;
        foreach( $kvfield as $thefield ){
          foreach( $thefield as $k => $v ){ ?>
            <div class="keyval-field"><?php
            $side = ( ( $i % 2 == 0)? 'val' : 'key' );
            switch( $k ){
              case "input":{ echo "<input type=\"".$v."\" id=\"".$field[ 'fid' ]."-".$side."-1\" class=\"seg-keyval ".$side."\" name=\"".$field[ 'fid' ]."[]\" value=\"\" >"; break; }
              case "select":{
                echo "<select id=\"".$field[ 'fid' ]."-".$side."-1\" class=\"seg-keyval ".$side."\" name=\"".$field[ 'fid' ]."[]\" >";
                echo "<option value=\"\">Select</option>";
                foreach( $v as $kitem => $vitem ){
                  echo "<option value=\"".strtolower( $kitem )."\">".$vitem."</option>";
                }
                echo "</select>";
                break;
              }
              case "textarea":{ echo "<textarea name=\"".$field[ 'fid' ]."-".$side."-1\" /></textarea>"; break; }
            }
          } ?>
          </div><?php
          $i++;
        } ?>
        <div class="keyval-revoker"><div class="seg-offer-team-revoker-icon dashicons dashicons-dismiss" data-revoker-field="keyval-container" data-revoker-id="1"></div></div>
        <div class="keyval-repeater"><div class="seg-offer-team-repeater-icon dashicons dashicons-plus-alt" data-repeater-field="keyval-container" data-repeater-id="1"></div></div>
      </div>
      <?php
    }
    $buffer = ob_get_clean();
    return $buffer;
  }

  public function seg_add_custom_field_hours( $field, $custom, $atts, $options, $settings, $value, $dataatt ){
    ob_start();
    $value = ( ( $value )? unserialize( $value ) : array() );
    // We have to increment all keys by 1, otherwise sunday is 0, which evaluates to false
    $value = ( ( $value )? array_combine( range( 1, count( $value ) ), array_values( $value ) ) : array() );
    $dotw = array( "sunday","monday","tuesday","wednesday","thursday","friday","saturday" );
    ?>
    <div class="seg-hours days-wrapper"<?=$dataatt?>>
      <div class="day-wrapper">
        <div class="day-field"></div>
        <div class="day-field">From</div>
        <div class="day-field"></div>
        <div class="day-field">To</div>
      </div>
      <?php foreach( $dotw as $day ){ ?>
      <?php if( $valkey = array_search( $day, $value ) ){ ?>
      <div class="day-wrapper">
        <div class="day-field"><input type="checkbox" id="<?=$field[ 'fid' ]?>-<?=$day?>" class="seg-checkbox" name="<?=$field[ 'fid' ]?>[]" checked value="<?=$day?>" /> <?=ucfirst( $day )?> </div>
        <div class="day-field"><input type="number" id="<?=$field[ 'fid' ]?>-<?=$day?>-from-hr" class="seg-numrange time" name="<?=$field[ 'fid' ]?>[]" min="0" max="23" placeholder="00" value="<?=$value[ intval( $valkey )+1 ]?>" ><span class="hr-min-colon">:</span></div>
        <div class="day-field"><input type="number" id="<?=$field[ 'fid' ]?>-<?=$day?>-from-min" class="seg-numrange time" name="<?=$field[ 'fid' ]?>[]" min="0" max="59" placeholder="00" value="<?=$value[ intval( $valkey )+2 ]?>" ><span class="from-to-dash">-</span></div>
        <div class="day-field"><input type="number" id="<?=$field[ 'fid' ]?>-<?=$day?>-to-hr" class="seg-numrange time" name="<?=$field[ 'fid' ]?>[]" min="0" max="23" placeholder="00" value="<?=$value[ intval( $valkey )+3 ]?>" ><span class="hr-min-colon">:</span></div>
        <div class="day-field"><input type="number" id="<?=$field[ 'fid' ]?>-<?=$day?>-to-min" class="seg-numrange time" name="<?=$field[ 'fid' ]?>[]" min="0" max="59" placeholder="00" value="<?=$value[ intval( $valkey )+4 ]?>" ></div>
      </div>
      <?php } else { ?>
      <div class="day-wrapper">
        <div class="day-field"><input type="checkbox" id="<?=$field[ 'fid' ]?>-<?=$day?>" class="seg-checkbox" name="<?=$field[ 'fid' ]?>[]" value="<?=$day?>" /> <?=ucfirst( $day )?> </div>
        <div class="day-field"><input type="number" id="<?=$field[ 'fid' ]?>-<?=$day?>-from-hr" class="seg-numrange time" name="<?=$field[ 'fid' ]?>[]" min="0" max="23" placeholder="00" value="" ><span class="hr-min-colon">:</span></div>
        <div class="day-field"><input type="number" id="<?=$field[ 'fid' ]?>-<?=$day?>-from-min" class="seg-numrange time" name="<?=$field[ 'fid' ]?>[]" min="0" max="59" placeholder="00" value="" ><span class="from-to-dash">-</span></div>
        <div class="day-field"><input type="number" id="<?=$field[ 'fid' ]?>-<?=$day?>-to-hr" class="seg-numrange time" name="<?=$field[ 'fid' ]?>[]" min="0" max="23" placeholder="00" value="" ><span class="hr-min-colon">:</span></div>
        <div class="day-field"><input type="number" id="<?=$field[ 'fid' ]?>-<?=$day?>-to-min" class="seg-numrange time" name="<?=$field[ 'fid' ]?>[]" min="0" max="59" placeholder="00" value="" ></div>
      </div>
      <?php } ?>
      <?php } ?>
    </div>
    <?php
    $buffer = ob_get_clean();
    return $buffer;
  }

  public function seg_add_custom_field_shortcodes( $field, $custom, $atts, $options, $settings, $value, $dataatt ){
    global $post;
    $parent = wp_get_post_parent_id( $post->ID );
    ob_start();
    ?>
    <div <?=$atts?>>
      <ul class="seg-sc-list">
        <?php
        foreach( $settings as $code => $deets ){
          $title = ( ( array_key_exists( "title", $deets ) )? $deets[ "title" ] : "" );
          $args = ( ( array_key_exists( "args", $deets ) )? $deets[ "args" ] : "" );
          $argdisplay = "";
          foreach( $args as $arg ){
            $argdisplay .= $arg.",";
          }
          $argdisplay = substr( $argdisplay, 0, -1);
          ?>
          <li class="seg-sc-item-wrapper">
            <span class="seg-sc-item" data-sc-parent="<?=$parent?>" data-sc-code="<?=$code?>"><strong><?=$title?></strong> (<a href="#" class="seg-sc-copy no-atts" >Copy</a>) (<a href="#" class="seg-sc-copy with-atts" data-sc-args="<?=htmlspecialchars( $argdisplay)?>">w/ attributes</a>)</span>
          </li>
          <?php
        }
        ?>
      </ul>
    </div>
    <?php
    $buffer = ob_get_clean();
    return $buffer;
  }

	public function seg_save_custom_fields(){
		global $post;
    include SEG_THEME_DIR ."/defs/metaboxes.php";
    // Set variables
		$pid = ( ( $post )? $post->ID : NULL );
    $custom_fields = array();
    $args = NULL;
    // Get field ID from metabox array
    foreach( $metaboxes as $value ){
      $args = ( ( array_key_exists( "fields", $value ) )? $value[ "fields" ] : NULL );
      foreach( $args as $field ){
        $fid = ( ( array_key_exists( "fid", $field ) )? $field[ "fid" ] : NULL );
        array_push( $custom_fields, $fid );
      }
    }
    // Process all fields in array
    if( $args ){
      foreach( $custom_fields as $field ){
        if( $content = ( ( array_key_exists( $field, $_POST ) )? $_POST[ $field ] : "" ) ){
          update_post_meta( $pid, $field, $content );
        }
      }
    }
	}

  public function seg_copy_meta( $id ){
    $meta = array();
    $meta_raw = get_post_meta( $id );
    foreach( $meta_raw as $key => $value ){
      if( substr( $key, 0, 16 ) === "seg_field_offer_" ){
        $meta[ $key ] = ( ( array_key_exists( 0, $value ) )? $value[ 0 ] : "" );
      }
    }
    return $meta;
  }

  /** START AJAX **/
  public function seg_pagelist_create(){
    $coreob = new SEG_Theme_Core();
		$response = $coreob->seg_theme_response_container();
    // Set variables
    $parent = ( ( array_key_exists( "parent", $_POST ) )? $_POST[ "parent" ] : NULL );
    $ppost = get_post( $parent );
    $content = ( ( array_key_exists( "content", $_POST ) )? ( ( $_POST[ "content" ] == "!duplicate" )? $ppost->post_content : ( ( $_POST[ "content" ] != "" )? $_POST[ "content" ] : NULL ) ) : NULL );
    $title = ( ( array_key_exists( "title", $_POST ) )? $_POST[ "title" ] : NULL );
    $status = ( ( array_key_exists( "status", $_POST ) )? $_POST[ "status" ] : NULL );
    $type = ( ( array_key_exists( "type", $_POST ) )? $_POST[ "type" ] : NULL );
    $name = ( ( array_key_exists( "name", $_POST ) )? $_POST[ "name" ] : NULL );
    $order = ( ( array_key_exists( "order", $_POST ) )? ( ( $_POST[ "order" ] == "!duplicate" )? $ppost->menu_order : ( ( $_POST[ "order" ] != "" )? $_POST[ "order" ] : NULL ) ) : NULL );
    $meta = ( ( array_key_exists( "meta", $_POST ) )? ( ( $_POST[ "meta" ] == "!duplicate" )? $this->seg_copy_meta( $parent ) : $_POST[ "content" ] ) : NULL );
    $meta_name = ( ( array_key_exists( "mname", $_POST ) )? $_POST[ "mname" ] : NULL );
    // Create argument array
    $postarr = array();
    ( ( $content )? $postarr[ "post_content" ] = $content : NULL );
    ( ( $title )? $postarr[ "post_title" ] = $title : NULL );
    ( ( $status )? $postarr[ "post_status" ] = $status : NULL );
    ( ( $type )? $postarr[ "post_type" ] = $type : NULL );
    ( ( $name )? $postarr[ "post_name" ] = $name : NULL );
    ( ( $parent )? $postarr[ "post_parent" ] = $parent : NULL );
    ( ( $order )? $postarr[ "menu_order" ] = $order : NULL );
    ( ( $meta )? $postarr[ "meta_input" ] = $meta : NULL );
    // Create
    $result = "";
    $pages = NULL;
    $returndata = array();
    if( $postarr && !empty( $postarr ) ){
      $result = wp_insert_post( $postarr );
      $pages = get_post_meta( $parent, $meta_name, true );
      $returndata = array(
        "id" => $result,
        "parent" => $parent,
        "postname" => get_the_title( $result ),
        "editlink" => get_edit_post_link( $result ),
        "metaname" => $meta_name
      );
      if( $pages ){
        array_push( $pages, $result );
      } else {
        $pages = array( $result );
      }
      update_post_meta( $parent, $meta_name, $pages );
    }
    array_push( $response[ "content" ], $returndata );
    // Send response
    wp_send_json( $response );
    unset( $coreob );
  }

  public function seg_pagelist_delete(){
    $coreob = new SEG_Theme_Core();
		$response = $coreob->seg_theme_response_container();
    // Set variables
    $id = ( ( array_key_exists( "id", $_POST ) )? $_POST[ "id" ] : NULL );
    $pid = ( ( array_key_exists( "pid", $_POST ) )? $_POST[ "pid" ] : NULL );
    $dname = ( ( array_key_exists( "dname", $_POST ) )? $_POST[ "dname" ] : NULL );
    $result = "";
    if( $id ){
      $result = wp_delete_post( $id, true );
      $pages = get_post_meta( $pid, $dname, true );
      if( ( $key = array_search( $id, $pages ) ) !== false ){
        unset( $pages[ $key ] );
        update_post_meta( $pid, $dname, $pages );
      }
      array_push( $response[ "content" ], $result );
    }
    // Send response
    wp_send_json( $response );
    unset( $coreob );
  }
  /** END AJAX **/

}
