<?php
$metaboxes = array( //Metaboxes
  "hideheader" => array(
    "mbid" => "seg_page_hideheader",
    "mbtitle" => "Hide Header",
    "mbscreen" => "page", //string or array of post types
    "mbcontext" => "side", //normal, side, advanced
    "mbpriority" => "default", //high, low, default
    "fields" => array( //Fields
      "fhideheader" => array(
        "fid" => "seg_field_custom_hideheader",
        "ftype" => "select",
        "fsubtype" => "text",
        "flabel" => "Do you want to hide the header?",
        "fdefault" => "no",
        "attributes" => array(
          "class" => "seg_custom_field hideheader",
          "options" => array(
            "no"=>"No",
            "yes"=>"Yes"
          )
        )
      )
    )
  )
);
// Tracking
$tracking = true;
$tracking_array = array(
  'fbpixel' => get_option( 'seg_radio_facebook' )
);
$tracking = ( ( in_array( 'yes', $tracking_array ) )? true : false );
if( $tracking ){
  $metaboxes[ 'addtracking' ] = array(
    "mbid" => "seg_page_addtracking",
    "mbtitle" => "Tracking Codes",
    "mbscreen" => array( 'page', 'post', 'offer', 'offer_landing' ), //string or array of post types
    "mbcontext" => "side", //normal, side, advanced
    "mbpriority" => "default", //high, low, default
    "fields" => array() //Fields
  );
  // FB Pixel
  foreach( $tracking_array as $key => $value ){
    if( $value == 'yes' ){
      switch( $key ){
        case 'fbpixel':{
          $metaboxes[ 'addtracking' ][ 'fields' ][ 'fbpixel' ] = array(
            "fid" => "seg_field_custom_fbpixel",
            "ftype" => "input",
            "fsubtype" => "checkbox",
            "flabel" => "Facebook Pixel Tags",
            "fdefault" => "",
            "attributes" => array(
              "class" => "seg_custom_field fbpixel",
              "options" => array(
                "Contact"=>"Contact",
                "Lead"=>"Lead",
                "Schedule"=>"Schedule",
                "Subscribe"=>"Subscribe",
                "ViewContent"=>"ViewContent"
              )
            )
          );
          break;
        }
      }
    }
  }
  // End FB Pixel
}
// End Tracking
?>
