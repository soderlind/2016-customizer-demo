<?php
/**
 *  Best practices, load parent style from functions.php don't use @import in style.css
 *
 * @author soderlind
 * @version 1.0.0
 */
function theme_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array(), rand() );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );


/**
 * Add to customizer.
 *
 * @author soderlind
 * @version 1.0.0
 * @param   WP_Customize_Manager    $wp_customize
 */
function customize_post_meta( $wp_customize ) {

	$wp_customize->add_section( 'post_meta', array(
			'title'    => _x( 'Post Meta', 'customizer menu section', '2016-customizer-demo' ),
			'priority' => 25,
	) );
	$wp_customize->add_setting( 'show_author', array(
			'default'    => '1',
			'capability' => 'manage_options',
			'transport' => 'postMessage',
			// 'transport' => 'refresh',
	) );
	$wp_customize->add_control( 'show_author', array(
			'settings' => 'show_author',
			'label'    => _x( 'Show Author', 'customizer menu setting', '2016-customizer-demo' ),
			'section'  => 'post_meta',
			'type'     => 'checkbox',
	) );

	// $wp_customize->selective_refresh->add_partial( 'show_author', array(
	//     'selector' => '.byline',
	//     'container_inclusive' => false,
	//     // 'render_callback' => function() {
	// 	// 	echo  ( '1' === get_theme_mod( 'show_author' ) ) ? 0: 1;
	//     //     // bloginfo( 'description' );
	//     // },
	// ) );

	$wp_customize->add_setting( 'show_date', array(
			'default'    => '1',
			'capability' => 'manage_options',
			'transport' => 'postMessage',
	) );
	$wp_customize->add_control( 'show_date', array(
			'settings' => 'show_date',
			'label'    => _x( 'Show Date', 'customizer menu setting', '2016-customizer-demo' ),
			'section'  => 'post_meta',
			'type'     => 'checkbox',
	) );

	// $wp_customize->selective_refresh->add_partial( 'show_date', array(
	//     'selector' => '.posted-on a',
	//     'container_inclusive' => false,
	//     'render_callback' => function() {
	// 		echo date( get_option( 'date_format' ) );
	//         // bloginfo( 'description' );
	//     },
	// ) );

	$wp_customize->add_section( 'svg_logo', array(
			'title'    => _x( 'SVG Logo', 'customizer menu section', '2016-customizer-demo' ),
			'priority' => 10,
	) );

	$wp_customize->add_setting( 'svg_logo_url', array(
			// 'default'       => '5',
			'capability'    => 'edit_theme_options',
			'transport'     => 'postMessage',
	) );

	$wp_customize->add_control( new DSS_SVG_Picker_Option( $wp_customize, 'svg_logo_url', array(
			// 'label'	      => esc_html__( 'Select SVG Logo', '2016-customizer-demo' ),
			'section'     => 'svg_logo',
			'settings'    => 'svg_logo_url',
			'type'        => 'svg',
	) ) );

	$wp_customize->add_setting( 'svg_logo_width', array(
			'default'       => get_theme_mod( 'svg_logo_width', '240' ),
			'capability'    => 'edit_theme_options',
			'transport'     => 'postMessage',
	) );

	$wp_customize->add_control( 'svg_logo_width', array(
			'type' => 'range',
			'section' => 'svg_logo',
			'settings'    => 'svg_logo_width',
			'label' => __( 'Logo Width' ),
			// 'description' => __( 'Width in px, default and max-width 240px' ),
			'input_attrs' => array(
				'min' => 1,
				'max' => 240,
				'step' => 1,
		  ),
	) );

	// $wp_customize->add_setting( 'svg_logo_remove', array(
	// 		'default'       => true,
	// 		'capability'    => 'edit_theme_options',
	// 		'transport'     => 'postMessage',
	// ) );
	//
	// $wp_customize->add_control( 'svg_logo_remove', array(
	// 		'label'	      => esc_html__( 'Display SVG Logo', '2016-customizer-demo' ),
	// 		'section'     => 'svg_logo',
	// 		'settings'    => 'svg_logo_remove',
	// 		'type'        => 'checkbox',
	// ) ) ;

	$wp_customize->add_setting( 'svg_logo_remove', array(
			'default'       => true,
			'capability'    => 'edit_theme_options',
			'transport'     => 'postMessage',
	) );

	$wp_customize->add_control( new DSS_Toggle( $wp_customize, 'svg_logo_remove', array(
			'label'	      => esc_html__( 'Display SVG Logo', '2016-customizer-demo' ),
			'section'     => 'svg_logo',
			'settings'    => 'svg_logo_remove',
			'type'        => 'toggle',
			'input_attrs' => array(
					'off' => 'off text',
					'on'  => 'on text',
			),
	) ) );

	// $wp_customize->selective_refresh->add_partial( 'svg_logo_url', array(
	//     'selector' => '.svg-logo',
	//     'container_inclusive' => false,
	//     'render_callback' => function() {
	// 		echo date( get_option( 'date_format' ) );
	//         // bloginfo( 'description' );
	//     },
	// ) );
	//
	// $partial = $wp_customize->selective_refresh->get_partial( 'custom_logo' );
	//
	//
	// // @codingStandardsIgnoreStart
	// printf( '<pre>%s</pre>', print_r( $partial, true ) );
	// // @codingStandardsIgnoreEnd
	//
	// $wp_customize->selective_refresh->remove_partial( 'custom_logo');

}
add_action( 'customize_register','customize_post_meta' );

