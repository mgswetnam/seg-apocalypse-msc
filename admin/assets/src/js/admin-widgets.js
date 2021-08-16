jQuery( document ).ready( function( $ ) {
  var id_prefix,name_prefix;
  var thefields = ["Title","Desc","URL","Icon"];
  if( $( ".widget-liquid-right .seg-featured-links.text-field.link-heading" ).length ) {
    refresh_add_link();
    refresh_functions();
  }

  $( document ).on( 'widget-updated widget-added', function(){
    refresh_add_link();
    refresh_functions();
  } );

  function refresh_add_link(){
    $( ".seg-featured-links.form-button" ).on( "click", function( e ){
      e.preventDefault();

      id_prefix = $( this ).attr( "id" ).replace( "seg-add-link", "" );
      name_prefix = $( this ).attr( "name" ).replace( "[seg-add-link]", "" );

      var linknum = "1";
      if( $( "fieldset[id^='" + id_prefix + "seg-featured-link|']" ).length == 0 ){
        linknum = "1";
      } else {
        linknum = $( "fieldset[id^='" + id_prefix + "seg-featured-link|']" ).last().data( "link-number" );
        linknum++;
      }
      // Render fields
      var fieldset = add_element( '[{"type":"fieldset","atts":[{"id":"' + id_prefix + 'seg-featured-link|' + linknum + '","class":"seg-admin seg-featured-link","data-link-number":"' + linknum + '"}],"content":"" }]' );
      var deletebtn = add_element( '[{"type":"i","atts":[{"id":"' + id_prefix + 'seg-remove-link-' + linknum + '","class":"seg-remove-link dashicons dashicons-dismiss"}],"content":"" }]' );
      $( deletebtn ).on( "click", function(){ remove_link( linknum ); } );
      var deletewrap = add_element( '[{"type":"span","atts":[{}],"content":"" }]' );
      $( deletewrap ).append( deletebtn );
      var upbtn = add_element( '[{"type":"i","atts":[{"id":"' + id_prefix + 'seg-up-link-' + linknum + '","class":"seg-up-link dashicons dashicons-arrow-up-alt2"}],"content":"" }]' );
      var upwrap = add_element( '[{"type":"span","atts":[{}],"content":"" }]' );
      $( upwrap ).append( upbtn );
      var downbtn = add_element( '[{"type":"i","atts":[{"id":"' + id_prefix + 'seg-down-link-' + linknum + '","class":"seg-down-link dashicons dashicons-arrow-down-alt2"}],"content":"" }]' );
      var downwrap = add_element( '[{"type":"span","atts":[{}],"content":"" }]' );
      $( downwrap ).append( downbtn );
      var legend = add_element( '[{"type":"legend","atts":[{"class":"seg-link-name"}],"content":"Link ' + linknum + ' " }]' );
      $( legend ).append( deletewrap,upwrap,downwrap );
      $( fieldset ).append( legend );
      $.each( thefields, function( k, v ){
        var wrapper = add_element( '[{"type":"div","atts":[{"class":"field-wrapper ' + v.toLowerCase() + '-wrap"}],"content":"" }]' );
        var label = add_element( '[{"type":"label","atts":[{"for":"' + id_prefix + v.toLowerCase() + '-' + linknum + '"}],"content":"' + v + '" }]' );
        var input = add_element('[{"type":"input","atts":[{"id":"' + id_prefix + v.toLowerCase() + '-' + linknum + '","name":"' + name_prefix + '[' + v.toLowerCase() + '][]","class":"","size":"40"}],"content":"" }]');
        $( label ).append( input );
        $( wrapper ).append( label );
        $( fieldset ).append( wrapper );
      } );
      $( "#" + id_prefix + "seg-featured-links-wrapper" ).append( fieldset );
    } );
  }

  function refresh_functions(){
    $( "i[id*='seg-remove-link-']" ).each( function(){
      var mylinknum = $( this ).closest("fieldset").data( "link-number" );
      id_prefix = $( this ).closest("fieldset").data( "id-prefix" );
      $( this ).off( "click" ).on( "click", function(){ remove_link( mylinknum ); } );
    } );
    $( "i[id*='seg-up-link-']" ).each( function(){
      var mylinknum = $( this ).closest("fieldset").data( "link-number" );
      id_prefix = $( this ).closest("fieldset").data( "id-prefix" );
      $( this ).off( "click" ).on( "click", function(){ move_link_up( mylinknum ); } );
    } );
    $( "i[id*='seg-down-link-']" ).each( function(){
      var mylinknum = $( this ).closest("fieldset").data( "link-number" );
      id_prefix = $( this ).closest("fieldset").data( "id-prefix" );
      $( this ).off( "click" ).on( "click", function(){ move_link_down( mylinknum ); } );
    } );
  }

  function remove_link( linknum ){
    if( confirm( "Are you sure you want to delete link " + linknum + "? NOTICE: CHANGE WILL ONLY BECOME PERMANENT ONCE THE WIDGET IS SAVED." ) ){
      $( "fieldset[id='" + id_prefix + "seg-featured-link|" + linknum + "'" ).remove();
      renumber_links();
    }
  }

  function move_link_up( linknum ){
    if( $( "fieldset[id='" + id_prefix + "seg-featured-link|" + linknum ).is(':not(:first-child)') ){
      var prevnum = linknum-1;
      $( "fieldset[id='" + id_prefix + "seg-featured-link|" + linknum ).insertBefore( "fieldset[id='" + id_prefix + "seg-featured-link|" + prevnum );
      renumber_links();
    }
  }

  function move_link_down( linknum ){
    if( $( "fieldset[id='" + id_prefix + "seg-featured-link|" + linknum ).is(':not(:last-child)') ){
      var nextnum = linknum+1;
      $( "fieldset[id='" + id_prefix + "seg-featured-link|" + linknum ).insertAfter( "fieldset[id='" + id_prefix + "seg-featured-link|" + nextnum );
      renumber_links();
    }
  }

  function renumber_links(){
    $( "div[id*='seg-featured-links-wrapper" ).each( function(){
      var i=1;
      $( "fieldset[id*='seg-featured-link|" ).each( function(){
        id_prefix = $( this ).closest("fieldset").data( "id-prefix" );
        $( this ).data( "link-number", i );
        $( this ).attr( "data-link-number", i );
        $( this ).attr( "id", id_prefix + "seg-featured-link|" + i );
        $( "legend", this )[ 0 ].childNodes[ 0 ].nodeValue = "Link " + i;
        $( "i[id^='" + id_prefix + "seg-remove-link-']", this ).attr( "id", id_prefix + "seg-remove-link-" + i );
        $( "i[id^='" + id_prefix + "seg-up-link-']", this ).attr( "id", id_prefix + "seg-up-link-" + i );
        $( "i[id^='" + id_prefix + "seg-down-link-']", this ).attr( "id", id_prefix + "seg-down-link-" + i );
        $( "label[for^='" + id_prefix + "title-']", this ).attr( "for", id_prefix + "title-" + i );
        $( "input[id^='" + id_prefix + "title-']", this ).attr( "id", id_prefix + "title-" + i );
        $( "label[for^='" + id_prefix + "desc-']", this ).attr( "for", id_prefix + "desc-" + i );
        $( "input[id^='" + id_prefix + "desc-']", this ).attr( "id", id_prefix + "desc-" + i );
        $( "label[for^='" + id_prefix + "url-']", this ).attr( "for", id_prefix + "url-" + i );
        $( "input[id^='" + id_prefix + "url-']", this ).attr( "id", id_prefix + "url-" + i );
        $( "label[for^='" + id_prefix + "icon-']", this ).attr( "for", id_prefix + "icon-" + i );
        $( "input[id^='" + id_prefix + "icon-']", this ).attr( "id", id_prefix + "icon-" + i );
        i++;
      } );
      refresh_functions();
    } );
  }

  function add_element( data ) {
    var thedata = JSON.parse( data );
    var theelement;
    $.each( thedata, function( index ){
      theelement = document.createElement( thedata[ index ].type );
      var theatts = thedata[ index ].atts;
      $.each( theatts, function( i ){
        var thekeys = Object.keys( theatts[ i ] );
        $.each( thekeys, function( mykey ){
          var k = thekeys[ mykey ];
          var v = theatts[ i ][ thekeys[ mykey ] ];
          if( k==="class" ) {
            $( theelement ).addClass( v );
          } else {
            $( theelement ).attr( k, v );
          }
        } );
      } );
      $( theelement ).html( thedata[ index ].content );
    } );
    return theelement;
  }
} );
