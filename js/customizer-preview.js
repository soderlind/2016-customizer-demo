( function( $ ) {
	/**
	 * Author on/off
	 * 'show_author' is the name of the setting, as added by the $wp_customize->add_setting call
	 * @type {String}
	 */
	wp.customize( 'show_author', function( value ) {
		value.bind( function( to ) {
			/**
			 * Add template if missing (eg not added by the theme becuse of if statement).
			 */
			if ( 0 == $( '.byline' ).length  ) {
				$( '<span class="byline"><span class="author vcard"><img alt="" src="http://0.gravatar.com/avatar/9e16d5154b083353f2f0bc0414b7c8c3?s=49&#038;d=mm&#038;r=g" srcset="http://0.gravatar.com/avatar/9e16d5154b083353f2f0bc0414b7c8c3?s=98&amp;d=mm&amp;r=g 2x" class="avatar avatar-49 photo" height="49" width="49" /><span class="screen-reader-text">Author </span>	<a class="url fn n" href="http://customizer.dev/author/admin/">admin</a></span>' )
				.prependTo( '.entry-footer' );
			}
			/**
			 * Toggle date on/off.
			 */
			$( '.byline' ).toggle( '1' == to );
		} );
	} );
	/**
	 * Post date on/off
	 * 'show_date' is the name of the setting, as added by the $wp_customize->add_setting call
	 * @type {String}
	 */
	wp.customize( 'show_date', function( value ) {
		value.bind( function( to ) {
			/**
			 * Add template if missing (eg not added by the theme becuse of if statement).
			 */
			if ( 0 == $( '.posted-on' ).length  ) {
				$( '</span><span class="posted-on"><span class="screen-reader-text">Posted on </span><a href="http://customizer.dev/2016/09/11/hello-world/" rel="bookmark"><time class="entry-date published" datetime="2016-09-11T21:02:20+00:00">September 11, 2016</time><time class="updated" datetime="2016-10-06T23:24:39+00:00">October 6, 2016</time></a></span>' )
				.insertBefore( '.edit-link' );
			}
			/**
			 * Toggle date on/off.
			 */
			$( '.posted-on' ).toggle( '1' == to );
		} );
	} );
	/**
	 * Update SVG URL
	 * 'svg_logo_url' is the name of the setting, as added by the $wp_customize->add_setting call
	 * @type {String}
	 */
	wp.customize( 'svg_logo_url', function( value ) {
		value.bind( function( to ) {
			if ( 0 == $( '.svg-logo' ).length  ) {
				$( '.custom-logo-link' ).css( 'display', 'block' );
				$( '.custom-logo' ).addClass( 'svg-logo' );
			}
			$( '.svg-logo' ).attr( 'src', to );
			svg_logo_tmp_url = to;
		} );
	} );
	/**
	 * Change SVG width
	 * 'svg_logo_width' is the name of the setting, as added by the $wp_customize->add_setting call
	 * @type {String}
	 */
	wp.customize( 'svg_logo_width', function( value ) {
		value.bind( function( to ) {
			$( '.svg-logo' ).width( to );
		} );
	} );


	wp.customize( 'svg_logo_remove', function( value ) {
		value.bind( function( to ) {
			$( '.custom-logo-link' ).toggle( '1' == to );
		} );
	} );

} )( jQuery );
