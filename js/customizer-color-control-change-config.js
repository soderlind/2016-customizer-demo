/* global jQuery.wp.wpColorPicker, _ColorControlPalette */
/* exported _ColorControlConfig */
var ColorControlConfig = ( function( $, colorpicker, options ) {
	var default_config = {
			defaultColor: false,
			change: false,
			clear: false,
			hide: true,
			palettes: true,
			width: 255,
			mode: 'hsv',
			type: 'full',
			slider: 'horizontal'
	};
	if ( options ) { // only modify if options is set
		$.extend( colorpicker.prototype.options, default_config, options );
	}
}( jQuery, jQuery.wp.wpColorPicker, _ColorControlConfig ) );
