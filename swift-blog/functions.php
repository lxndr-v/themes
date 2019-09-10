<?php
/**
 * Swift Blog functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Swift_Blog
 */

if ( ! function_exists( 'swift_blog_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function swift_blog_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Swift Blog, use a find and replace
		 * to change 'swift-blog' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'swift-blog', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary-nav' => esc_html__( 'Primary Menu', 'swift-blog' ),
			'social-nav' => esc_html__( 'Social Menu', 'swift-blog' ),
			'footer-nav' => esc_html__( 'Footer Menu', 'swift-blog' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'swift_blog_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		 /*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
		    'image',
		    'video',
		    'quote',
		    'gallery',
		    'audio',
		) );
		
		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 400,
			'width'       => 600,
			'flex-width'  => true,
			'flex-height' => true,
		) );

	}
endif;
add_action( 'after_setup_theme', 'swift_blog_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function swift_blog_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'swift_blog_content_width', 640 );
}
add_action( 'after_setup_theme', 'swift_blog_content_width', 0 );


/**
 * function for google fonts
 */
if (!function_exists('swift_blog_fonts_url')) :

    /**
     * Return fonts URL.
     *
     * @since 1.0.0
     * @return string Fonts URL.
     */
    function swift_blog_fonts_url()
    {

        $fonts_url = '';
        $fonts     = array();
        $swift_blog_title_font   = 'Josefin+Sans:400,600';
        $swift_blog_content_font   = 'Raleway:400,400i,600,600i';
        $swift_blog_meta_font   = 'Courgette';


        $swift_blog_fonts   = array();
        $swift_blog_fonts[] = $swift_blog_title_font;
        $swift_blog_fonts[] = $swift_blog_content_font;
        $swift_blog_fonts[] = $swift_blog_meta_font;

        $swift_blog_fonts_stylesheet = '//fonts.googleapis.com/css?family=';

        $i = 0;
        for ($i = 0; $i < count($swift_blog_fonts); $i++) {

            if ('off' !== sprintf(_x('on', '%s font: on or off', 'swift-blog'), $swift_blog_fonts[$i])) {
                $fonts[] = $swift_blog_fonts[$i];
            }

        }

        if ($fonts) {
            $fonts_url = add_query_arg(array(
                'family' => urldecode(implode('|', $fonts)),
            ), 'https://fonts.googleapis.com/css');
        }
        return $fonts_url;
    }
endif;

/**
 * Enqueue scripts and styles.
 */
function swift_blog_scripts() {

	$fonts_url = swift_blog_fonts_url();
	if (!empty($fonts_url)) {
	    wp_enqueue_style('swift-blog-google-fonts', $fonts_url, array(), null);
	}
	wp_enqueue_style('font-awesome', get_template_directory_uri().'/assets/libraries/font-awesome/css/font-awesome.min.css');
	wp_enqueue_style('slick', get_template_directory_uri().'/assets/libraries/slick/css/slick.css');
	wp_enqueue_style('magnific', get_template_directory_uri().'/assets/libraries/magnific/css/magnific-popup.css');
    wp_enqueue_style('aos', get_template_directory_uri() . '/assets/libraries/aos/css/aos.css');

	wp_enqueue_style( 'swift-blog-style', get_stylesheet_uri() );
	wp_add_inline_style( 'swift-blog-style', swift_blog_trigger_custom_css_action() );
	
	wp_enqueue_script( 'swift-blog-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );
	wp_enqueue_script('jquery-slick', get_template_directory_uri() . '/assets/libraries/slick/js/slick.min.js', array('jquery'), '', true);
	wp_enqueue_script('jquery-magnific', get_template_directory_uri() . '/assets/libraries/magnific/js/jquery.magnific-popup.min.js', array('jquery'), '', true);
	wp_enqueue_script('color-switcher', get_template_directory_uri() . '/assets/libraries/color-switcher/color-switcher.js', array('jquery'), '', true);
	wp_enqueue_script( 'swift-blog-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );
	wp_enqueue_script('theiaStickySidebar', get_template_directory_uri() . '/assets/libraries/theiaStickySidebar/theia-sticky-sidebar.min.js', array('jquery'), '', true);
    wp_enqueue_script('aos', get_template_directory_uri() . '/assets/libraries/aos/js/aos.js', array('jquery'), '', true);

    $args = swift_blog_get_localized_variables();
    	wp_enqueue_script('swift-blog-script', get_template_directory_uri() . '/assets/twp/js/twp-script.js', array( 'jquery', 'wp-mediaelement' ), '', true);
        wp_localize_script( 'swift-blog-script', 'swiftBlog', $args );
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'swift_blog_scripts' );

/**
 * Enqueue admin scripts and styles.
 */
function swift_blog_admin_scripts( $hook ) {
	if ( 'widgets.php' === $hook ) {
	    wp_enqueue_media();
		wp_enqueue_script( 'swift-blog-custom-widgets', get_template_directory_uri() . '/assets/twp/js/widgets.js', array( 'jquery' ), '1.0.0', true );
	}
	wp_enqueue_style( 'swift-blog-custom-admin-style', get_template_directory_uri() . '/assets/twp/css/wp-admin.css', array(), '1.0.0' );

}
add_action( 'admin_enqueue_scripts', 'swift_blog_admin_scripts' );
/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/single-meta.php';

/**
 * Tgmpa plugin activation.
 */
require get_template_directory().'/assets/libraries/TGM-Plugin/class-tgm-plugin-activation.php';


/**
 * Implement the Custom wodgets feature.
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';


/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * banner slider.
 */
require get_template_directory() . '/inc/main-banner-slider.php';

/**
 * single slider.
 */
require get_template_directory() . '/inc/related-post.php';

/**
 * widget init.
 */
require get_template_directory() . '/inc/widget-init.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}
