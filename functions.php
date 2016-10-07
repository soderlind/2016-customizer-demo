<?php
/**
 *  Best practices, load parent style from functions.php don't use @import in style.css
 *
 * @author soderlind
 * @version 1.0.0
 */
function theme_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
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
	) );
	$wp_customize->add_control( 'show_author', array(
			'settings' => 'show_author',
			'label'    => _x( 'Show Author', 'customizer menu setting', '2016-customizer-demo' ),
			'section'  => 'post_meta',
			'type'     => 'checkbox',
	) );
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
}
add_action( 'customize_register','customize_post_meta' );

/**
 * Enqueue customizer preview script.
 *
 * @author soderlind
 * @version 1.0.0
 */
function customize_preview_post_meta() {
	wp_enqueue_script( 'customize_preview_post_meta', get_stylesheet_directory_uri() . '/js/customizer.js', array( 'customize-preview', 'jquery' ), rand(), true );
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
