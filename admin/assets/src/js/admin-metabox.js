var _timer;
jQuery( document ).ready( function( $ ){
  var Timer = function( callback, delay ){
    var timerId, start, remaining = delay;
    this.pause = function(){
      window.clearTimeout( timerId );
      remaining -= Date.now() - start;
    };
    this.resume = function(){
      start = Date.now();
      window.clearTimeout(timerId);
      timerId = window.setTimeout(callback, remaining);
    };
    this.resume();
  };

  refresh_copy_events();
  refresh_copy_atts_events();

  function refresh_copy_events(){
    $( ".seg-sc-copy.no-atts" ).off();
    $( "#temp-copy-confirm" ).remove();
    $( ".seg-sc-copy.no-atts" ).on( "click", function( e ){
      e.preventDefault();
      var parent = $( this ).parent();
      var shortcode = "\[" + $( parent ).data( "sc-code" ) + "\]";
      copy_to_clipboard( parent, shortcode );
      var verified = add_element('[{"type":"i","atts":[{"id":"temp-copy-confirm","class":"fas fa-copy"}],"content":""}]');
      $( parent ).append( verified );
      var _timer = new Timer( function(){
        $( "#temp-copy-confirm" ).remove();
      }, 4000);
    } );
  }

  function refresh_copy_atts_events(){
    $( ".seg-sc-copy.with-atts" ).off();
    $( "#temp-copy-confirm" ).remove();
    $( ".seg-sc-copy.with-atts" ).on( "click", function( e ){
      e.preventDefault();
      var parent = $( this ).parent();
      var shortcode = $( parent ).data( "sc-code" );
      var atts = $( this ).data( "sc-args" ).split( "," );
      var attsdisplay = '';
      $.each( atts, function( index ){
        attsdisplay += atts[ index ] + "=\'\' ";
      } );
      var scoutput = "\[" + shortcode + " " + attsdisplay + "\]";
      copy_to_clipboard( parent, scoutput );
      var verified = add_element('[{"type":"i","atts":[{"id":"temp-copy-confirm","class":"fas fa-copy"}],"content":""}]');
      $( parent ).append( verified );
      var _timer = new Timer( function(){
        $( "#temp-copy-confirm" ).remove();
      }, 4000);
    } );
  }

  function copy_to_clipboard( parent, content ){
    var tempcpytxt = add_element('[{"type":"textarea","atts":[{"id":"temp-copy-textarea"}],"content":"' + content + '"}]');
    $( parent ).append( tempcpytxt );
    $( "#temp-copy-textarea" ).focus();
    $( "#temp-copy-textarea" ).select();
    try {
      var successful = document.execCommand( 'copy' );
      var msg = successful ? 'successful' : 'unsuccessful';
      console.log( 'Text copy was ' + msg );
    } catch( err ) {
      console.error( 'Failed to copy: ', err );
    }
    $( "#temp-copy-textarea" ).remove();
  }

  function add_element(data) {
    var thedata = JSON.parse(data);
    var theelement;
    $.each(thedata, function(index){
      theelement = document.createElement(thedata[index].type);
      var theatts = thedata[index].atts;
      $.each(theatts, function(i){
        var thekeys = Object.keys(theatts[i]);
        $.each(thekeys, function(mykey){
          var k = thekeys[mykey];
          var v = theatts[i][thekeys[mykey]];
          if(k==="class") {
            $(theelement).addClass(v);
          } else {
            $(theelement).attr(k, v);
          }
        });
      });
      $(theelement).html(thedata[index].content);
    });
    return theelement;
  }
} );