/**
 * Enqueue customizer preview script.
 *
 * @author soderlind
 * @version 1.0.0
 */
function customize_preview_post_meta() {
	wp_enqueue_script( 'customize_preview_post_meta', get_stylesheet_directory_uri() . '/js/customizer-preview.js', array( 'customize-preview', 'jquery' ), rand(), true );
}
add_action( 'customize_preview_init', 'customize_preview_post_meta' );

/**
 * Load translations for 2016-customizer-demo
 *
 * @author soderlind
 * @version 1.0.0
 */
function customizer_demo_theme_setup() {
	load_theme_textdomain( '2016-customizer-demo', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'customizer_demo_theme_setup' );

/**
 * Extend the default twentysixteen_entry_meta() function with test for 'show_author' and 'show_date'
 *
 * @author soderlind
 * @version 1.0.0
 */
function twentysixteen_entry_meta() {
	if ( 'post' === get_post_type() && '1' == get_theme_mod( 'show_author', '1' ) ) {
		$author_avatar_size = apply_filters( 'twentysixteen_author_avatar_size', 49 );
		printf( '<span class="byline"><span class="author vcard">%1$s<span class="screen-reader-text">%2$s </span> <a class="url fn n" href="%3$s">%4$s</a></span></span>',
			get_avatar( get_the_author_meta( 'user_email' ), $author_avatar_size ),
			_x( 'Author', 'Used before post author name.', '2016-customizer-demo' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			get_the_author()
		);
	}

	if ( in_array( get_post_type(), array( 'post', 'attachment' ) ) && '1' == get_theme_mod( 'show_date', '1' )  ) {
		twentysixteen_entry_date();
	}

	$format = get_post_format();
	if ( current_theme_supports( 'post-formats', $format ) ) {
		printf( '<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
			sprintf( '<span class="screen-reader-text">%s </span>', _x( 'Format', 'Used before post format.', '2016-customizer-demo' ) ),
			esc_url( get_post_format_link( $format ) ),
			get_post_format_string( $format )
		);
	}

	if ( 'post' === get_post_type() ) {
		twentysixteen_entry_taxonomies();
	}

	if ( ! is_singular() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( sprintf( __( 'Leave a comment<span class="screen-reader-text"> on %s</span>', '2016-customizer-demo' ), get_the_title() ) );
		echo '</span>';
	}
}


/**
 * Add custom customizer control
 * Check for WP_Customizer_Control existence before adding custom control because WP_Customize_Control
 * is loaded on customizer page only
 *
 * @see _wp_customize_include()
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	/**
	 * Custom customizer option control
	 */
	class DSS_SVG_Picker_Option extends WP_Customize_Control {
		public $type = 'svg';

		public function render_content() {
		?>
		<label>
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<?php echo list_svg( esc_attr( $this->value() ) ); ?>
			<input type="hidden" id="selected_svg" <?php $this->input_attrs(); ?> value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
		</label>
		<?php
		}
	}


	class DSS_Toggle extends WP_Customize_Control {
		public $type = 'toggle';

		/**
		 * Enqueue scripts/styles for the color picker.
		 *
		 * @since 3.4.0
		 */
		public function enqueue() {
			global $_wp_admin_css_colors;
			wp_enqueue_script( 'pure-css-toggle-buttons', get_stylesheet_directory_uri() . '/js/customizer.js', array( 'jquery' ), rand(), true );
			wp_enqueue_style( 'pure-css-toggle-buttons', get_stylesheet_directory_uri() . '/pure-css-toggle-buttons/pure-css-togle-buttons.css', array(), rand() );

			$color_scheme = array();
			$colors = array();
			$default_colors = array('#222','#333');
			$user_color_scheme_name = get_user_option( 'admin_color' );
			if ( isset( $user_color_scheme_name ) && '' !== $user_color_scheme_name ) {
				$color_scheme = $_wp_admin_css_colors[get_user_option( 'admin_color' )];
				$colors =  wp_parse_args( $_wp_admin_css_colors['colors'], $default_colors );
			} else {
				$colors = $default_colors;
			}

			/*
			[blue] => stdClass Object
			        (
			            [name] => Blue
			            [url] => http://customizer.dev/wp-admin/css/colors/blue/colors.min.css
			            [colors] => Array
			                (
			                    [0] => #096484
			                    [1] => #4796b3
			                    [2] => #52accc
			                    [3] => #74B6CE
			                )

			            [icon_colors] => Array
			                (
			                    [base] => #e5f8ff
			                    [focus] => #fff
			                    [current] => #fff
			                )

			        )
			 */



				$css = '
					.disabled-control-title {
						color: #a0a5aa;
					}
					input[type=checkbox].tgl-light:checked + .tgl-btn {
				  		background: #0085ba;
					}
					input[type=checkbox].tgl-light + .tgl-btn {
					  background: #a0a5aa;
				  	}
					input[type=checkbox].tgl-light + .tgl-btn:after {
					  background: #f7f7f7;
				  	}

					input[type=checkbox].tgl-ios:checked + .tgl-btn {
					  background: #0085ba;
					}
				';
				wp_add_inline_style( 'pure-css-toggle-buttons' , $css );
		}

		public function render_content() {
			global $_wp_admin_css_colors;
			// @codingStandardsIgnoreStart
			// printf( '<pre>%s</pre>', print_r( $_wp_admin_css_colors[get_user_option( 'admin_color' )], true ) );
			// printf( '<pre>%s</pre>', print_r( $_wp_admin_css_colors, true ) );
			// echo '<pre>' . wp_color_scheme_settings() . ' </pre>';
			// @codingStandardsIgnoreEnd
			/*
			(
			    [name] => Default
			    [url] =>
			    [colors] => Array
			        (
			            [0] => #222
			            [1] => #333
			            [2] => #0073aa
			            [3] => #00a0d2
			        )

			    [icon_colors] => Array
			        (
			            [base] => #82878c
			            [focus] => #00a0d2
			            [current] => #fff
			        )

			)
			*/
			?>
			<!-- <label> -->
				<div style="display:flex;flex-direction: row;justify-content: flex-start;">
				<span class="customize-control-title" style="flex: 2 0 0; vertical-align: middle;"><?php echo esc_html( $this->label ); ?></span>
				<input id="cb1" type="checkbox" class="tgl tgl-light" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); checked( $this->value() ); ?> />
				<label for="cb1" class="tgl-btn"></label>
				</div>
				<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo $this->description; ?></span>
				<?php endif; ?>
			<!-- </label> -->
			<?php
			/*
			switch ( $this->type ) {
				case 'value':
					# code...
					break;

				default:
					# code...
					break;
			}

			<input id="cb1" type="checkbox" class="tgl tgl-light"/>
			<label for="cb1" class="tgl-btn"></label>

			<input id="cb2" type="checkbox" class="tgl tgl-ios"/>
			<label for="cb2" class="tgl-btn"></label>

			<input id="cb3" type="checkbox" class="tgl tgl-skewed"/>
		    <label data-tg-off="OFF" data-tg-on="ON" for="cb3" class="tgl-btn"></label>

			<input id="cb4" type="checkbox" class="tgl tgl-flat"/>
		    <label for="cb4" class="tgl-btn"></label>

			<input id="cb5" type="checkbox" class="tgl tgl-flip"/>
		    <label data-tg-off="Nope" data-tg-on="Yeah!" for="cb5" class="tgl-btn"></label>
			<?php
			*/
		}
	}
}

