/* global wp, _validateWCAGColorContrastExports */
/* exported validateWCAGColorContrast */
var validateWCAGColorContrast = ( function( $, api, exports ) {
	var self = {
		validate_color_contrast: []
	};
	if ( exports ) {
		$.extend( self, exports );
	}
	/**
	 * Add contrast validation to a control if it is entitled (is a valid color control).
	 *
	 * @param {wp.customize.Control} setting                   - Control.
	 * @param {wp.customize.Value}   setting.validationMessage - Validation message.
	 * @return {boolean} Whether validation was added.
	 */
	self.addWCAGColorContrastValidation = function( setting ) {
		var initialValidate;

		if ( ! self.isColorControl( setting ) ) {
			return false;
		}
		initialValidate = setting.validate;

		/**
		 * Wrap the setting's validate() method to do validation on the value to be sent to the server.
		 *
		 * @param {mixed} value   - New value being assigned to the setting.
		 * @returns {*}
		 */
		setting.validate = function( value ) {
			var setting       = this, title, validationError;
			var current_color = value;
			var current_id    = this.id;

			var all_color_controls = _.union( _.flatten( _.values( self.validate_color_contrast  ) ) );

			// remove other (old) notifications
			_.each ( _.without ( all_color_controls , current_id ), function( other_color_control_id ) {
				 var other_control = api.control.instance( other_color_control_id );
				 notice            = other_control.container.find('.notice');
				 notice.hide();
			} );

			// find other color controls and check contrast with current color control
			var other_color_controls =  self.validate_color_contrast[ current_id ];

			_.each ( other_color_controls, function( other_color_control_id ) {
				var other_control = api.control.instance( other_color_control_id);
				var other_color   = other_control.container.find('.color-picker-hex').val();
				var name          = $( '#customize-control-' + other_color_control_id + ' .customize-control-title').text();
				var contrast      = self.hex( current_color, other_color );
				var score         = self.score( contrast );

				// contrast >= 7 ? "AAA" : contrast >= 4.5 ? "AA" : ""
				if ( contrast <  4.5 ) {
					setting.notifications.remove( other_color_control_id );
					validationWarning = new api.Notification( other_color_control_id, { message: self.sprintf( 'WCAG conflict with "%s"<br/>contrast: %s' ,name, contrast), type: 'warning' } );
					setting.notifications.add( validationWarning.code, validationWarning );
					// console.log(  color_control_id + ' ' + color + ' ' + contrast + ' ' + score );
				} else {
					setting.notifications.remove( other_color_control_id );
				}
			} );

			return value;
		};

		return true;
	};

	/**
	 * Return whether the setting is entitled (i.e. if it is a title or has a title).
	 *
	 * @param {wp.customize.Setting} setting - Setting.
	 * @returns {boolean}
	 */
	self.isColorControl = function( setting ) {
		 return _.findKey( self.validate_color_contrast, function( key, value ) {
			return value == setting.id;
		} );
	};

	api.bind( 'add', function( setting ) {
		self.addWCAGColorContrastValidation( setting );
	} );


	self.sprintf = function( format ) {
		for( var i=1; i < arguments.length; i++ ) {
			format = format.replace( /%s/, arguments[i] );
		}
		return format;
	};

	/**
	 * Methods used to calculate WCAG Color Contrast
	 */

	// from https://github.com/sindresorhus/hex-rgb
	self.hexRgb = function (hex) {
		if (typeof hex !== 'string') {
			throw new TypeError('Expected a string');
		}

		hex = hex.replace(/^#/, '');

		if (hex.length === 3) {
			hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
		}

		var num = parseInt(hex, 16);

		return [num >> 16, num >> 8 & 255, num & 255];
	};

	// from https://github.com/tmcw/relative-luminance
	// # Relative luminance
	//
	// http://www.w3.org/TR/2008/REC-WCAG20-20081211/#relativeluminancedef
	// https://en.wikipedia.org/wiki/Luminance_(relative)
	// https://en.wikipedia.org/wiki/Luminosity_function
	// https://en.wikipedia.org/wiki/Rec._709#Luma_coefficients

	// red, green, and blue coefficients
	var rc = 0.2126,
	  gc = 0.7152,
	  bc = 0.0722,
	  // low-gamma adjust coefficient
	  lowc = 1 / 12.92;

	self.adjustGamma = function( g ) {
	  return Math.pow((g + 0.055) / 1.055, 2.4);
	};

	/**
	 * Given a 3-element array of R, G, B varying from 0 to 255, return the luminance
	 * as a number from 0 to 1.
	 * @param {Array<number>} rgb 3-element array of a color
	 * @returns {number} luminance, between 0 and 1
	 * @example
	 * var luminance = require('relative-luminance');
	 * var black_lum = luminance([0, 0, 0]); // 0
	 */
	self.relativeLuminance = function (rgb) {
	  var rsrgb = rgb[0] / 255;
	  var gsrgb = rgb[1] / 255;
	  var bsrgb = rgb[2] / 255;

	  var r = rsrgb <= 0.03928 ? rsrgb * lowc : self.adjustGamma(rsrgb),
	    g = gsrgb <= 0.03928 ? gsrgb * lowc : self.adjustGamma(gsrgb),
	    b = bsrgb <= 0.03928 ? bsrgb * lowc : self.adjustGamma(bsrgb);

	  return r * rc + g * gc + b * bc;
	};


	// from https://github.com/tmcw/wcag-contrast
	/**
	 * Get the contrast ratio between two relative luminance values
	 * @param {number} a luminance value
	 * @param {number} b luminance value
	 * @returns {number} contrast ratio
	 * @example
	 * luminance(1, 1); // = 1
	 */
	self.luminance = function(a, b) {
	  var l1 = Math.max(a, b);
	  var l2 = Math.min(a, b);
	  return (l1 + 0.05) / (l2 + 0.05);
	};

	/**
	 * Get a score for the contrast between two colors as rgb triplets
	 * @param {array} a
	 * @param {array} b
	 * @returns {number} contrast ratio
	 * @example
	 * rgb([0, 0, 0], [255, 255, 255]); // = 21
	 */
	self.rgb = function(a, b) {
	  return self.luminance(self.relativeLuminance(a), self.relativeLuminance(b));
	};

	/**
	 * Get a score for the contrast between two colors as hex strings
	 * @param {string} a hex value
	 * @param {string} b hex value
	 * @returns {number} contrast ratio
	 * @example
	 * hex('#000', '#fff'); // = 21
	 */
	self.hex = function(a, b) {
	  return self.rgb(self.hexRgb(a), self.hexRgb(b));
	};

	/**
	 * Get a textual score from a numeric contrast value
	 * @param {number} contrast
	 * @returns {string} score
	 * @example
	 * score(10); // = 'AAA'
	 */
	self.score = function(contrast) {
	  return contrast >= 7 ? "AAA" : contrast >= 4.5 ? "AA" : "";
	};

	return self;

}( jQuery, wp.customize, _validateWCAGColorContrastExports ) );
