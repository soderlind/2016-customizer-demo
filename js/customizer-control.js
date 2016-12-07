( function( $ ) {
	wp.customize.bind( 'ready', function() { // Ready?

	    var customize = this; // Customize object alias.
	    customize( 'svg_logo_remove', function( value ) {
	        var controlTitle = customize.control( 'svg_logo_remove' ).container.find( '.customize-control-title' ); // Get control  title.
	        // 1. On loading.
			controlTitle.toggleClass('disabled', !value.get() );
	        // 2. Binding to value change.
	        value.bind( function( to ) {
	            controlTitle.toggleClass( 'disabled-control-title', !value.get() );
	        } );
	    } );
	} );
} )( jQuery );