/**
 * Create a option list with URLs to SVG files. The SVG files are defined in svg.json
 *
 * @author soderlind
 * @version 1.1.0
 * @param   string    $selected_value
 * @return  string                    select tag with options
 */
function list_svg( $selected_value = '' ) {
	$ret = '';
	$svg_data_file = dirname( __FILE__ ) . '/svg.json';
	if ( file_exists( $svg_data_file ) ) {
		$svg_data = file_get_contents( $svg_data_file );
		$svgs = json_decode( $svg_data,true );
		// TODO: add error control for `if ( null === $svgs && json_last_error() !== JSON_ERROR_NONE ) {}`

		$ret = '<select class="image-picker">';
		foreach ( $svgs as $svg ) {
			if ( '' !== $selected_value && sprintf( '%s/%s', get_stylesheet_directory_uri(), $svg['file'] ) === $selected_value ) {
				$ret .= sprintf( '<option selected data-img-class="svg-logo"  data-img-src="%1$s/%2$s" value="%1$s/%2$s" >%2$s</option>', get_stylesheet_directory_uri(), $svg['file'] );
			} else {
				$ret .= sprintf( '<option data-img-class="svg-logo"  data-img-src="%1$s/%2$s" value="%1$s/%2$s" >%2$s</option>', get_stylesheet_directory_uri(), $svg['file'] );
			}
		}
		$ret .= '</select>';
	}
	return $ret;
}

