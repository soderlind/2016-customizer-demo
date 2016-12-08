/*
 * Script run inside a Customizer control sidebar
 *
 * Enable / disable the control title by toggeling its .disabled-control-title style class on or off.
 */
( function( $ ) {
	wp.customize.bind( 'ready', function() {
	    var customize = this; // Customize object alias.
		// Array with the control names
		var toggleControls = [
            'svg_logo_remove',
            'show_date',
			'show_author'
        ];
	    $.each( toggleControls, function( index, control_name ) {
			customize( control_name, function( value ) {
		        var controlTitle = customize.control( control_name ).container.find( '.customize-control-title' ); // Get control  title.
				// 1. On loading.
				controlTitle.toggleClass('disabled-control-title', !value.get() );
		        // 2. Binding to value change.
		        value.bind( function( to ) {
		            controlTitle.toggleClass( 'disabled-control-title', !value.get() );
		        } );
		    } );
		} );
	} );
} )( jQuery );
