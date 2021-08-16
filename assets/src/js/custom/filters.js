( function( $ ) {
    var methods = {
     tags: function( options ){
       var postdata = this.create_object;
       $.ajax({
         url: ajax_object.ajax_url,
         type:'POST',
         dataType: 'json',
         data: postdata,
         error: function( jqXHR, textStatus, errorThrown ){
           console.log( JSON.stringify( errorThrown, null, 4 ) );
         },
         success: function( data, textStatus, jqXHR ){
           // render_tags( data );
           console.log( JSON.stringify( data ) );
         }
       });
     },
     create_object: function( options ){
       return {
         "action":	options.action,
         "orderby": options.orderby,
         "order": options.order,
         "page": options.page,
         "ppp": options.ppp,
         "lib": options.lib,
         "media": options.media,
         "prod": options.prod,
         "com": options.com,
         "search": options.search
       };
     }
   }
   function render_tags( data ){
     var thedata = data.content;
     $.each( thedata, function( index ){
       var tagdata = thedata[ index ].data;
       var n = thedata[ index ].target.lastIndexOf('_');
       var fname = thedata[ index ].target.substring(n + 1);
       var first_option = add_element('[{"type":"option","atts":[{"value":"" }],"content":"Select ' + fname.charAt(0).toUpperCase() + fname.slice(1) + '" }]');
       $( "#" + thedata[ index ].target ).append( first_option );
       $.each( tagdata, function( ind ){
         var select_option = add_element('[{"type":"option","atts":[{"value":"' + tagdata[ ind ][ 0 ] + '" }],"content":"' + tagdata[ ind ][ 1 ] + '" }]');
         $( "#" + thedata[ index ].target ).append( select_option );
       });
     });
   }
   function create_object( action, orderby, order, page, ppp, lib, media, prod, com, search ){
     var postdata = {
       "action":	action,
       "orderby": orderby,
       "order": order,
       "page": page,
       "ppp": ppp,
       "lib": lib,
       "media": media,
       "prod": prod,
       "com": com,
       "search": search
     };
     return postdata;
   }
   function add_element( data ) {
     var thedata = JSON.parse( data );
     var theelement;
     $.each( thedata, function( index ){
       theelement = document.createElement( thedata[ index ].type);
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

   $.fn.wgResources = function( method, options ) {
     options = $.extend( {}, $.fn.wgResources.defaults, options );
     if( methods[ method ] ) {
       methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ) );
     } else if( typeof method === 'object' || !method ) {
       return methods.init.apply( this, options );
     } else {
       console.log( 'Method ' + method + ' does not exist on jquery.filters' );
     }
   };
})( jQuery );
