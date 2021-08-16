 ( function( $ ) {
   var opt = {};
   var methods = {
    init: function( options ){
      options = $.extend( {}, $.fn.wgResources.defaults, options );
      var postdata = create_object( options.action, options.orderby, options.order, options.page, options.ppp, options.lib, options.media, options.prod, options.com, options.search );
      $.ajax({
        url: ajax_object.ajax_url,
        type:'POST',
        dataType: 'json',
        data: postdata,
        error: function(jqXHR, textStatus, errorThrown){
          console.log( JSON.stringify( errorThrown ) );
        },
        success: function(data, textStatus, jqXHR){
          console.log( JSON.stringify( data ) );
        }
      });
    },
    fetch: function( options ){
      options = $.extend( {}, $.fn.wgResources.defaults, options );
      var postdata = create_object( options.action, options.orderby, options.order, options.page, options.ppp, options.lib, options.media, options.prod, options.com, options.search );
      $.ajax({
        url: ajax_object.ajax_url,
        type:'POST',
        dataType: 'json',
        data: postdata,
        error: function( jqXHR, textStatus, errorThrown ){
          console.log( JSON.stringify( errorThrown, null, 4 ) );
        },
        success: function( data, textStatus, jqXHR ){
          render_blocks( data, options.target );
        }
      });
    }
  }
  function render_blocks( data, target ){
    var theheader = data.header;
    //console.log( JSON.stringify( theheader[ "supplied_arguements" ].lib, null, 4 ) );
    var thedata = data.content;
    $( "#" + target ).empty();
    var wrapper_row = add_element('[{"type":"div","atts":[{"id":"cswg_resources_wrapper","class":"cswg_resources_wrapper" }],"content":"" }]');
    if( thedata.length > 0 ){
      var paging_bar_top = add_element('[{"type":"div","atts":[{"id":"cswg_resources_paging_top","class":"cswg_resources_paging_top paging_bar" }],"content":"" }]');
      var paging_bar_tprev = add_element('[{"type":"div","atts":[{"id":"cswg_resources_paging_prev_top","class":"cswg_resources_paging_prev_top paging_item paging_prev" }],"content":"<< Prev" }]');
      if( parseInt( theheader[ "current_page" ] )-1 < 1 ){
        $( paging_bar_tprev ).addClass( "paging_inactive" );
      } else {
        $( paging_bar_tprev ).removeClass( "paging_inactive" );
        $( paging_bar_tprev ).on( "click", function(){
          var args = [
            {
              action: "cs_wg_fetch_tags_proxy",
              orderby: "name",
              order: "ASC",
              page: parseInt( theheader[ "current_page" ] )-1,
              ppp: theheader[ "posts_per_page" ],
              lib: theheader[ "supplied_arguements" ].lib,
              media: theheader[ "supplied_arguements" ].media,
              prod: theheader[ "supplied_arguements" ].prod,
              com: theheader[ "supplied_arguements" ].com,
              search: theheader[ "supplied_arguements" ].search,
              target: target
            }
          ];
          $( "body" ).wgResources( "fetch", args );
        });
      }
      var paging_bar_tnext = add_element('[{"type":"div","atts":[{"id":"cswg_resources_paging_next_top","class":"cswg_resources_paging_next_top paging_item paging_next" }],"content":"Next >>" }]');
      if( parseInt( theheader[ "current_page" ] )*parseInt( theheader[ "posts_per_page" ] ) >= parseInt( theheader[ "total_records" ] ) ){
        $( paging_bar_tnext ).addClass( "paging_inactive" );
      } else {
        $( paging_bar_tnext ).removeClass( "paging_inactive" );
        $( paging_bar_tnext ).on( "click", function(){
          $( paging_bar_tnext ).on( "click", function(){
            var args = [
              {
                action: "cs_wg_fetch_tags_proxy",
                orderby: "name",
                order: "ASC",
                page: parseInt( theheader[ "current_page" ] )+1,
                ppp: theheader[ "posts_per_page" ],
                lib: theheader[ "supplied_arguements" ].lib,
                media: theheader[ "supplied_arguements" ].media,
                prod: theheader[ "supplied_arguements" ].prod,
                com: theheader[ "supplied_arguements" ].com,
                search: theheader[ "supplied_arguements" ].search,
                target: target
              }
            ];
            $( "body" ).wgResources( "fetch", args );
          });
        });
      }
      $( paging_bar_top ).append( paging_bar_tprev, paging_bar_tnext );
      $.each( thedata, function( index ){
        var block_content = add_element('[{"type":"div","atts":[{"id":"cswg_resources_block_content-' + thedata[ index ].ID + '","class":"csad_srchable_content_block cswg_resources_block_content" }],"content":"" }]');
        var block_title = add_element('[{"type":"h4","atts":[{"id":"cswg_resources_block_title-' + thedata[ index ].ID + '","class":"cswg_resources_block_title" }],"content":"' + thedata[ index ].post_title + '" }]');
        var block_linkwrap = add_element('[{"type":"a","atts":[{"href":"' + thedata[ index ].package[ "download_url" ] + '","id":"cswg_resources_block_link_wrap-' + thedata[ index ].ID + '","class":"cswg_resources_block_link_wrap" }],"content":"" }]');
        var block_image = add_element('[{"type":"div","atts":[{"id":"cswg_resources_block_image-' + thedata[ index ].ID + '","class":"cswg_resources_block_image" }],"content":"" }]');
        $( block_image ).html( thedata[ index ].package[ "icon" ] );
        var block_dlicon = add_element('[{"type":"span","atts":[{"id":"cswg_resources_block_dlicon-' + thedata[ index ].ID + '","class":"eltd_icon_font_awesome fa fa-download button_icon cswg_resources_block_dlicon" }],"content":"" }]');
        var block_dltext = add_element('[{"type":"span","atts":[{"id":"cswg_resources_block_dltext-' + thedata[ index ].ID + '","class":"cswg_resources_block_dltext" }],"content":"' + thedata[ index ].package[ "link_label" ] + '" }]');
        var block_dlbutton = add_element('[{"type":"div","atts":[{"id":"cswg_resources_block_dlbutton-' + thedata[ index ].ID + '","class":"qbutton  qbutton_with_icon icon_right cswg_resources_block_dlbutton" }],"content":"" }]');
        $( block_dlbutton ).append( block_dlicon, block_dltext );
        $( block_linkwrap ).append( block_title, block_image, block_dlbutton );
        $( block_content ).append( block_linkwrap );
        $( wrapper_row ).append( block_content );
      });
      var paging_bar_bottom = add_element('[{"type":"div","atts":[{"id":"cswg_resources_paging_bottom","class":"cswg_resources_paging_bottom paging_bar" }],"content":"" }]');
      var paging_bar_bprev = add_element('[{"type":"div","atts":[{"id":"cswg_resources_paging_prev_bottom","class":"cswg_resources_paging_prev_bottom paging_item paging_prev" }],"content":"<< Prev" }]');
      if( parseInt( theheader[ "current_page" ] )-1 < 1 ){
        $( paging_bar_bprev ).addClass( "paging_inactive" );
      } else {
        $( paging_bar_bprev ).removeClass( "paging_inactive" );
        $( paging_bar_bprev ).on( "click", function(){
          var args = [
            {
              action: "cs_wg_fetch_tags_proxy",
              orderby: "name",
              order: "ASC",
              page: parseInt( theheader[ "current_page" ] )-1,
              ppp: theheader[ "posts_per_page" ],
              lib: theheader[ "supplied_arguements" ].lib,
              media: theheader[ "supplied_arguements" ].media,
              prod: theheader[ "supplied_arguements" ].prod,
              com: theheader[ "supplied_arguements" ].com,
              search: theheader[ "supplied_arguements" ].search,
              target: target
            }
          ];
          $( "body" ).wgResources( "fetch", args );
        });
      }
      var paging_bar_bnext = add_element('[{"type":"div","atts":[{"id":"cswg_resources_paging_next_bottom","class":"cswg_resources_paging_next_bottom paging_item paging_next" }],"content":"Next >>" }]');
      if( parseInt( theheader[ "current_page" ] )*parseInt( theheader[ "posts_per_page" ] ) >= parseInt( theheader[ "total_records" ] ) ){
        $( paging_bar_bnext ).addClass( "paging_inactive" );
      } else {
        $( paging_bar_bnext ).removeClass( "paging_inactive" );
        $( paging_bar_bnext ).on( "click", function(){
          var args = [
            {
              action: "cs_wg_fetch_tags_proxy",
              orderby: "name",
              order: "ASC",
              page: parseInt( theheader[ "current_page" ] )+1,
              ppp: theheader[ "posts_per_page" ],
              lib: theheader[ "supplied_arguements" ].lib,
              media: theheader[ "supplied_arguements" ].media,
              prod: theheader[ "supplied_arguements" ].prod,
              com: theheader[ "supplied_arguements" ].com,
              search: theheader[ "supplied_arguements" ].search,
              target: target
            }
          ];
          $( "body" ).wgResources( "fetch", args );
        });
      }
      $( paging_bar_bottom ).append( paging_bar_bprev, paging_bar_bnext );
      $( "#" + target ).append( paging_bar_top, wrapper_row, paging_bar_bottom );
    } else {
      var empty_msg_outter = add_element('[{"type":"div","atts":[{"id":"cswg_resources_empty_outter","class":"cswg_resources_empty_outter" }],"content":"" }]');
      var empty_msg_inner = add_element('[{"type":"div","atts":[{"id":"cswg_resources_empty_inner","class":"cswg_resources_empty_inner" }],"content":"No files were found that matched the given criteria." }]');
      $( empty_msg_outter ).append( empty_msg_inner );
      $( "#" + target ).append( empty_msg_outter );
    }
  }
  $.fn.wgResources = function( method, options ) {
    if( methods[ method ] ) {
      //console.log( JSON.stringify( options, null, 4 ) );
      methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ) );
    } else if( typeof method === 'object' || !method ) {
      return methods.init.apply( this, options );
    } else {
      console.log( 'Method ' + method + ' does not exist on wgResources' );
    }
  };
  $.fn.wgResources.defaults = {
    action: "cs_wg_fetch_attachments_proxy",
    orderby: "post_title",
    order: "ASC",
    ppp: 20,
    lib: "",
    media: "",
    prod: "",
    com: ""
  }
})( jQuery );