/**
 * Enqueue the image-picker jQuery plugin (https://rvera.github.io/image-picker/)
 *
 * @author soderlind
 * @version 1.1.0
 */
function customizer_svg_enqueue_scripts() {
	wp_enqueue_style( 'image-picker', get_stylesheet_directory_uri() . '/js/image-picker/image-picker.css' );
	$css = '
		.svg-logo {
			width: 55px;
			/*text-align: center;*/
		}
		.svg-logo img {
			background-color: #ffffff;
		}
	';
	wp_add_inline_style( 'image-picker', $css );

	wp_enqueue_script( 'image-picker', get_stylesheet_directory_uri() . '/js/image-picker/image-picker.js', array( 'jquery' ), '1.0.11', true );
	wp_add_inline_script( 'image-picker', 'jQuery(document).ready(function($){
		$("select.image-picker").imagepicker({
			selected : function(){
				$("#selected_svg").val( $(this).val() ); // save selected svg logo in hidden field
				$("#selected_svg").change(); // trigger Save & Publish  change in customizer
 			}
		});
	});', 'after' );
}
add_action( 'customize_controls_enqueue_scripts', 'customizer_svg_enqueue_scripts' );

/**
 * Use the get_custom_logo filter to change the custom logo
 *
 * @author soderlind
 * @version 1.1.0
 * @return  string  custom logo, linked to home.
 */
function add_svg_logo( $html ) {
	$svg_logo_remove = get_theme_mod( 'svg_logo_remove', false );
	if ( false !== $svg_logo_remove ) {
		$svg_logo_url  = get_theme_mod( 'svg_logo_url', '' );
		$svg_logo_width = get_theme_mod( 'svg_logo_width', '240' );
		if ( '' !== $svg_logo_url ) {
			$html = sprintf( '<a href="%1$s" class="custom-logo-link" rel="home" itemprop="url">%2$s</a>',
			    esc_url( home_url( '/' ) ),
				sprintf( '<img src="%s" class="custom-logo svg-logo" style="width: %spx"/>',$svg_logo_url, $svg_logo_width )
			);
		}
	} else {
		remove_theme_mod( 'svg_logo_url' );
	}
	return $html;
}
add_filter( 'get_custom_logo', 'add_svg_logo' );
