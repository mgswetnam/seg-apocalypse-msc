/*
 * csa_customizer v1.0
 * -------------------
 * Copyright (c) 2019 Matthew Swetnam
 */
( function( $ ) {

	var csac = wp.customize;

  CSACustomizer = {

		/**
		 * Initializes our custom logic for the Customizer.
		 *
		 * @since 1.2.0
		 * @method init
		 */
		init: function(){
      console.log( "SEG Customizer" );
      //csac.previewer.refresh();
		},
	};

  $( function() { CSACustomizer.init(); } );

})( jQuery );
