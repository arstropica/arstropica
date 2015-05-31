<?php
    /**
    * ArsTropica  Responsive Framework Functions.php
    * 
    * PHP version 5
    * 
    * @category   Theme Functions 
    * @package    WordPress
    * @author     ArsTropica <info@arstropica.com> 
    * @copyright  2014 ArsTropica 
    * @license    http://opensource.org/licenses/gpl-license.php GNU Public License 
    * @version    1.0 
    * @link       http://pear.php.net/package/ArsTropica  Reponsive Framework
    * @subpackage ArsTropica  Responsive Framework
    * @see        References to other sections (if any)...
    */

    /*ini_set('display_errors', 1);
    error_reporting(E_ALL);*/ 


    /*
    * *************************
    * ArsTropica  Responsive Framework *
    * *************************
    */

    /*       Definitions       *
    * ************************ */

    /* Define paths to child theme directory */
    if (!defined('template_directory'))
        define('template_directory', dirname(__FILE__));

    if (!defined('template_url'))
        define('template_url', get_template_directory_uri());

    /**
    * Define Theme Namespace
    * @global string 
    */
    global $theme_namespace;
    $theme_namespace = "at-responsive-theme";

    /*       Theme Setup       *
    * ************************ */

    at_responsive_options_setup();

    /* Switch default core markup for search form, comment form, and comments to output valid HTML5. */
    add_theme_support('html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
    ));
    add_theme_support('post-thumbnails', array('post'));
    add_theme_support('admin-bar', array('callback' => 'at_responsive_admin_bar'));

    /*Load Theme Locale (if applicable)*/
    load_theme_textdomain( $theme_namespace, template_directory . '/lib/languages' );

    /*       Shortcodes        *
    * ************************ */

    /* [blogurl] */
    add_shortcode('blogurl', 'at_responsive_blogurl_shortcode');

    /* [blogname] */
    add_shortcode('blogname', 'at_responsive_blogname_shortcode');

    /* [blogdesc] */
    add_shortcode('blogdesc', 'at_responsive_blogdesc_shortcode');



    /*         Actions        *
    * *********************** */

    /* Load Child Theme Functions if standalone */
    locate_template('/lib/functions/child-functions.php', true, true);

    /* Load Framework Stylesheets */
    add_action('init', 'at_responsive_theme_styles_min');
    add_action('wp_print_styles', 'at_responsive_theme_styles');
    add_action('wp_print_styles', 'at_responsive_desktop_lg_style');

    /* Load IE Stylesheet (last) */
    add_action('wp_print_styles', 'at_responsive_theme_ie_only_style', 100);

    /* Load Framework Scripts */
    add_action('init', 'at_responsive_theme_scripts_min');
    add_action('wp_enqueue_scripts', 'at_responsive_theme_scripts');

    /* Load Framework Minified Assets */
    add_action('wp', 'at_responsive_print_min_assets');

    /* Print Browser Update Init Script */
    add_action('wp_footer', 'at_responsive_browser_update_js');

    /* Initialize Theme Building Functions */
    add_action('init', 'at_responsive_theme_setup', 0);

    add_action('widgets_init', 'at_responsive_register_sidebars');

    add_action('pre_get_posts', 'at_responsive_posts_limit', 0);

    /* Add Publisher link to Head Tab */
    add_action('wp_head', 'at_responsive_google_authorship_publisher_link');

    /* Format Pagination */
    add_action('previous_posts_link_attributes', 'at_responsive_format_pagination_prev');
    add_action('next_posts_link_attributes', 'at_responsive_format_pagination_next');

    /* Sidebar Widget Areas */
    add_action('at_responsive_loop_start', 'at_responsive_do_before_posts_widget_area');
    add_action('at_responsive_loop_end', 'at_responsive_do_after_posts_widget_area');
    add_action('at_responsive_mobile_contact', 'at_responsive_do_mobile_header_widget_area');

    /* Author Box */
    add_action('at_responsive_after_entry', 'at_responsive_do_author_box_single');
    add_action('loop_start', 'at_responsive_do_author_box_author_page', 10000);

    /*Insert Facebook Open Graph in Head Tag*/
    add_action('wp_head', 'at_responsive_insert_fb_in_head', 5);

    /*Add Author Meta Tag to Head*/
    add_action('wp_head', 'at_responsive_single_author_meta');


    /*         Filters         *
    * ************************ */

    add_filter('wp_title', 'at_responsive_wp_title', 10, 2);

    /* Format Excerpt */
    add_filter('at_responsive_excerpt', 'at_responsive_modify_post_excerpt');
    add_filter('excerpt_length', 'at_responsive_excerpt_length');

    /* Format Title */
    add_filter('the_title', 'at_responsive_trim_title');

    /* Default Thumbnail Image */
    add_filter('post_thumbnail_html', 'at_responsive_default_ft_image', 10, 5);

    /* Add Sticky Post Class */
    add_filter('post_class', 'at_responsive_post_class');

    /* Add title to nav menu items */
    add_filter('nav_menu_link_attributes', 'at_responsive_nav_link_atts', 10, 3);

    add_filter('wp_nav_menu_items', 'at_responsive_add_login_logout_link', 10, 2);

    /* Add Device Code to Body */
    add_filter('body_class', 'at_responsive_browser_body_class');

    /* Add Navbar Class to Body */
    add_filter('body_class', 'at_responsive_navbar_body_class');

    /* Add Default Classes to Body */
    add_filter('body_class', 'at_responsive_body_classes');

    /* Widget Wrap Fallback */
    add_filter('dynamic_sidebar_params', 'at_responsive_check_sidebar_params');

    /* Category Widget Filter */
    add_filter('widget_categories_args', 'at_responsive_categories_widget_args');

    /* Archive Widget Filter */
    add_filter('widget_archives_args', 'at_responsive_archives_widget_args');

    /* Hide Dashboard CTA on Mobile */
    add_filter('dynamic_sidebar_params', 'at_responsive_dashboard_cta_class');

    add_filter('user_contactmethods', 'at_responsive_add_user_fields', 10, 1);

    /* Optimize Date Time Display for Microformat */
    add_filter('the_time', 'at_responsive_published_timeago');
    add_filter('get_the_time', 'at_responsive_published_timeago');
    add_filter('the_modified_time', 'at_responsive_updated_timeago');
    add_filter('get_the_modified_time', 'at_responsive_updated_timeago');

    /* Google Authorship Rel Tag */
    add_filter('at_google_authorship_rel', 'at_responsive_google_authorship_rel_text', 10, 1);
    add_filter('the_author_posts_link', 'at_responsive_google_authorship_author_posts_link', 10, 1);

    add_filter('language_attributes', 'at_responsive_add_opengraph_doctype');

    add_filter('soliloquy_defaults', 'at_responsive_soliloquy_set_defaults', 100, 2);

    /* Close Comments on Pages */
    add_filter('comments_open', 'at_responsive_close_page_comments', 10, 2);

    /* Add responsive class to inline post images */
    add_filter('the_content', 'at_responsive_filter_singular_images');

    /* Resize & Crop Post Thumbnails */
    add_filter('wp_get_attachment_image_attributes', 'at_responsive_resize_post_thumbnail', 10, 2);

    /*Add Default Favicon to Theme Settings*/
    add_filter('at_responsive_theme_mod_at_responsive[images][favicon]', 'at_responsive_theme_mod_default_favicon', 10, 1);

    /*Add Unique Identifier to Post Variable*/
    add_filter('posts_clauses', 'at_responsive_post_query_clauses', 20, 1);


    /*       Functions         *
    * ************************ */

    /**
    * Admin Bar Bump
    * 
    * @since 1.0
    * @return void 
    */
    function at_responsive_admin_bar() {
    ?>
    <style type="text/css" media="screen">
        body { padding-top: 32px !important; }
        * html body { margin-top: 32px !important; }
        @media screen and ( max-width: 782px ) {
            body { padding-top: 46px !important; }
            * html body { margin-top: 46px !important; }
        }
        html #wpadminbar { z-index: 1050 !important; }
    </style>
    <?php
    }

    /**
    * Enqueue Framework Stylesheets
    * 
    * @since 1.0
    * @return void 
    */
    function at_responsive_theme_styles() {
        if (is_admin()) return;
        global $wp_styles;
        // Load our main stylesheet.
        wp_enqueue_style('at-responsive-framework-style', template_url . '/style.css');
    }

    /**
    * Enqueue IE Only Stylesheet
    * 
    * Enqueue IE Stylesheet (last)
    * 
    * @since 1.0
    * @return void 
    */
    function at_responsive_theme_ie_only_style() {
        if (is_admin()) return;
        global $wp_styles;
        // Load our IE conditional stylesheet.
        wp_enqueue_style('at-responsive-framework-ie-style', template_url . '/lib/assets/css/ie.css');
        $wp_styles->add_data('at-responsive-framework-ie-style', 'conditional', 'IE');
    }

    /**
    * Enqueue Minified Framework Stylesheets
    * 
    * Avoid Stylesheets that use @import.
    * 
    * @since 1.0
    * @return void 
    */
    function at_responsive_theme_styles_min() {
        if (is_admin()) return;
        global $wp_styles;
        // Load our responsive stylesheet(s).
        at_responsive_enqueue_min_asset('css', 'bootstrap-style', template_url . '/lib/assets/js/bootstrap/css/bootstrap.min.css', array(), '3.1');
        at_responsive_enqueue_min_asset('css', 'yamm-style', template_url . '/lib/assets/css/yamm.css', array('bootstrap-style'));
        at_responsive_enqueue_min_asset('css', 'at-common-style', template_url . '/lib/assets/css/common.css', array('bootstrap-style', 'yamm-style'));

        // Load Font Stylesheets.
        at_responsive_enqueue_min_asset('css', 'fontello-style', template_url . '/lib/assets/fonts/fontello/css/fontello.css', array('bootstrap-style', 'at-common-style'));
        at_responsive_enqueue_min_asset('css', 'Quattrocento-Font', template_url . '/lib/assets/fonts/Quattrocento-Sans.css');
        at_responsive_enqueue_min_asset('css', 'Open-Sans-Font', template_url . '/lib/assets/fonts/Open-Sans.css');
        at_responsive_enqueue_min_asset('css', 'Cardo-Font', template_url . '/lib/assets/fonts/Cardo.css');
        at_responsive_enqueue_min_asset('css', 'Oswald-font', template_url . '/lib/assets/fonts/Oswald.css');
    }

    /**
    * Add Media Query for Large Desktop
    * 
    * @since 1.0
    * @return void 
    */
    function at_responsive_desktop_lg_style() {
    ?>
    <style type="text/css">
        @media all and (min-width: 1510px)
        {
            html .container
            {
                width: 1480px;
            }
        }
    </style>
    <?php
    }

    /**
    * Enqueue Framework Scripts
    * 
    * @since 1.0
    * @return void 
    */
    function at_responsive_theme_scripts() {
        if (is_admin()) return;
        wp_enqueue_script('jquery');
        wp_enqueue_script('at-responsive-script', template_url . '/lib/assets/js/at-responsive.js', array('jquery'), '1.0', true);

        if (is_single() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }

    /**
    * Enqueue Minified Framework Scripts
    * 
    * @since 1.0
    * @return void 
    */
    function at_responsive_theme_scripts_min() {
        if (is_admin()) return;
        at_responsive_enqueue_min_asset('js', 'modernizr-script', template_url . '/lib/assets/js/modernizr-2.8.3.min.js', array('jquery'));
        at_responsive_enqueue_min_asset('js', 'detectizr-script', template_url . '/lib/assets/js/detectizr.min.js', array('jquery'));
        at_responsive_enqueue_min_asset('js', 'bootstrap-script', template_url . '/lib/assets/js/bootstrap/js/bootstrap.min.js', array('jquery'));
        at_responsive_enqueue_min_asset('js', 'eqheight-script', template_url . '/lib/assets/js/jquery.eqheight.js', array('jquery'));
        at_responsive_enqueue_min_asset('js', 'suggest-script', template_url . '/lib/assets/js/jquery.suggest.js', array('jquery'));
        at_responsive_enqueue_min_asset('js', 'syze-script', template_url . '/lib/assets/js/syze.min.js', array('jquery'));
    }

    /**
    * Enqueue Minified Framework Assets
    * 
    * Add in order!
    * 
    * @param string         $type      Type of Asset (js/css).
    * @param string         $handle    Name of the script.
    * @param string         $src       Path to the script from the root directory of WordPress. Example: '/js/myscript.js'.
    * @param array          $deps      An array of registered handles this script depends on. Default empty array.
    * @param string|bool    $ver       Optional. String specifying the script version number, if it has one. This parameter
    *                               is used to ensure that the correct version is sent to the client regardless of caching,
    *                               and so should be included if a version number is available and makes sense for the script.
    * @param bool           $in_footer Optional. Whether to enqueue the script before </head> or before </body>.
    *                               Default 'false'. Accepts 'false' or 'true'.
    * @since 1.0
    * @return void 
    */
    function at_responsive_enqueue_min_asset($type, $handle, $src, $deps = array(), $ver = false, $in_footer = false, $media = 'all') {
        $data = array(
            'handle' => $handle,
            'src' => $src,
            'deps' => $deps,
            'ver' => $ver,
            'in_footer' => $in_footer,
            'media' => $media,
        );
        $at_responsive_minified = wp_cache_get('at_responsive_minified', $type);
        if (!$at_responsive_minified)
            $at_responsive_minified = array();

        $at_responsive_minified[$handle] = $data;

        wp_cache_set('at_responsive_minified', $at_responsive_minified, $type);
    }

    /**
    * Print Minified Framework Assets
    * 
    * @since 1.0
    * @return void 
    */
    function at_responsive_print_min_assets() {
        global $at_theme_custom;
        foreach (array('js', 'css') as $type) {
            $at_responsive_minified = wp_cache_get('at_responsive_minified', $type);
            if (!$at_responsive_minified)
                continue;

            $enable_minify = $at_theme_custom->get_option('seo/minifyassets', false);

            if ($enable_minify) {
                $at_responsive_minified_local = array_filter($at_responsive_minified, function($data) {
                    return (stristr($data['src'], $_SERVER['HTTP_HOST']) || (stristr($data['src'], "://") === false));
                });
                $at_responsive_minified_remote = array_diff_key($at_responsive_minified, $at_responsive_minified_local);
                $local_sources = $at_responsive_minified_local ? array_map(function($data) {
                    return @esc_url($data['src']);
                    }, $at_responsive_minified_local) : array();
                $relative_sources = $local_sources ? array_map('wp_make_link_relative', $local_sources) : array();
                if ( ! defined( 'SUBDOMAIN_INSTALL' ) || ( defined( 'SUBDOMAIN_INSTALL' ) && SUBDOMAIN_INSTALL !== true) ) {
                    $relative_sources = array_map(function($source){return preg_replace('/^\/[^\/]*/', '', $source);}, $relative_sources);
                }

                $single_sources = array();
                $combined_sources = array();
                if ($relative_sources) {
                    $f = array();
                    $commonPath = at_responsive_commonPath($relative_sources);
                    if ($commonPath) {
                        $single_sources = array_filter($relative_sources, function($src) use ($commonPath) {
                            return (strpos($src, $commonPath) !== 0);
                        });
                        $combined_sources = array_map(function($src) use ($commonPath) {
                            return str_replace($commonPath, '', $src);
                            }, array_filter($relative_sources, function($src) use ($commonPath) {
                                return (strpos($src, $commonPath) === 0);
                        }));
                        $commonPath = ltrim($commonPath, '/');
                        if (!$combined_sources) {
                            $single_sources = $relative_sources;
                        }
                    } else {
                        $single_sources = $relative_sources;
                        $combined_sources = array();
                    }
                    $minify_url = template_url . '/lib/tools/minify/min/?';
                    if ($commonPath && $combined_sources && !$single_sources) {
                        $minify_url .= "b={$commonPath}&f=" . implode(',', array_map('urlencode', $combined_sources));
                    } else {
                        $minify_url .= "f=" . ($combined_sources ? implode(',', array_map('urlencode', $combined_sources)) . "," : "") . ($single_sources ? implode(',', array_map('urlencode', $single_sources)) : "");
                    }

                    switch ($type) {
                        case 'js' : {
                            wp_enqueue_script("at-reponsive-framework-minified-{$type}-assets", $minify_url, array('jquery'));
                            break;
                        }
                        case 'css' : {
                            wp_enqueue_style("at-reponsive-framework-minified-{$type}-assets", $minify_url);
                            break;
                        }
                    }
                }
            } else {
                $at_responsive_minified_remote = $at_responsive_minified;
            }

            if ($at_responsive_minified_remote) {
                foreach ($at_responsive_minified_remote as $handle => $data) {
                    switch ($type) {
                        case 'js' : {
                            wp_enqueue_script($handle, $data['src'], $data['deps'], $data['ver'], $data['in_footer']);
                            break;
                        }
                        case 'css' : {
                            wp_enqueue_style($handle, $data['src'], $data['deps'], $data['ver'], $data['media']);
                            break;
                        }
                    }
                }
            }
        }
    }

    /**
    * Find common path in array of paths.
    * 
    * This works with dirs and files in any number of combinations
    * 
    * @param array         $dirList    Array of paths.
    * 
    * @since 1.0
    * @return string Return 
    */
    function at_responsive_commonPath($dirList) {
        $dirList = array_values($dirList);
        $arr = array();
        foreach ($dirList as $i => $path) {
            $dirList[$i] = explode('/', $path);
            unset($dirList[$i][0]);

            $arr[$i] = count($dirList[$i]);
        }

        $min = min($arr);

        for ($i = 0; $i < count($dirList); $i++) {
            while (count($dirList[$i]) > $min) {
                array_pop($dirList[$i]);
            }

            $dirList[$i] = '/' . implode('/', $dirList[$i]);
        }

        $dirList = array_unique($dirList);
        while (count($dirList) !== 1) {
            $dirList = array_map('dirname', $dirList);
            $dirList = array_unique($dirList);
        }
        reset($dirList);

        return current($dirList);
    }

    /**
    * Browser Update Nag (local)
    * 
    * This script loads a local copy of the JS file at
    * http://browser-update.org/update.js.  It is limited
    * to the browser version(s) and functionality that was 
    * in effect at the time the local copy was downloaded.  
    * Future versions and/or changes to the code may 
    * prevent it from working correctly.  The remote file
    * may used by uncommeting the third line.
    * 
    * @since 1.0
    * @return void 
    */
    function at_responsive_browser_update_js() {
        $update_js_src = template_url . '/lib/assets/js/browser-update.js';
        // Enable if local browser update is failing.
        // $update_js_src = '//browser-update.org/update.js';
    ?>
    <script type="text/javascript">
        // Browser Update
        var $buoop = {
            vs: {i: 9, f: 15, o: 12.1, s: 5.1}, // Versions
            reminder: 24, // atfer how many hours should the message reappear
            test: false, // true = always show the bar (for testing)
        }
        $buoop.ol = window.onload;
        window.onload = function() {
            try {
                if ($buoop.ol)
                $buoop.ol();
            } catch (e) {
            }
            var e = document.createElement("script");
            e.setAttribute("type", "text/javascript");
            e.setAttribute("src", "<?php echo $update_js_src; ?>");
            document.body.appendChild(e);
        }
    </script>
    <?php
    }

    /**
    * Setup Theme Building
    * 
    * @since 1.0
    * @return void 
    */
    function at_responsive_theme_setup() {
        global $at_theme_custom;

        /* Include Dependencies */
        at_responsive_includes();

        // Setup Theme Menu(s)
        at_responsive_menu_init();

        // Favicon
        add_action('wp_head', array($at_theme_custom, 'get_favicon'));
    }

    /**
    * Include Dependent Files
    * 
    * @since 1.0
    * @return void 
    */
    function at_responsive_includes() {
        require_once(template_directory . '/lib/menu.php');
        require_once(template_directory . '/lib/options.php');
        require_once(template_directory . '/lib/theme_mod.php');
        require_once(template_directory . '/lib/classes/plaintext_cat_walker.php');
        require_once(template_directory . '/lib/classes/comments.php');
        require_once(template_directory . '/lib/classes/aq_resizer.php');
        require_once(template_directory . '/lib/functions/comments.php');
        require_once(template_directory . '/lib/plugins/at_social_icons_widget.php');
        require_once(template_directory . '/lib/plugins/at_mobile_contact_widget.php');
        require_once(template_directory . '/lib/plugins/at_logo_widget.php');
        require_once(template_directory . '/lib/plugins/at_related_posts_plugin.php');
        require_once(template_directory . '/lib/plugins/at_featured_posts_plugin.php');
    }

    /* Register Framework Sidebars */

    /**
    * Register Framework Sidebars
    * 
    * @since 1.0
    * @return void 
    */
    function at_responsive_register_sidebars() {
        $grid_values = at_responsive_get_content_grid_values();
        $grid_classes = at_responsive_get_content_grid_classes();
        register_sidebar(array(
            'name' => 'Mobile Form',
            'id' => 'mobile_form',
            'before_widget' => '<li>',
            'after_widget' => '</li>',
            'before_title' => '',
            'after_title' => '',
        ));
        register_sidebar(array(
            'name' => 'Before Posts',
            'id' => 'before_posts',
            'before_widget' => '<aside id="%1$s" class="col-md-' . $grid_values['loop_start'] . ' ' . $grid_classes['loop_start'] . ' widget at_widget %2$s" role="complementary"><div class="widget-frame">',
            'after_widget' => "</div></div></div></aside>",
            'before_title' => '<h4 class="widgettitle">',
            'after_title' => '</h4><div class="widget-wrap"><div class="widget-content">',
        ));
        register_sidebar(array(
            'name' => 'After Posts',
            'id' => 'after_posts',
            'before_widget' => '<aside id="%1$s" class="col-md-' . $grid_values['loop_end'] . ' ' . $grid_classes['loop_end'] . ' widget at_widget %2$s" role="complementary"><div class="widget-frame">',
            'after_widget' => "</div></div></div></aside>",
            'before_title' => '<h4 class="widgettitle">',
            'after_title' => '</h4><div class="widget-wrap"><div class="widget-content">',
        ));
    }

    /**
    * Google Publisher Link
    * 
    * @since 1.0
    * @return void 
    */
    function at_responsive_google_authorship_publisher_link() {
        global $at_theme_custom;
        $google_business_page = at_responsive_get_theme_option('seo/googlepublisher');
        if ($google_business_page)
            echo '<link href="' . $google_business_page . '" rel="publisher" />' . "\n";
    }

    /**
    * Return id for body tag based on content type
    * 
    * @since 1.0
    * @return string Return 
    */
    function at_responsive_body_id() {
        $text = ' id = "%s"';
        $content_type = at_responsive_wp_content_type();
        $id = sprintf($text, $content_type . '-page');
        return $id;
    }

    /**
    * Register Theme Menu(s)
    * 
    * @since 1.0
    * @return void 
    */
    function at_responsive_menu_init() {
        register_nav_menu('header-menu', __('ArsTropica  Responsive Header Menu'));
        register_nav_menu('footer-menu', __('ArsTropica  Responsive Footer Menu'));
    }

    /* Init Theme Options */

    /**
    * Init Theme Options
    * 
    * @since 1.0
    * @return object Return 
    */
    function at_responsive_options_setup() {
        global $at_theme_options, $at_theme_custom;

        require_once(template_directory . '/lib/options.php');
        require_once(template_directory . '/lib/theme_mod.php');

        if (current_user_can('edit_theme_options')) {
            if (! $at_theme_options instanceof at_responsive_theme_options)
                $at_theme_options = new at_responsive_theme_options();
        }
        $at_theme_custom = new at_responsive_theme_mod();

        return $at_theme_custom;
    }

    /**
    * Display Custom Menu
    * 
    * @param string  $location  Parameter 
    * @param string  $id        Parameter 
    * @param string  $class     Parameter 
    * @param boolean $walker    Parameter 
    * @param integer $depth     Parameter 
    * @param string  $container Parameter 
    * @param boolean $touch     Parameter 
    * @since 1.0
    * @return void    
    */
    function at_responsive_menu($location, $id = '', $class = '', $walker = true, $depth = 2, $container = '', $touch = true) {
        $args = array(
            'theme_location' => $location,
            'menu_id' => $id,
            'menu_class' => $class . ($touch ? " fancy" : ""),
            'container' => $container,
            'depth' => $depth,
            'touch' => $touch,
        );
        if ($walker)
            $args['walker'] = new at_responsive_menu_walker();

        wp_nav_menu($args);
    }

    /**
    * Alter Menu Link Attributes
    * 
    * @param array   $atts Parameter 
    * @param object  $item Parameter 
    * @param array $args Parameter 
    * @since 1.0
    * @return array   Return 
    */
    function at_responsive_nav_link_atts($atts, $item, $args) {
        if (empty($atts['title']))
            $atts['title'] = $item->title;
        return $atts;
    }

    /* Add Login to Footer Menu */

    /**
    * Add Login to Footer Menu
    * 
    * @param string $items Parameter 
    * @param object $args  Parameter 
    * @since 1.0
    * @return string Return 
    */
    function at_responsive_add_login_logout_link($items, $args) {
        global $theme_namespace;
        $login = __('Log in');
        $logout = __('Log out');
        $redirect = current_page_url();
        if (!is_user_logged_in())
            $link = '<a href="' . esc_url(wp_login_url($redirect)) . '">' . $login . '</a>';
        else
            $link = '<a href="' . esc_url(wp_logout_url($redirect)) . '">' . $logout . '</a>';

        if ($args->theme_location == 'footer-menu') {
            $items = '<li>Copyright &copy; ' . date('Y') . '</li>' . $items;
            $items = '<li>' . __('Powered by ', $theme_namespace) . '<a class="arstropica-link" href="http://arstropica.com/" target="_blank" title="ArsTropica site link">ArsTropica</a></li>' . $items;
            $items .= '<li class="no-border-login">' . $link . '</li>';
        }
        return $items;
    }

    /**
    * Wrap Widget Content
    * 
    * @param array $params Parameter 
    * @since 1.0
    * @return array Return 
    */
    function at_responsive_check_sidebar_params($params) {
        global $wp_registered_widgets;

        $settings_getter = $wp_registered_widgets[$params[0]['widget_id']]['callback'][0];
        $settings = $settings_getter->get_settings();
        $settings = $settings[$params[1]['number']];

        if (isset($settings['title']) && empty($settings['title']))
            $params[0]['before_widget'] .= '<div class="widget-wrap"><div class="widget-content">';

        return $params;
    }

    /**
    * Modify Categories Widget
    * 
    * @param array $cat_args Parameter 
    * @since 1.0
    * @return array Return 
    */
    function at_responsive_categories_widget_args($cat_args) {
        $cat_args['orderby'] = 'count';
        $cat_args['order'] = 'DESC';
        $cat_args['exclude'] = isset($cat_args['exclude']) ? implode(',', array($cat_args['exclude'], 1)) : '1';
        $cat_args['number'] = 24;
        return $cat_args;
    }

    /**
    * Modify Archives Widget
    * 
    * @param array $arch_args Parameter 
    * @since 1.0
    * @return array Return 
    */
    function at_responsive_archives_widget_args($arch_args) {
        $arch_args['limit'] = 24;
        return $arch_args;
    }

    /**
    * Handle Before Posts Widget Area
    * 
    * @since 1.0
    * @return void 
    */
    function at_responsive_do_before_posts_widget_area() {
        $grid_values = at_responsive_get_content_grid_values();
        $loop_start = isset($grid_values['loop_start']) ? $grid_values['loop_start'] : 12;
        $columns = "col-md-{$loop_start}";

        $grid_classes = at_responsive_get_content_grid_classes();
        $class = isset($grid_classes['loop_start']) ? $grid_classes['loop_start'] : '';

        at_responsive_do_sidebar('Before Posts', $columns, array($class));
    }

    /**
    * Handle After Posts Widget Area
    * 
    * @since 1.0
    * @return void 
    */
    function at_responsive_do_after_posts_widget_area() {
        $grid_values = at_responsive_get_content_grid_values();
        $loop_end = isset($grid_values['loop_end']) ? $grid_values['loop_end'] : 12;
        $columns = "col-md-{$loop_end}";

        $grid_classes = at_responsive_get_content_grid_classes();
        $class = isset($grid_classes['loop_end']) ? $grid_classes['loop_end'] : '';

        at_responsive_do_sidebar('After Posts', $columns, array($class));
    }

    /**
    * Handle Mobile Header Widget Area
    * 
    * @since 1.0
    * @return void 
    */
    function at_responsive_do_mobile_header_widget_area() {
        at_responsive_do_m_sidebar('Mobile Form');
    }

    /**
    * Output Sidebar
    * 
    * @param string $sidebar_id Parameter 
    * @param string  $columns    Parameter 
    * @param array   $classes    Parameter 
    * @since 1.0
    * @return void    
    */
    function at_responsive_do_sidebar($sidebar_id, $columns = 'col-md-3', $classes = array()) {
        global $wp_query, $theme_namespace;
        $old_wp_query = $wp_query;
        $display_placeholders = at_responsive_get_theme_option('settings/widgetplaceholders', true);
        $c = implode(' ', $classes);
        if (!dynamic_sidebar($sidebar_id)) {
            if ($display_placeholders) {
                echo '<aside class="' . $columns . ' ' . $c . ' widget_text at_widget widget ' . at_responsive_slugify($sidebar_id) . '-widget' .  ($display_placeholders ? " placeholder" : "") . '" role="complementary"><div class="widget-frame no-margin"><div class="widget-wrap">';
                echo '<h4 class="widgettitle">';
                _e(ucwords($sidebar_id) . ' Widget Area', $theme_namespace);
                echo '</h4>';
                echo '<div class="textwidget"><p>';
                printf(__('This is the ' . ucwords($sidebar_id) . ' Widget Area. You can add content to this area by visiting your <a href="%s">Widgets Panel</a> and adding new widgets to this area.', $theme_namespace), admin_url('widgets.php'));
                echo '</p></div>';
                echo '</div></div></aside>';
            }
        }
        wp_reset_query();
        $wp_query = $old_wp_query;
    }

    /**
    * Output Mobile Sidebar
    * 
    * @param string $sidebar_id Parameter 
    * @since 1.0
    * @return void    
    */
    function at_responsive_do_m_sidebar($sidebar_id) {
        global $wp_query;
        $old_wp_query = $wp_query;
        $display_placeholders = at_responsive_get_theme_option('settings/widgetplaceholders', true);
        if (!dynamic_sidebar($sidebar_id)) {
            if ($display_placeholders) {
                echo '<li><a class="btn btn-oval contact-button switch" href="mailto:' . get_bloginfo('admin_email') . '">Contact Us</a></li>';
            }
        }
        wp_reset_query();
        $wp_query = $old_wp_query;
    }

    /* ShortCode Handlers */
    // [blogname]

    /**
    * Handle [blogname] shortcode
    * 
    * @param array $atts Parameter 
    * @since 1.0
    * @return string  Return 
    */
    function at_responsive_blogname_shortcode($atts) {
        return get_bloginfo('name');
    }

    add_shortcode('blogname', 'at_responsive_blogname_shortcode');

    // [blogurl]

    /**
    * Handle [blogurl] shortcode
    * 
    * @param array $atts Parameter 
    * @since 1.0
    * @return string  Return 
    */
    function at_responsive_blogurl_shortcode($atts) {
        return home_url('/');
    }

    // [blogdesc]

    /**
    * Handle [blogdesc] shortcode
    * 
    * @param array $atts Parameter 
    * @since 1.0
    * @return string  Return 
    */
    function at_responsive_blogdesc_shortcode($atts) {
        return get_bloginfo('description');
    }

    /**
    * Get Theme Option(s)
    * 
    * @param boolean $option_path Parameter 
    * @param boolean $default     Parameter 
    * @since 1.0
    * @return mixed   Return 
    */
    function at_responsive_get_theme_option($option_path = false, $default = false) {
        global $at_theme_custom;
        return $at_theme_custom->get_option($option_path, $default);
    }

    /**
    * Post Entry
    * 
    * @since 1.0
    * @return unknown Return 
    */
    function at_responsive_post_entry() {
        global $post, $theme_namespace;
        $output = "";
        if (is_sticky() && is_home() && !is_paged()) {
            $output .= '<span class="featured-post">' . __('Sticky', $theme_namespace) . '</span>';
        }

        // Set up and print post meta information.
        if (is_singular()) :
            if (!is_single()) {
                return;
            }
            // Single Post
            $edit_post = get_edit_post_link() ? (' <span class="edit-link">(' . '<a class="post-edit-link" href="' . get_edit_post_link() . '">' . __('Edit') . '</a>)' . '</span>') : '';

            $output .= sprintf('<p><span class="author">by <span class="author vcard"><span class="fn"><a class="url fn n" href="%4$s" rel="author">%5$s</a></span></span>%6$s</span> on <span class="date"><span class="entry-date"><a href="%1$s" rel="bookmark"><time class="entry-date" datetime="%2$s">%3$s</time></a></span><time class="entry-date updated hidden" datetime="%2$s">' . get_the_modified_time(get_option('date_format')) . '</time></span></p>', esc_url(get_permalink()), esc_attr(get_the_date('c')), esc_html(get_the_date()), esc_url(get_author_posts_url(get_the_author_meta('ID'))), get_the_author(), $edit_post
            );
            else :
            // Archives
            $output .= sprintf('<span>by <span class="author vcard"><span class="fn"><a class="url fn n" href="%4$s" rel="author">%5$s</a></span></span></span> | <span class="entry-date"><span class="date published time" title="%3$s"><a href="%1$s" rel="bookmark"><time class="entry-date" datetime="%2$s">%3$s</time></a></span><time class="entry-date updated hidden" datetime="%2$s">' . get_the_modified_time(get_option('date_format')) . '</time></span>', esc_url(get_permalink()), esc_attr(get_the_date('c')), esc_html(get_the_date()), esc_url(get_author_posts_url(get_the_author_meta('ID'))), get_the_author()
            );
            if ($post && get_comments_number($post->ID) && (!post_password_required())) {
                $output .= ' | <span class="comments-link">';
                $output .= at_responsive_comments_popup_link(__('', $theme_namespace), __('1 Comment', $theme_namespace), __('% Comments', $theme_namespace), '', '', true);
                $output .= '</span>';
            }
            endif;

        echo apply_filters('at_responsive_post_entry', $output);
    }

    /**
    * Get Post Entry
    * 
    * @param object $_post Parameter 
    * @since 1.0
    * @return string Return 
    */
    function at_responsive_get_post_entry($_post = null) {
        global $post, $theme_namespace;
        if (!$_post)
            $_post = $post;
        $output = "";
        if (is_sticky() && is_home() && !is_paged()) {
            $output .= '<span class="featured-post">' . __('Sticky', $theme_namespace) . '</span>';
        }

        // Set up and print post meta information.
        $output .= sprintf('<span>by <span class="author vcard"><span class="fn"><a class="url fn n" href="%4$s" rel="author">%5$s</a></span></span></span> | <span class="entry-date"><span class="date published time" title="%3$s"><a href="%1$s" rel="bookmark"><time class="entry-date" datetime="%2$s">%3$s</time></a></span><time class="entry-date updated hidden" datetime="%2$s">' . get_the_modified_time(get_option('date_format')) . '</time></span>', esc_url(get_permalink($_post->ID)), esc_attr(get_the_date('c', $_post)), esc_html(get_the_date('', $_post)), esc_url(get_author_posts_url(get_the_author_meta('ID', $_post->post_author))), get_the_author_meta('user_nicename', $_post->post_author)
        );
        if ($_post && get_comments_number($_post->ID) && (!post_password_required($_post))) {
            $output .= ' | <span class="comments-link">';
            $output .= at_responsive_comments_popup_link(__('', $theme_namespace), __('1 Comment', $theme_namespace), __('% Comments', $theme_namespace), '', '', true);
            $output .= '</span>';
        }
        return apply_filters('at_responsive_post_entry', $output);
    }

    /**
    * Comments Popup Link
    * 
    * @param boolean $zero      Parameter 
    * @param boolean $one       Parameter 
    * @param boolean $more      Parameter 
    * @param string  $css_class Parameter 
    * @param mixed   $none      Parameter 
    * @param boolean $return    Parameter 
    * @param object  $_post     Parameter 
    * @since 1.0
    * @return string  Return 
    */
    function at_responsive_comments_popup_link($zero = false, $one = false, $more = false, $css_class = '', $none = false, $return = false, $_post = null) {
        global $wpcommentspopupfile, $wpcommentsjavascript, $post;

        if (!$_post)
            $_post = $post;
        $output = "";
        $id = $_post->ID;

        if (false === $zero)
            $zero = __('No Comments');
        if (false === $one)
            $one = __('1 Comment');
        if (false === $more)
            $more = __('% Comments');
        if (false === $none)
            $none = __('Comments Off');

        $number = get_comments_number($id);

        if (0 == $number && !comments_open() && !pings_open()) {
            $output .= '<span' . ((!empty($css_class)) ? ' class="' . esc_attr($css_class) . '"' : '') . '>' . $none . '</span>';
            if ($return) {
                return $output;
            } else {
                echo $output;
                return;
            }
        }

        if (post_password_required()) {
            // $output .= __('Enter your password to view comments.');
            if ($return) {
                return $output;
            } else {
                echo $output;
                return;
            }
        }

        $output .= '<a href="';
        if ($wpcommentsjavascript) {
            if (empty($wpcommentspopupfile))
                $home = home_url();
            else
                $home = get_option('siteurl');
            $output .= $home . '/' . $wpcommentspopupfile . '?comments_popup=' . $id;
            $output .= '" onclick="wpopen(this.href); return false"';
        } else { // if comments_popup_script() is not in the template, display simple comment link
            if (0 == $number)
                $output .= get_permalink() . '#respond';
            else
                $output .= esc_url(get_comments_link());
            $output .= '"';
        }

        if (!empty($css_class)) {
            $output .= ' class="' . $css_class . '" ';
        }
        $title = the_title_attribute(array('echo' => 0));

        $attributes = '';
        /**
        * Filter the comments popup link attributes for display.
        *
        * @since 2.5.0 
        *        
        * @param string $attributes The comments popup link attributes. Default empty.
        */
        $output .= apply_filters('comments_popup_link_attributes', $attributes);

        $output .= ' title="' . esc_attr(sprintf(__('Comment on %s'), $title)) . '">';
        $output .= at_responsive_ob_capture('comments_number', $zero, $one, $more);
        $output .= '</a>';

        if ($return)
            return apply_filters('at_responsive_comments_popup_link', $output);
        else
            echo apply_filters('at_responsive_comments_popup_link', $output);
    }

    function at_responsive_post_sharing() {
        global $post;
        if (!$post)
            $post = get_post();
        $uniqID = isset($post->uniqID) ? "{$post->ID}_{$post->uniqID}" : $post->ID;
    ?>
    <ul class="social-share-in-title">
        <li class="twitter-share-post"><a title="Tweet to Share" class="share-action twitter" href="https://twitter.com/intent/tweet?text=<?php echo rawurlencode(substr(get_the_title(), 0, 140)); ?>&url=<?php echo rawurlencode(get_the_permalink()); ?>" target="_blank"><i class="icon-twitter-open iconf"></i></a></li>
        <li class="fb-like-post">
            <a title="Like on Facebook" class="like-action facebook" href="#" style="position: relative;" data-source="#fb_like_iframe_<?php echo $uniqID; ?>" rel="popover">
                <i class="icon-thumbs-up-open iconf"></i>
            </a>
            <div id="fb_like_iframe_<?php echo $uniqID; ?>" class="hidden">
                <iframe src="//www.facebook.com/plugins/like.php?href=<?php echo urlencode(get_the_permalink()); ?>&amp;width=290&amp;layout=standard&amp;action=like&amp;show_faces=true&amp;share=false&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:290px; height:80px;" allowTransparency="true"></iframe>
            </div>
        </li>
        <li class="fb-share-post"><a title="Share on Facebook" class="share-action facebook" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fparse.com" target="_blank"><i class="icon-facebook-open iconf"></i></a></li>
        <li class="gplus-share-post"><a title="Plus to Share" class="share-action googleplus" href="https://plus.google.com/share?url=<?php echo rawurlencode(get_the_permalink()); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');
            return false;" target="_blank"><i class="icon-gplus-open iconf"></i></a></li>
        <li class="pin-share-post"><a title="Pin to Share" class="share-action pinterest" href="//www.pinterest.com/pin/create/?url=<?php echo rawurlencode(get_the_permalink()); ?>&media=<?php echo rawurlencode(wp_get_attachment_thumb_url($post->ID)); ?>" target="_blank"><i class="icon-pinterest-open iconf"></i></a></li>
        <li class="linkedin-share-post"><a title="Share on LinkedIn" class="share-action linkedin" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo rawurlencode(get_the_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>&summary=<?php echo urlencode(get_the_excerpt()); ?>" target="_blank"><i class="icon-linkedin-open iconf"></i></a></li>
    </ul>
    <?php
    }

    /**
    * AddThis Post Sharing Function
    * 
    * @param object $post Parameter 
    * @since 1.0
    * @return void   
    */
    function at_responsive_post_addthis($post = null) {
        if (!$post)
            $post = get_post();
        $uniqID = isset($post->uniqID) ? "{$post->ID}_{$post->uniqID}" : $post->ID;
        if (!(is_singular($post->post_type) && get_post_type($post) != 'post')) {
            $post_title = get_the_title($post->ID);
            $post_excerpt = at_responsive_get_excerpt($post->ID);
            $message = <<<EOT
            I thought you'd find this interesting:
            $post_title
            $post_excerpt
EOT;
        ?>
        <div class="panel-collapse collapse share-drawer" id="share-drawer-<?php echo $uniqID; ?>">
            <div class="panel-body">
                <!-- AddThis Button BEGIN -->
                <div class="addthis_toolbox" addthis:url="<?php echo get_permalink($post->ID); ?>" addthis:title="<?php get_the_title($post->ID); ?>" addthis:description="<?php echo preg_quote(at_responsive_get_excerpt($post->ID)); ?>">
                    <a class="addthis_button_facebook" title="Share to Facebook"><img src="<?php echo template_url . '/lib/assets/img/inficons/32/facebook.png'; ?>" width="24" height="24" alt="Share to Facebook" /></a>
                    <a class="addthis_button_twitter" title="Share to Twitter"><img src="<?php echo template_url . '/lib/assets/img/inficons/32/twitter.png'; ?>" width="24" height="24" alt="Share to Twitter" /></a>
                    <?php if (has_post_thumbnail($post->ID)) : ?><a href="http://pinterest.com/pin/create/%20button/?url=<?php the_permalink(); ?>&media=<?php wp_get_attachment_url(get_post_thumbnail_id($post->ID)); ?>&description=<?php echo get_the_title($post->ID); ?>" class="pinitbutton" title="Pin to Pinterest" target="_blank"><img src="<?php echo template_url . '/lib/assets/img/inficons/32/pinterest.png'; ?>" width="24" height="24" alt="Pin to Pinterest" /></a><?php endif; ?>
                    <a class="addthis_button_google_plusone_share" title="Share to Google +"><img src="<?php echo template_url . '/lib/assets/img/inficons/32/google+.png'; ?>" width="24" height="24" alt="Share to Google Plus" /></a>
                    <a class="addthis_button_linkedin" title="Share to LinkedIn"><img src="<?php echo template_url . '/lib/assets/img/inficons/32/linkedin.png'; ?>" width="24" height="24" alt="Share to LinkedIn" /></a>
                    <a href="http://www.addthis.com/bookmark.php" class="addthis_button_email" title="Share via Email"><img src="<?php echo template_url; ?>/lib/assets/img/inficons/32/mail.png" height="24" width="24" border="0" alt="Share via Email" /></a>
                    <a class="addthis_counter addthis_button_compact" title="More Options"><img src="<?php echo template_url . '/lib/assets/img/inficons/32/more.png'; ?>" width="24" height="24" alt="More Sharing Options" /></a>
                </div>
                <!-- AddThis Button END -->
            </div>
        </div>
        <?php
        }
    }

    /**
    * AddThis Site Sharing Function
    * 
    * @since 1.0
    * @return void 
    */
    function at_responsive_site_addthis() {
        $site_logo = at_responsive_get_theme_option('images/companylogo', false);
        $twitter = false;
        $twitter_url = at_responsive_get_theme_option('social/widget/Twitter', false);
        if ($twitter_url && (preg_match("/https?:\/\/(www\.)?twitter\.com\/(#!\/)?@?([^\/]*)/", $twitter_url, $parts))) {
            $twitter = $parts[3];
        }
    ?>
    <div class="dropdown social-pull-down">
        <div class="menu-social-container">
            <div class="social-button">
                <!-- AddThis Button BEGIN -->
                <div class="addthis_toolbox" addthis:url="<?php home_url('/'); ?>" addthis:title="<?php get_bloginfo('name'); ?>" addthis:description="<?php echo preg_quote(get_bloginfo('description')); ?>">
                    <a class="addthis_button_facebook navicon" title="Share to Facebook"><img src="<?php echo template_url . '/lib/assets/img/inficons/32/facebook.png'; ?>" width="32" height="32" alt="Share to Facebook" /></a>
                    <?php if ($twitter) : ?><a class="addthis_button_twitter navicon" addthis:userid="<?php echo $twitter; ?>" title="Share to Twitter"><img src="<?php echo template_url . '/lib/assets/img/inficons/32/twitter.png'; ?>" width="32" height="32" alt="Share to Twitter" /></a> <?php endif; ?>
                    <?php if ($site_logo) : ?><a href="http://pinterest.com/pin/create/%20button/?url=<?php echo home_url('/'); ?>&media=<?php echo $site_logo; ?>&description=<?php echo get_bloginfo('name'); ?>" class="pinitbutton navicon" title="Pin to Pinterest" target="_blank"><img src="<?php echo template_url . '/lib/assets/img/inficons/32/pinterest.png'; ?>" width="32" height="32" alt="Pin to Pinterest" /></a><?php endif; ?>
                    <a class="addthis_button_google_plusone_share navicon" title="Share to Google +"><img src="<?php echo template_url . '/lib/assets/img/inficons/32/google+.png'; ?>" width="32" height="32" alt="Share to Google Plus" /></a>
                    <a class="addthis_button_linkedin navicon" title="Share to LinkedIn"><img src="<?php echo template_url . '/lib/assets/img/inficons/32/linkedin.png'; ?>" width="32" height="32" alt="Share to LinkedIn" /></a>
                    <a href="http://www.addthis.com/bookmark.php" class="addthis_button_email navicon" title="Share via Email"><img src="<?php echo template_url; ?>/lib/assets/img/inficons/32/mail.png" height="32" width="32" border="0" alt="Share via Email" /></a>
                    <a class="addthis_counter addthis_button_compact navicon" title="More Options"><img src="<?php echo template_url . '/lib/assets/img/inficons/32/more.png'; ?>" width="32" height="32" alt="More Sharing Options" /></a>
                </div>
                <!-- AddThis Button END -->
            </div>
        </div>
    </div>
    <?php
    }

    /**
    * Post Thumbnail
    * 
    * @since 1.0
    * @return unknown Return 
    */
    function at_responsive_post_thumbnail() {
        global $post, $wp_query, $wp_the_query;
        if (is_attachment()) {
            return;
        }
        $attr = array('class' => 'img-responsive', 'alt' => trim(strip_tags(get_the_title())));
        if (is_singular()) {
            the_post_thumbnail('single-thumbnail', $attr);
        } else {
            echo '<a class="post-thumbnail thumbnail" href="' . get_the_permalink() . '">';
            the_post_thumbnail('entry-thumbnail', $attr);
            echo '</a>';
        }
    }

    /**
    * Resize Post Thumbnail
    * 
    * @param array   $attr       Parameter 
    * @param object $attachment Parameter 
    * @since 1.0
    * @return array   Return 
    */
    function at_responsive_resize_post_thumbnail($attr = array(), $attachment = null) {
        global $_wp_additional_image_sizes;
        if (is_array($attr)) {
            if (isset($attr['class'], $attr['src']) && stristr($attr['class'], 'img-responsive')) {
                $src = $attr['src'];
                $_size = 'entry-thumbnail';
                $_width = $_wp_additional_image_sizes[$_size]['width'];
                $_height = $_wp_additional_image_sizes[$_size]['height'];
                $_crop = $_wp_additional_image_sizes[$_size]['crop'];
                $attr['src'] = aq_resize($src, $_width, $_height, $_crop, true, true);
            }
        }
        return $attr;
    }

    /**
    * Post Title
    * 
    * @param mixed $title Parameter 
    * @since 1.0
    * @return void  
    */
    function at_responsive_post_title($title = false) {
        if (is_singular()) :
            $before = '<h1 class="entry-title">';
            $after = '</h1>';
            if ($title) :
                echo $before . $title . $after;
                else :
                the_title($before, $after);
                endif;
            else :
            $before = '<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">';
            $after = '</a></h2>';
            if ($title) :
                echo $before . $title . $after;
                else :
                the_title($before, $after);
                endif;
            endif;
    }

    /**
    * Generate Post Excerpt
    * 
    * @param boolean $echo Parameter 
    * @since 1.0
    * @return unknown Return 
    */
    function at_responsive_post_excerpt($echo = true) {
        global $theme_namespace;
        $excerpt = get_the_excerpt();
        $newexcerpt = apply_filters('at_responsive_excerpt', __($excerpt, $theme_namespace));
        if ($echo) {
            echo $newexcerpt;
        } else {
            return $newexcerpt;
        }
    }

    /**
    * Modify HTML tag structure around post excerpt
    * 
    * @param string $excerpt Parameter 
    * @since 1.0
    * @return unknown Return 
    */
    function at_responsive_modify_post_excerpt($excerpt) {
        global $theme_namespace;
        $nopee = at_responsive_strip_selected_tags($excerpt, array('p'));
        $newexcerpt = __('<p class="teaser-text">' . $nopee . '</p>', $theme_namespace);
        $newexcerpt .= at_responsive_excerpt_more();
        if ($newexcerpt)
            $excerpt = $newexcerpt;
        return $excerpt;
    }

    /**
    * Trim Post Excerpt
    * 
    * @param integer $length Parameter 
    * @since 1.0
    * @return unknown Return 
    */
    function at_responsive_excerpt_length($length = 50) {
        $layout = at_responsive_wp_template_type();
        // $fixed = false;
        switch ($layout) {
            case 'archive' :
            case 'date' :
            case 'search' : {
                $fixed = true;
                $length = 15;
                $excerptwords = at_responsive_get_theme_option('settings/excerptlength', $length);
                break;
            }
            case 'home' :
            case 'front_page' : {
                $length = 55;
                $excerptwords = at_responsive_get_theme_option('settings/homeexcerptlength', $length);
                break;
            }
            default: {
                $excerptwords = at_responsive_get_theme_option('settings/excerptlength', $length);
                break;
            }
        }
        return $excerptwords;
    }

    /**
    * Add "Read More" link to Excerpt
    * 
    * @param string $more Parameter 
    * @since 1.0
    * @return string Return 
    */
    function at_responsive_excerpt_more($more = '') {
        global $post;
        if (!$post)
            $post = get_post();
        $_share_id = "#share-drawer-" . (isset($post->uniqID) ? "{$post->ID}_{$post->uniqID}" : $post->ID);
        $more = '<p class="read-n-share"><a title="Read More" class="read-more" href="' . get_permalink($post->ID) . '">Read More</a><a class="share-post" title="Share" data-toggle="collapse" data-parent="#post-' . $post->ID . '" href="' . $_share_id . '"><span class="glyphicon glyphicon-share-alt"></span></a></p>';
        return $more;
    }

    /**
    * Trim Post Excerpt Title
    * 
    * @param string $title Parameter 
    * @since 1.0
    * @return string Return 
    */
    function at_responsive_trim_title($title) {
        $chars = 77;
        if ((is_singular() === false) && (strlen($title) > $chars)) {
            $title = substr($title, 0, $chars);
            $title = substr($title, 0, strrpos($title, ' '));
            $title .= " &hellip;";
        }
        return $title;
    }

    /* Limit Homepage Posts */

    /**
    * Limit Homepage Posts
    * 
    * @param object  &$query Parameter 
    * @since 1.0
    * @return unknown Return 
    */
    function at_responsive_posts_limit(&$query) {
        //Before anything else, make sure this is the right query...
        if ($query->is_feed || $query->is_singular || is_admin()) {
            return;
        }
        //Define default pagination
        $home_posts_per_page = (int) max(array(2, floor(get_option('posts_per_page') / at_responsive_grid_column_count())));
        //Get uniform post count for grid
        $posts_per_page = max(array(2, get_option('posts_per_page') - ( get_option('posts_per_page') % at_responsive_grid_column_count() )));
        // Check / Set Theme Option
        $theme_options = at_responsive_get_theme_option();
        ;
        if ($theme_options) {
            if (empty($theme_options['settings']['homepostsperpage']) === false) {
                $home_posts_per_page = $theme_options['settings']['homepostsperpage'];
            }
            if (empty($theme_options['settings']['postsperpage']) === false) {
                $posts_per_page = $theme_options['settings']['postsperpage'];
            }
        }
        //Next, detect and handle pagination...
        if (($query->is_posts_page) || is_front_page() || $query->is_home) {
            if ($query->is_paged) {
                //Manually determine page query offset (offset + current page (minus one) x posts per page)
                $page_offset = ( ($query->query_vars['paged'] - 1) * $home_posts_per_page );
                //Apply adjust page offset
                $query->set('offset', $page_offset);
                $query->set('posts_per_page', $posts_per_page);
            } else {
                //This is the first page. Just use the offset...
                $query->set('posts_per_page', $home_posts_per_page);
                $query->set('ignore_sticky_posts', 1);
            }
        } else {
            $query->set('posts_per_page', $posts_per_page);
        }
    }

    /**
    * Sticky Post Class
    * 
    * @param array $classes Parameter 
    * @since 1.0
    * @return array Return 
    */
    function at_responsive_post_class($classes) {
        global $post;
        if (!$post)
            $post = get_post();
        $layout_type = at_responsive_wp_template_type();
        if (is_sticky())
            $classes[] = 'sticky';
        $classes[] = "{$layout_type}-parent";
        if (isset($post->uniqID))
            $classes[] = "at-{$post->uniqID}";
        return $classes;
    }

    /**
    * Pagination
    * 
    * @param boolean $numeric Parameter 
    * @since 1.0
    * @return unknown Return 
    */
    function at_responsive_pagination($numeric = false) {
        global $theme_namespace;

        $max_num_pages = max(array(ceil($GLOBALS['wp_query']->found_posts / $GLOBALS['wp_query']->post_count), $GLOBALS['wp_query']->max_num_pages));
        // Don't print empty markup if there's only one page.
        if ($max_num_pages < 2) {
            return;
        }

        $links = false;
        if ($numeric) {
            $paged = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
            $pagenum_link = html_entity_decode(get_pagenum_link());
            $query_args = array();
            $url_parts = explode('?', $pagenum_link);

            if (isset($url_parts[1])) {
                wp_parse_str($url_parts[1], $query_args);
            }

            $pagenum_link = remove_query_arg(array_keys($query_args), $pagenum_link);
            $pagenum_link = trailingslashit($pagenum_link) . '%_%';

            $format = $GLOBALS['wp_rewrite']->using_index_permalinks() && !strpos($pagenum_link, 'index.php') ? 'index.php/' : '';
            $format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit('page/%#%', 'paged') : '?paged=%#%';

            // Set up paginated links.
            $links = paginate_links(array(
                'base' => $pagenum_link,
                'format' => $format,
                'total' => $GLOBALS['wp_query']->max_num_pages,
                'current' => $paged,
                'mid_size' => 1,
                'add_args' => array_map('urlencode', $query_args),
                'prev_text' => __('&larr; Previous', $theme_namespace),
                'next_text' => __('Next &rarr;', $theme_namespace),
            ));
        } else {
            $args = array(
                'prelabel' => __('<i class="icon-left-dir"></i><span>Previous</span>', $theme_namespace),
                'nxtlabel' => __('<span>Next</span><i class="icon-right-dir"></i>', $theme_namespace)
            );
            // $links = get_posts_nav_link($args);
            $links = get_previous_posts_link(__('<i class="glyphicon glyphicon-chevron-left"></i><span>Previous</span>', $theme_namespace));
            $links .= get_next_posts_link(__('<span>Next</span><i class="glyphicon glyphicon-chevron-right"></i>', $theme_namespace));
        }
        $grid_values = at_responsive_get_content_grid_values();
        $grid_classes = at_responsive_get_content_grid_classes();

        $pagination_columns = isset($grid_values['pagination']) ? $grid_values['pagination'] : 12;
        $pagination_classes = isset($grid_classes['pagination']) ? $grid_classes['pagination'] : '';

        if ($links) :
        ?>
        <div class="row content-row">
            <div class="pagination col-md-<?php echo $pagination_columns; ?> <?php echo $pagination_classes; ?>">
                <h2 class="screen-reader-text"><?php _e('Posts navigation', $theme_namespace); ?></h2>
                <?php echo apply_filters('at_responsive_pagination_links', $links); ?>
                <div style="width:100%; height: 0px; clear: both;"></div>
            </div>
        </div>
        <?php
            endif;
    }

    /**
    * Format Pagination Links
    * 
    * @param array $attr Parameter 
    * @since 1.0
    * @return string  Return 
    */
    function at_responsive_format_pagination_prev($attr) {
        return ' title="Previous Posts" class="previous"';
    }

    /**
    * Format Pagination Links
    * 
    * @param array $attr Parameter 
    * @since 1.0
    * @return string  Return 
    */
    function at_responsive_format_pagination_next($attr) {
        return ' title="Next Posts" class="next"';
    }

    /* Format Title Tag */

    /**
    * Format Title Tag
    * 
    * @param string  $title Parameter 
    * @param string $sep   Parameter 
    * @since 1.0
    * @return string  Return 
    */
    function at_responsive_wp_title($title, $sep) {
        global $paged, $page, $theme_namespace;

        if (is_feed())
            return $title;

        // Add the site name.
        $title .= get_bloginfo('name');

        // Add the site description for the home/front page.
        $site_description = get_bloginfo('description', 'display');
        if ($site_description && ( is_home() || is_front_page() ))
            $title = "$title $sep $site_description";

        // Add a page number if necessary.
        if ($paged >= 2 || $page >= 2)
            $title = "$title $sep " . sprintf(__('Page %s', $theme_namespace), max($paged, $page));

        return $title;
    }

    /**
    * Return Type of Page Content being output (in the loop)
    * 
    * @since 1.0
    * @return string Return 
    */
    function at_responsive_wp_content_type() {
        global $wp_query;
        // echo "<pre>" . print_r($wp_query, true) . "</pre>";
        $type = get_post_type();
        if ($wp_query->is_home) {
            $type = 'home';
        } elseif ($wp_query->is_404) {
            $type = '404';
        } elseif ($wp_query->is_archive) {
            $type = 'archive';
        } elseif ($wp_query->is_attachment) {
            $type = 'attachment';
        } elseif ($wp_query->is_author) {
            $type = 'author';
        } elseif ($wp_query->is_category) {
            $type = 'category';
        } elseif ($wp_query->is_comments_popup) {
            $type = 'comments-popup';
        } elseif ($wp_query->is_date) {
            $type = 'date';
        } elseif ($wp_query->is_day) {
            $type = 'date';
        } elseif (is_front_page()) {
            $type = 'front_page';
        } elseif ($wp_query->is_month) {
            $type = 'date';
        } elseif ($wp_query->is_page) {
            $type = 'page';
        } elseif ($wp_query->is_post_type_archive) {
            $type = get_post_type() . '-archive';
        } elseif ($wp_query->is_preview) {
            $type = 'preview';
        } elseif ($wp_query->is_search) {
            $type = 'search';
        } elseif ($wp_query->is_single) {
            $type = 'single-post';
        } elseif ($wp_query->is_singular) {
            $type = 'singular';
        } elseif ($wp_query->is_tag) {
            $type = 'tag';
        } elseif ($wp_query->is_tax) {
            $type = 'tax';
        } elseif ($wp_query->is_time) {
            $type = 'date';
        } elseif ($wp_query->is_trackback) {
            $type = 'trackback';
        } elseif ($wp_query->is_year) {
            $type = 'date';
        }
        return $type;
    }

    /**
    * Get Layout Type
    * 
    * @param string $layout Parameter 
    * @since 1.0
    * @return string Return 
    */
    function at_responsive_wp_template_type($layout = 'singular') {
        global $post, $wp_query;

        if (is_archive())
            $layout = 'archive';

        if (is_date())
            $layout = 'date';

        if (is_singular())
            $layout = 'singular';

        if (is_search())
            $layout = 'search';

        if (is_home())
            $layout = 'home';

        if (is_front_page())
            $layout = 'front_page';

        if (is_feed())
            $layout = 'feed';

        if (is_404() || (!$post))
            $layout = '404';

        return $layout;
    }

    /**
    * Add classes to BODY tag
    * 
    * @param array $classes Parameter 
    * @since 1.0
    * @return array Return 
    */
    function at_responsive_body_classes($classes) {
        global $at_theme_custom, $theme_namespace;

        $classes[] = $theme_namespace;

        $sticky_nav = $at_theme_custom->get_option('settings/stickynav');
        if ($sticky_nav) {
            $classes[] = 'at-fixed';
        }

        if (is_multi_author()) {
            $classes[] = 'group-blog';
            $classes[] = 'multi-author';
        }

        if (get_header_image()) {
            $classes[] = 'header-image';
        } else {
            $classes[] = 'masthead-fixed';
        }

        if (is_singular() && !is_front_page()) {
            $classes[] = 'singular';
        }

        if (is_404() || (is_search() && !have_posts())) {
            $classes[] = 'singular';
        }

        $classes[] = at_responsive_slugify(wp_get_theme()->get('Name'));

        return $classes;
    }

    /**
    * Custom Post Class based on Layout
    * 
    * @param string $additional_classes Parameter 
    * @param string $layout_classes     Parameter 
    * @since 1.0
    * @return string Return 
    */
    function at_responsive_get_post_class($additional_classes = '', $layout_classes = '') {
        $layout = at_responsive_get_theme_option('settings/layout', 'fullwidth');

        switch ($layout) {
            case 'left' :
            case 'right' : {
                $layout_classes = "col-md-8";
                break;
            }
            case 'fullwidth' :
            case 'nosidebar' : {
                $layout_classes = "col-md-12";
                break;
            }
        }
        $class = trim($additional_classes) . " " . trim($layout_classes);
        return 'class="' . join(' ', get_post_class($class)) . '"';
    }

    /**
    * Display Post Social Sharing Elements
    * 
    * @since 1.0
    * @return void 
    */
    function at_responsive_post_social_sharing() {
        global $post;
        if (!$post)
            $post = get_post();
    ?>
    <div class="drawer share-pull-down" id="share-drawer-<?php the_ID(); ?>">
        <div class="social-share-post">
            <!--FB Share-->
            <div class="fb-share-button" data-href="<?php the_permalink(); ?>" data-type="button_count"></div>
            <!--End of FB Share-->

            <!--Twitter Share-->
            <a title="Tweet to Share" href="https://twitter.com/share" class="twitter-share-button" data-dnt="true">Tweet</a>
            <script>!function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                    if (!d.getElementById(id)) {
                        js = d.createElement(s);
                        js.id = id;
                        js.src = p + '://platform.twitter.com/widgets.js';
                        fjs.parentNode.insertBefore(js, fjs);
                    }
                }(document, 'script', 'twitter-wjs');
            </script>
            <!--End of Twitter Share-->

            <!--G Plus Share-->
            <div  class="g-plus" data-action="share"></div>

            <script type="text/javascript">
                (function() {
                    var po = document.createElement('script');
                    po.type = 'text/javascript';
                    po.async = true;
                    po.src = 'https://apis.google.com/js/platform.js';
                    var s = document.getElementsByTagName('script')[0];
                    s.parentNode.insertBefore(po, s);
                })();
            </script>
            <!--End of G Plus Share-->

            <!--Pin It-->
            <span class="pin-it" ><a title="Pin to Share" href="//www.pinterest.com/pin/create/button/?url=<?php echo rawurlencode(get_the_permalink()); ?>" data-pin-do="buttonPin" data-pin-config="beside" data-pin-color="red"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_red_20.png" alt="Pinterest"></a></span>
            <!-- End of Pin It-->

            <!--LinkedIn-->
            <div class="linkedin">
                <script src="//platform.linkedin.com/in.js" type="text/javascript">
                    lang: en_US
                </script>
                <script type="IN/Share" data-url="<?php the_permalink(); ?>" data-counter="right"></script>
            </div>
            <!--End of LinkedIn-->

            <p class="email-share"><a title="Email to Share" href="mailto:?subject=<?php echo rawurlencode(get_the_title()); ?>&amp;body=<?php echo rawurlencode(get_the_excerpt()); ?>"><i class="icon-mail"></i></a></p>
        </div>
    </div>
    <?php
    }

    /**
    * Display Post Social Sharing Elements
    * 
    * @since 1.0
    * @return void 
    */
    function at_responsive_site_social_sharing() {
        global $post;
        if (!$post)
            $post = get_post();
        $site_url = home_url('/');
        $site_name = get_bloginfo('name');
        $message = <<<EOT
Link to & $site_name ($site_url).
EOT;
        $theme_options = at_responsive_get_theme_option();
    ?>
    <div class="dropdown social-pull-down">
        <div class="menu-social-container">
            <div class="social-button">
                <!--FB Like-->
                <div class="fb-like" data-href="<?php echo $site_url; ?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false" data-colorscheme="light"></div>
                <!--End of FB Like-->

                <?php
                    if (empty($theme_options['social']['widget']['Twitter']) === false) :
                        $twitter = $theme_options['social']['widget']['Twitter'];
                        if (preg_match("/https?:\/\/(www\.)?twitter\.com\/(#!\/)?@?([^\/]*)/", $twitter, $parts)) {
                            if (isset($parts[3])) {
                                $twitter_handle = $parts[3];
                            ?>
                            <!--Twitter Follow-->
                            <a title="Follow @<?php echo $twitter_handle; ?> on Twitter" href="<?php echo $twitter; ?>" class="twitter-follow-button" data-show-count="true" data-show-screen-name="false" data-dnt="true">Follow @<?php echo $twitter_handle; ?></a>
                            <script>!function(d, s, id) {
                                    var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                                    if (!d.getElementById(id)) {
                                        js = d.createElement(s);
                                        js.id = id;
                                        js.src = p + '://platform.twitter.com/widgets.js';
                                        fjs.parentNode.insertBefore(js, fjs);
                                    }
                                }(document, 'script', 'twitter-wjs');
                            </script>
                            <!--End of Twitter Follow-->
                            <?php
                            }
                        }
                    ?>
                    <?php endif; ?>

                <?php if (@isset($theme_options['social']['widget']['Google'])) : ?>
                    <!--G Plus Follow-->
                    <div class="g-follow" data-annotation="bubble" data-height="20" data-href="<?php echo $theme_options['social']['widget']['Google']; ?>" data-rel="publisher">
                    </div>
                    <script type="text/javascript">
                        (function() {
                            var po = document.createElement('script');
                            po.type = 'text/javascript';
                            po.async = true;
                            po.src = 'https://apis.google.com/js/platform.js';
                            var s = document.getElementsByTagName('script')[0];
                            s.parentNode.insertBefore(po, s);
                        })();
                    </script>    
                    <!--End of G Plus Follow-->
                    <?php endif; ?>

                <!--LinkedIn-->
                <script src="//platform.linkedin.com/in.js" type="text/javascript">
                    lang: en_US
                </script>
                <script type="IN/Share" data-url="<?php echo $site_url; ?>" data-counter="right"></script>
                <!--End of LinkedIn-->

                <p class="email-share"><a title="Email to Share this Page" href="<?php echo ('mailto:?subject=' . preg_replace('/[^\w\d :\/\.\(\)]/i', '', $site_name) . '&amp;body=' . preg_replace('/[^\w\d :\/\.\(\)]/i', '', $message)); ?>" title="Email"><i class="icon-mail"></i></a>
                </p>
            </div>
        </div>
    </div>

    <?php
    }

    if (!function_exists('current_page_url')) {

        /**
        * Get Current Page URL
        * 
        * @since 1.0
        * @return string Return 
        */
        function current_page_url() {
            $pageURL = 'http';
            if (isset($_SERVER["HTTPS"])) {
                if ($_SERVER["HTTPS"] == "on") {
                    $pageURL .= "s";
                }
            }
            $pageURL .= "://";
            if ($_SERVER["SERVER_PORT"] != "80") {
                $pageURL .= $_SERVER["HTTP_HOST"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
            } else {
                $pageURL .= $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
            }
            return $pageURL;
        }

    }

    /**
    * Get Page Title
    * 
    * @param boolean $default Parameter 
    * @since 1.0
    * @return mixed   Return 
    */
    function at_responsive_get_page_title($default = false) {
        global $theme_namespace;
        if (is_home() || is_front_page()) {
            return 'Home';
        } elseif (is_archive()) {
            $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
            if ($term) {
                return $term->name;
            } elseif (is_post_type_archive()) {
                return get_queried_object()->labels->name;
            } elseif (is_day()) {
                return sprintf(__('%s', $theme_namespace), get_the_date('d M'));
            } elseif (is_month()) {
                return sprintf(__('%s', $theme_namespace), get_the_date('M Y'));
            } elseif (is_year()) {
                return sprintf(__('%s', $theme_namespace), get_the_date('Y'));
            } elseif (is_author()) {
                $author = get_queried_object();
                return sprintf(__('%s', $theme_namespace), $author->display_name);
            } else {
                return single_cat_title('', false);
            }
        } elseif (is_search()) {
            return sprintf(__('%s', $theme_namespace), get_search_query());
        } elseif (is_404()) {
            return __('404', $theme_namespace);
        } else {
            if (get_the_title()) {
                return get_the_title();
            }
        }
        return $default;
    }

    /**
    * Get Logo
    * 
    * @param boolean $default Parameter 
    * @since 1.0
    * @return mixed   Return 
    */
    function at_responsive_get_companylogo($default = false) {
        global $at_theme_custom;
        return $at_theme_custom->get_option('images/companylogo', $default);
    }

    /**
    * Strip Selected Tags from HTML
    * 
    * @param string $text Parameter 
    * @param array   $tags Parameter 
    * @since 1.0
    * @return unknown Return 
    */
    function at_responsive_strip_selected_tags($text, $tags = array()) {
        $args = func_get_args();
        $text = array_shift($args);
        $tags = func_num_args() > 2 ? array_diff($args, array($text)) : (array) $tags;
        foreach ($tags as $tag) {
            if (preg_match_all('/<' . $tag . '[^>]*>(.*)<\/' . $tag . '>/iU', $text, $found)) {
                $text = str_replace($found[0], $found[1], $text);
            }
        }

        return $text;
    }

    /**
    * Browser Console Debug
    * 
    * @param string  $content Parameter 
    * @param boolean $console Parameter 
    * @param boolean $dir     Parameter 
    * @since 1.0
    * @return void    
    */
    function js_alert($content, $console = true, $dir = false) {
        if ($console) {
            echo '<script type="text/javascript">' . ((is_array($content) || is_object($content) || $dir) ? ('console.dir(' . json_encode($content, JSON_FORCE_OBJECT) . ');') : 'console.log("' . $content . '");') . '</script>' . "\n";
        } else {
            echo '<script type="text/javascript">' . ((is_array($content) || is_object($content) || $dir) ? ('alert(' . json_encode($content, JSON_FORCE_OBJECT) . ');') : 'alert("' . $content . '");') . '</script>' . "\n";
        }
    }

    /**
    * Display Author Box on Single Posts
    * 
    * @since 1.0
    * @return unknown Return 
    */
    function at_responsive_do_author_box_single() {
        if (!is_single())
            return;

        if (get_the_author_meta('description'))
            at_responsive_author_box('single');
    }

    /**
    * Display Author Box on Author Pages
    * 
    * @since 1.0
    * @return unknown Return 
    */
    function at_responsive_do_author_box_author_page() {
        if (!is_author())
            return;

        at_responsive_author_box('single');
    }

    /**
    * Render Author Box
    * 
    * @param string  $context Parameter 
    * @param boolean $echo    Parameter 
    * @since 1.0
    * @return void    
    */
    function at_responsive_author_box($context = '', $echo = true) {
        get_template_part('templates/author');
    }

    /**
    * Gumby Column Wrap
    * 
    * @param string  $content Parameter 
    * @param string  $columns Parameter 
    * @param string  $el      Parameter 
    * @since 1.0
    * @return unknown Return 
    */
    function at_responsive_column_wrap($content = null, $columns = 'col-md-12', $el = 'div') {
        $output = null;
        if ($content) {
            $output = sprintf('<%1$s class="%2$s">' . $content . '</%1$s>', $el, $columns);
        }
        return $output;
    }

    /**
    * Default Image
    * 
    * @param string  $html              Parameter 
    * @param int $post_id           Parameter 
    * @param int $post_thumbnail_id Parameter 
    * @param array   $size              Parameter 
    * @param array   $attr              Parameter 
    * @since 1.0
    * @return string  Return 
    */
    function at_responsive_default_ft_image($html, $post_id, $post_thumbnail_id, $size, $attr) {
        if (!$html) {
            $fallback_img = false;
            $upload_dir = wp_upload_dir();
            $working_dir = $upload_dir['basedir'] . '/default_img/';
            $sizes_array = at_responsive_list_thumbnail_sizes();
            $width = 0;
            $height = 0;
            if (is_string($size) && isset($sizes_array[$size])) {
                $slug = $sizes_array[$size]['width'] . 'x' . $sizes_array[$size]['height'];
                $width = $sizes_array[$size]['width'];
                $height = $sizes_array[$size]['height'];
            } elseif (is_array($size)) {
                $slug = join('x', $size);
                $width = $size[0];
                $height = $size[1];
            }
            $img_filepath = at_responsive_get_theme_option('images/defaultimg', template_directory . '/lib/assets/img/default-img.jpg');
            if ($img_filepath) {
                $img_details = getimagesize($img_filepath);
                if (!$img_details || @$sizes_array[$size]['width'] > $img_details[0] || @$sizes_array[$size]['height'] > $img_details[1]) {
                    $img_filepath = false;
                }
            }
            if (empty($img_filepath))
                $img_filepath = template_directory . '/lib/assets/img/default-img.jpg';
            $img_info = pathinfo($img_filepath);
            $img_base_filename = $img_info['filename'] . '.' . $img_info['extension'];
            $img_filename = $img_info['filename'] . '-' . $slug . '.' . $img_info['extension'];
            if (file_exists($working_dir . $img_filename)) {
                $fallback_img = $upload_dir['baseurl'] . '/default_img/' . $img_filename;
            } else {
                if (@mkdir($working_dir, 0775, true) || is_dir($working_dir)) {
                    if (copy($img_filepath, $working_dir . $img_base_filename) || file_exists($img_filepath, $working_dir . $img_base_filename)) {
                        $uploaded_img = $working_dir . $img_base_filename;
                        $img = wp_get_image_editor($uploaded_img);
                        if (!is_wp_error($img)) {
                            $resize = $img->multi_resize($sizes_array);
                            if ($resize) {
                                if (is_string($size) && isset($resize[$size])) {
                                    $slug = $resize[$size]['width'] . 'x' . $resize[$size]['height'];
                                } elseif (is_array($size)) {
                                    foreach ($resize as $size_array) {
                                        if ($size_array['width'] == $size[0]) {                                        
                                            $slug = $size_array['width'] . 'x' . $size_array['height'];
                                            break;
                                        }
                                    }
                                }
                                $img_filename = $img_info['filename'] . '-' . $slug . '.' . $img_info['extension'];
                                $fallback_img = file_exists($working_dir . $img_filename) ? $upload_dir['baseurl'] . '/default_img/' . $img_filename : false;
                            }
                        }
                    }
                }
            }
            if ($fallback_img) {
                $attr['src'] = $fallback_img;
                $hwstring = image_hwstring($width, $height);
                $html = rtrim("<img $hwstring");
                foreach ($attr as $name => $value) {
                    $html .= " $name=" . '"' . $value . '"';
                }
                $html .= ' />';
            }
        }
        return $html;
    }

    /**
    * List Image Sizes
    * 
    * @param boolean $crop Parameter 
    * @since 1.0
    * @return array   Return 
    */
    function at_responsive_list_thumbnail_sizes($crop = true) {
        global $_wp_additional_image_sizes;
        $sizes = array();
        foreach (get_intermediate_image_sizes() as $s) {
            $sizes[$s] = array(0, 0);
            if (in_array($s, array('thumbnail', 'medium', 'large'))) {
                $sizes[$s]['width'] = get_option($s . '_size_w');
                $sizes[$s]['height'] = get_option($s . '_size_h');
                $sizes[$s]['crop'] = $crop;
            } else {
                if (isset($_wp_additional_image_sizes) && isset($_wp_additional_image_sizes[$s]))
                    $sizes[$s] = array(
                        'width' => $_wp_additional_image_sizes[$s]['width'],
                        'height' => $_wp_additional_image_sizes[$s]['height'],
                        'crop' => $crop,
                    );
            }
        }
        return $sizes;
    }

    /**
    * True if current theme supports genesis-html5, false otherwise.
    * 
    * @since 1.0
    * @return string Return 
    */
    function at_responsive_html5() {
        return current_theme_supports('html5');
    }

    /**
    * Soliloquy Slider
    * 
    * @param string  $null_value  Parameter 
    * @param string  $banner_type Parameter 
    * @param boolean $banner_arg  Parameter 
    * @since 1.0
    * @return void    
    */
    function at_responsive_masthead($null_value = '', $banner_type = 'slider', $banner_arg = false) {
        // $mode = at_responsive_get_theme_option('banner_type', $banner_type);
        $mode = $banner_type;
        $arg = at_responsive_get_theme_option('settings/slider', false);
        if (!$arg) {
            $sliders = at_responsive_get_sliders();
            if ($sliders) {
                if (isset($sliders[$banner_arg]))
                    $arg = $sliders[$banner_arg];
                else
                    $arg = key($sliders);
            }
        }
        if ($arg) {
            switch ($mode) {
                case 'slider' : {
                    if (function_exists('soliloquy') && $arg) {
                        echo '<div id="slider">' . "\n";
                        soliloquy($arg, 'slug');
                        echo '</div>' . "\n";
                    }
                    break;
                }
                case 'image' : {
                    if ($arg) {
                        echo '<div id="slider" class="at_banner_img">' . "\n";
                        echo '<img src="' . $arg . '" alt="' . get_bloginfo('name') . ' | ' . get_bloginfo('description') . '" />' . "\n";
                        echo '</div>' . "\n";
                    }
                    break;
                }
            }
        }
    }

    /**
    * Return Soliloquy Sliders
    * 
    * @since 1.0
    * @return mixed Return 
    */
    function at_responsive_get_sliders() {
        $output = false;
        if (function_exists('soliloquy')) {
            global $soliloquy;
            if (is_object($soliloquy)) {
                $sliders = $soliloquy->get_sliders();
                if ($sliders) {
                    foreach ($sliders as $slider) {
                        $output[$slider['config']['slug']] = $slider['config']['title'];
                    }
                }
            }
        }
        return $output;
    }

    /**
    * Check if Customizer is Active
    * 
    * @since 1.0
    * @return boolean Return 
    */
    function at_responsive_is_customizer() {
        if (isset($_REQUEST['wp_customize'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
    * Convert absolute file path to url
    * 
    * @param string $file Parameter 
    * @since 1.0
    * @return mixed   Return 
    */
    function at_responsive_file2url($file) {
        return str_replace(array(ABSPATH, DIRECTORY_SEPARATOR), array(home_url('/'), "/"), $file);
    }

    /**
    * Convert absolute file path to relative path
    * 
    * @param string $file Parameter 
    * @since 1.0
    * @return mixed   Return 
    */
    function at_responsive_file2rel($file) {
        return str_replace(array(ABSPATH, DIRECTORY_SEPARATOR), array("", "/"), $file);
    }

    /**
    * Adding device class to body tag
    * 
    * @param array $classes Parameter 
    * @since 1.0
    * @return array Return 
    */
    function at_responsive_browser_body_class($classes) {

        // A little Browser detection shall we?
        $browser = $_SERVER['HTTP_USER_AGENT'];

        // Detect Mobile
        $mcd_browser = get_query_var('browser');
        $mcd_platform = get_query_var('platform');
        if (in_array($mcd_platform, array('tablet', 'mobile'))) {
            $classes[] = 'mobile';
        }

        // Mac, PC ...or Linux
        if (preg_match("/Mac/", $browser)) {
            $classes[] = 'mac';
        } elseif (preg_match("/Windows/", $browser)) {
            $classes[] = 'windows';
        } elseif (preg_match("/Linux/", $browser)) {
            $classes[] = 'linux';
        } else {
            $classes[] = 'unknown-os';
        }

        // Checks browsers in this order: Chrome, Safari, Opera, MSIE, FF
        if (preg_match("/Chrome/", $browser)) {
            $classes[] = 'chrome';
            preg_match("/Chrome\/(\d.\d)/si", $browser, $matches);
            if ($matches) {
                $classesh_version = 'ch' . str_replace('.', '-', $matches[1]);
                $classes[] = $classesh_version;
            }
        } elseif (preg_match("/Safari/", $browser)) {
            $classes[] = 'safari';
            preg_match("/Version\/(\d.\d)/si", $browser, $matches);
            if ($matches) {
                $sf_version = 'sf' . str_replace('.', '-', $matches[1]);
                $classes[] = $sf_version;
            }
        } elseif (preg_match("/Opera/", $browser)) {
            $classes[] = 'opera';
            preg_match("/Opera\/(\d.\d)/si", $browser, $matches);
            if ($matches) {
                $op_version = 'op' . str_replace('.', '-', $matches[1]);
                $classes[] = $op_version;
            }
        } elseif (preg_match("/MSIE|rv:11.0/", $browser)) {
            $classes[] = 'msie';
            if (preg_match("/MSIE 6.0/", $browser)) {
                $classes[] = 'ie6';
                $classes[] = 'obsolete';
            } elseif (preg_match("/MSIE 7.0/", $browser)) {
                $classes[] = 'ie7';
                $classes[] = 'obsolete';
            } elseif (preg_match("/MSIE 8.0/", $browser)) {
                $classes[] = 'ie8';
                $classes[] = 'obsolete';
            } elseif (preg_match("/MSIE 9.0/", $browser)) {
                $classes[] = 'ie9';
                $classes[] = 'obsolete';
            } elseif (preg_match("/MSIE 10/", $browser)) {
                $classes[] = 'ie10';
            } elseif (preg_match("/rv:11.0/", $browser)) {
                $classes[] = 'ie11';
            }
        } elseif (preg_match("/Firefox/", $browser) && preg_match("/Gecko/", $browser)) {
            $classes[] = 'firefox';
            preg_match("/Firefox\/(\d)/si", $browser, $matches);
            $ff_version = 'ff' . str_replace('.', '-', $matches[1]);
            $classes[] = $ff_version;
        } else {
            $classes[] = 'unknown-browser';
        }
        if (!preg_match("/MSIE|rv:11.0/", $browser)) {
            $classes[] = 'no-ie';
        }
        return $classes;
    }

    /**
    * Add class for fixed navbar
    * 
    * @param array $classes Parameter 
    * @since 1.0
    * @return array Return 
    */
    function at_responsive_navbar_body_class($classes) {
        $sticky_nav = at_responsive_get_theme_option('settings/stickynav', false);
        if ($sticky_nav)
            $classes[] = "sticky-nav";

        return $classes;
    }

    /**
    * Post Meta
    * 
    * @param object $post Parameter 
    * @since 1.0
    * @return void   
    */
    function at_responsive_post_meta($post = null) {
        if (!$post)
            $post = get_post();
        if (!is_page($post->ID)) :
            $has_tags = has_tag('', $post);
            $has_cat = has_category('', $post);
            if ($has_tags || $has_cat) :
            ?>        
            <ul class="post-meta">
                <?php if ($has_cat) : ?>
                    <li class="categories"><span class="icon"></span>Filed Under: &nbsp; <?php the_category('&ndash; &nbsp;', '', $post->ID); ?></li>
                    <?php endif; ?>
                <?php if ($has_tags) : ?>
                    <li class="tags"><span class="icon"></span><?php echo get_the_tag_list('Tagged With: &nbsp; ', '&ndash; &nbsp;', '<br />', $post->ID); ?></li>
                    <?php endif; ?>
            </ul>
            <?php
                endif;
            endif;
    }

    /**
    * Get Virtual Page Meta
    * 
    * @param string $id Parameter 
    * @since 1.0
    * @return string Return 
    */
    function at_responsive_get_vpage_meta($id) {
        return get_transient('at_responsive_' . $id);
    }

    /**
    * Truncate String
    * 
    * @param string $str    Parameter 
    * @param integer $length Parameter 
    * @since 1.0
    * @return unknown Return 
    */
    function at_responsive_truncate_str($str, $length = 55) {
        if (strlen($str) > $length) {
            $str = substr($str, 0, $length) . " &hellip;";
        }
        return $str;
    }

    /**
    * Check if page/post is templated
    * 
    * @param int $id Parameter 
    * @since 1.0
    * @return void    
    */
    function is_at_responsive_template($id) {

    }

    /**
    * Modified Get Template Part
    * 
    * @param string $slug Parameter 
    * @param string $name Parameter 
    * @since 1.0
    * @return void    
    */
    function at_responsive_get_template_part($slug, $name = null) {
        global $post;
        if (!$post)
            $post = get_post();

        $template_slug = wp_cache_get($post->ID, 'at_responsive_template');

        if ($template_slug)
            get_template_part($slug, $template_slug);
        else
            get_template_part($slug, $name);
    }

    /**
    * Add Desktop Only Class to Dashboard CTA Widget
    * 
    * @param array $params Parameter 
    * @since 1.0
    * @return array Return 
    */
    function at_responsive_dashboard_cta_class($params) {
        if (stristr($params[0]['widget_id'], "dashboard_widget")) { //make sure its your widget id here
            // its your widget so you add  your classes
            $new_class = 'class=" ' . 'visible-md visible-lg '; // make sure you leave a space at the end
            $params[0]['before_widget'] = str_replace('class="', $new_class, $params[0]['before_widget']);
        }
        return $params;
    }

    /* Add facebook, twitter, & google+ links to the user profile */

    /**
    * Add facebook, twitter, & google+ links to the user profile
    * 
    * @param array $contactmethods Parameter 
    * @since 1.0
    * @return array Return 
    */
    function at_responsive_add_user_fields($contactmethods) {
        // Add Facebook
        $contactmethods['user_fb'] = 'Facebook';
        // Add Twitter
        $contactmethods['user_tw'] = 'Twitter';
        // Add Google+
        $contactmethods['google_profile'] = 'Google+ Profile URL';
        // Save 'Em
        return $contactmethods;
    }

    /**
    * Author vs Me Authorship Rel Text
    * 
    * @param string $rel Parameter 
    * @since 1.0
    * @return string Return 
    */
    function at_responsive_google_authorship_rel_text($rel) {
        if (!is_author())
            $rel = "me";

        return $rel;
    }

    /**
    * Google Authorship Author Link Rel Tag
    * 
    * @param string $link Parameter 
    * @since 1.0
    * @return unknown Return 
    */
    function at_responsive_google_authorship_author_posts_link($link) {
        if (!is_author())
            $link = str_replace('rel="author"', 'rel="me"', $link);

        return $link;
    }

    /**
    * Adjust Published Date Time to Relative Format
    * 
    * @since 1.0
    * @return unknown Return 
    */
    function at_responsive_published_timeago() {
        global $post;
        if (!$post)
            $post = get_post();
        $date = $post->post_date;
        $time = get_post_time('G', true, $post);
        $time_diff = time() - $time;

        if ($time_diff > 0 && $time_diff < 24 * 60 * 60)
            $display = sprintf(__('%s ago'), human_time_diff($time));
        else
            $display = date(get_option('date_format'), strtotime($date));

        return $display;
    }

    /**
    * Adjust Updated Date Time to Relative Format
    * 
    * @since 1.0
    * @return unknown Return 
    */
    function at_responsive_updated_timeago() {
        global $post;
        if (!$post)
            $post = get_post();
        $date = $post->post_modified;
        $time = get_post_modified_time('G', true, $post);
        $time_diff = time() - $time;

        if ($time_diff > 0 && $time_diff < 24 * 60 * 60)
            $display = sprintf(__('%s ago'), human_time_diff($time));
        else
            $display = date(get_option('date_format'), strtotime($date));

        return $display;
    }

    /* Add the Open Graph in the Language Attributes */

    /**
    * Add the Open Graph in the Language Attributes
    * 
    * @param string $output Parameter 
    * @since 1.0
    * @return string Return 
    */
    function at_responsive_add_opengraph_doctype($output) {
        if (at_responsive_get_theme_option('seo/facebookid', false))
            return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
    }

    /* Add Open Graph Meta Info */

    /**
    * Add Open Graph Meta Info
    * 
    * @since 1.0
    * @return unknown Return 
    */
    function at_responsive_insert_fb_in_head() {
        global $post;
        if (!$post)
            $post = get_post();
        if (!is_singular()) //if it is not a post or a page
            return;
        // remove_theme_mod('at_responsive_facebookid');
        $fbid = at_responsive_get_theme_option('seo/facebookid', false);
        if (!$fbid)
            return;

        $site_name = get_bloginfo('name');
        $default_img_path = at_responsive_get_theme_option('images/defaultimg', template_directory . '/lib/assets/img/default-img.jpg');
        $default_img_url = at_responsive_file2url($default_img_path);

        echo '<meta property="fb:admins" content="' . $fbid . '"/>';
        echo "\n";
        echo '<meta property="og:title" content="' . get_the_title() . '"/>';
        echo "\n";
        echo '<meta property="og:type" content="article"/>';
        echo "\n";
        echo '<meta property="og:url" content="' . get_permalink() . '"/>';
        echo "\n";
        echo '<meta property="og:site_name" content="' . $site_name . '"/>';
        echo "\n";
        if (!has_post_thumbnail($post->ID)) { //the post does not have featured image, use a default image
            echo '<meta property="og:image" content="' . $default_img_url . '"/>';
            echo "\n";
        } else {
            $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'medium');
            echo '<meta property="og:image" content="' . esc_attr($thumbnail_src[0]) . '"/>';
            echo "\n";
        }
        echo "\n";
    }

    /* Add Author Meta in Single and Author Pages */

    /**
    * Add Author Meta in Single and Author Pages
    * 
    * @since 1.0
    * @return void 
    */
    function at_responsive_single_author_meta() {
        global $post;
        if (!$post)
            $post = get_post();
        if (!is_singular() && !is_author()) //if it is not a post or a page or author
            return;

        if (is_single()) {
            $author_name = get_the_author_meta('display_name', $post->post_author);
        } else {
            $curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
            $author_name = $curauth ? $curauth->display_name : get_the_author_meta('display_name', $post->post_author);
        }

        echo "<meta name=\"author\" itemprop=\"name\" content=\"{$author_name}\">\n";
    }

    /**
    * Set Responsive Grid Variables
    * 
    * @param float|int $full       Parameter 
    * @param float|int $row        Parameter 
    * @param float|int $home       Parameter 
    * @param float|int|boolean $archive    Parameter 
    * @param float|int|boolean $single     Parameter 
    * @param float|int|boolean $sidebar    Parameter 
    * @param float|int|boolean $secondary  Parameter 
    * @param float|int|boolean $loop_start Parameter 
    * @param float|int|boolean $loop_end   Parameter 
    * @param float|int|boolean $pagination Parameter 
    * @param float|int|boolean $comments   Parameter 
    * @param float|int|boolean $featured   Parameter 
    * @since 1.0
    * @return void    
    */
    function at_responsive_set_content_grid_values($full, $row, $home, $archive = false, $single = false, $sidebar = false, $secondary = false, $loop_start = false, $loop_end = false, $pagination = false, $comments = false, $featured = false) {
        global $at_theme_custom;
        $at_responsive_grid = array();
        $at_responsive_grid['full'] = $full;
        $at_responsive_grid['row'] = $row;
        $at_responsive_grid['home'] = $home;

        if ($archive)
            $at_responsive_grid['archive'] = $archive;
        else
            $at_responsive_grid['archive'] = $home;

        if ($single)
            $at_responsive_grid['single'] = $single;
        else
            $at_responsive_grid['single'] = $home;

        if ($sidebar)
            $at_responsive_grid['sidebar'] = $sidebar;
        else
            $at_responsive_grid['sidebar'] = $row;

        if ($secondary)
            $at_responsive_grid['secondary'] = $secondary;
        else
            $at_responsive_grid['secondary'] = $sidebar;

        if ($loop_start)
            $at_responsive_grid['loop_start'] = $loop_start;
        else
            $at_responsive_grid['loop_start'] = $full;

        if ($loop_end)
            $at_responsive_grid['loop_end'] = $loop_end;
        else
            $at_responsive_grid['loop_end'] = $full;

        if ($pagination)
            $at_responsive_grid['pagination'] = $pagination;
        else
            $at_responsive_grid['pagination'] = $full;

        if ($comments)
            $at_responsive_grid['comments'] = $comments;
        else
            $at_responsive_grid['comments'] = $full;

        if ($featured)
            $at_responsive_grid['featured'] = $featured;
        else
            $at_responsive_grid['featured'] = $home;

        if (! $at_theme_custom->is_customizer())
            $at_theme_custom->set_option($at_responsive_grid, 'appearance/grid', false);

        // For Theme Customizer Preview
        add_filter('at_responsive_theme_mod_at_responsive[appearance][grid]', function ($default) use ($at_responsive_grid) {$default = $at_responsive_grid; return $default;});

        return apply_filters('at_responsive_content_grid', $at_responsive_grid);        
    }

    /**
    * Set Theme Setting Default Values
    * 
    * @param array $args Optional. Default configuration values for Theme also accessible in Theme Settings section of customizer.
    *       - exerptlength (int) Numner of Words in Excerpt
    *       - homeexcerptlength (int) Number of Words in Excerpt on Homepage
    *       - postsperpage (int) Number of Posts per Page
    *       - homepostsperpage (int) Number of Posts on Homepage
    *       - stickynav (bool) Enable Sticky Navigation
    * 
    * @since 1.0
    * @return void 
    */
    function at_responsive_set_theme_settings_default_values($args = array()) {
        if ($args) {
            foreach ($args as $param => $value) {
                switch($param) {
                    case 'excerptlength' : 
                    case 'homeexcerptlength' : 
                    case 'postsperpage' : 
                    case 'homepostsperpage' : 
                    case 'stickynav' : {
                        add_filter("at_responsive_child_theme_mod_" . "at_responsive[settings][{$param}]", function($default) use($value) {return $value;});
                        break;
                    }
                    default : {
                        //
                        break;
                    }
                }
            }
        }
    }

    /**
    * Get Responsive Grid Variables
    * 
    * @param mixed $default Parameter 
    * @since 1.0
    * @return mixed Return 
    */
    function at_responsive_get_content_grid_values($default = false) {
        global $at_theme_custom;
        if (!$default)
            $default = array('full' => 12, 'row' => 12, 'home' => 4, 'archive' => 6, 'single' => 12, 'sidebar' => 12, 'secondary' => 12, 'loop_start' => 12, 'loop_end' => 12, 'pagination' => 12, 'comments' => 12, 'featured' => 6);

        if (! $at_theme_custom->is_customizer())
            return $at_theme_custom->get_option('appearance/grid', $default, false);
        else
            return $at_theme_custom->get_option('appearance/grid', $default);
    }

    /**
    * Get Responsive Grid Classes
    * 
    * @param array $default Parameter 
    * @since 1.0
    * @return mixed Return 
    */
    function at_responsive_get_content_grid_classes($default = array()) {
        if (!$default)
            $default = array('full' => '', 'row' => '', 'home' => '', 'archive' => '', 'single' => '', 'sidebar' => '', 'secondary' => '', 'loop_start' => '', 'loop_end' => '', 'pagination' => '', 'comments' => '', 'featured' => '');
        return apply_filters('at_responsive_content_grid_classes', $default);
    }

    /**
    * Get Grid Columns based on layout
    * 
    * @param boolean $layout Parameter 
    * @since 1.0
    * @return integer Return 
    */
    function at_responsive_grid_column_count($layout = false) {
        if (!$layout)
            $layout = at_responsive_wp_template_type();
        $grid_array = at_responsive_get_content_grid_values();
        $columns = 1;
        if ($grid_array) {
            switch ($layout) {
                case 'front_page' :
                case 'home' : {
                    $columns = (int) ceil($grid_array['row'] / $grid_array['home']);
                    break;
                }
                case 'archive' : {
                    $columns = (int) ceil($grid_array['row'] / $grid_array['archive']);
                    break;
                }
                default : {
                    $columns = (int) ceil($grid_array['row'] / $grid_array['home']);
                    break;
                }
                case 'singular' : {
                    $columns = (int) ceil($grid_array['row'] / $grid_array['single']);
                    break;
                }
            }
        }
        return $columns;
    }

    /**
    * Capture and return echoed output
    * 
    * @param callback $func Parameter 
    * @since 1.0
    * @return unknown Return 
    */
    function at_responsive_ob_capture($func) {
        $numargs = func_num_args();
        $arg_list = array();
        if ($numargs >= 2) {
            $arg_list = func_get_args();
            $arg_list = array_slice($arg_list, 1, false);
        }
        ob_start();
        call_user_func_array($func, $arg_list);
        return ob_get_clean();
    }

    /**
    * Modifies a string to remove all non ASCII characters and spaces.
    * 
    * @param string $text Parameter 
    * @since 1.0
    * @return string  Return 
    */
    function at_responsive_slugify($text) {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        // trim
        $text = trim($text, '-');
        // transliterate
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }
        // lowercase
        $text = strtolower($text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }

    /* Change Slider Defaults */

    /**
    * Change Slider Defaults
    * 
    * @param array   $defaults Parameter 
    * @param int $post_id  Parameter 
    * @since 1.0
    * @return array   Return 
    */
    function at_responsive_soliloquy_set_defaults($defaults, $post_id) {

        // You can easily set default values here. See the get_config_defaults method in the includes/global/common.php file for all available defaults to modify (around L250).
        // In this example, we will modify the default slider size to 3000 x 1000.
        $defaults['slider_size'] = 'full_width';
        $defaults['slider_width'] = 0;
        // $defaults['slider_height'] = 1000;
        // Return the modified defaults.
        return $defaults;
    }

    /**
    * Close comments on page(s)
    * 
    * @param boolean $open    Parameter 
    * @param int $post_id Parameter 
    * @since 1.0
    * @return boolean Return 
    */
    function at_responsive_close_page_comments($open, $post_id) {

        $post = get_post($post_id);

        if ('page' == $post->post_type)
            $open = false;

        return $open;
    }

    /**
    * Add img-responsive class to inline images
    * 
    * Replaced Regex substitution with more stable DOM parsing -- Akin Williams 050715
    * 
    * @param string $content Parameter 
    * @since 1.0
    * @return unknown Return 
    */
    function at_responsive_filter_singular_images($content) {
        libxml_use_internal_errors(true);
        $new_content = $content;
        if (is_singular() && is_main_query()) {
            $doc = new DOMDocument();
            $doc->loadHTML('<?xml encoding="utf-8" ?>' . $content); // Added UTF character encoding -- Akin Williams 051115
            $images = $doc->getElementsByTagName('img');
            foreach ($images as $img) {
                $class = $img->getAttribute('class') ?: "";
                if (strpos($class, 'img-responsive') === false)
                    $img->setAttribute('class', $class . " img-responsive");
            }
            $new_content = $doc->saveHTML();
        }
        libxml_clear_errors();
        return $new_content;
    }

    /**
    * Generate Breadcrumbs
    * 
    * @since 1.0
    * @return void 
    */
    function at_responsive_breadcrumb() {
        global $post;
        $grid_values = at_responsive_get_content_grid_values();
        $grid_classes = at_responsive_get_content_grid_classes();
        echo '<ul id="breadcrumbs" class="col-md-' . $grid_values['row'] . ' ' . $grid_classes['row'] . '">';
        if (!is_home() && !is_singular()) {
            echo '<li><a href="';
            echo get_option('home');
            echo '">';
            echo 'Home';
            echo '</a></li><li class="separator"> / </li>';
            if (is_category()) {
                echo '<li>';
                the_category(' </li><li class="separator"> / </li><li> ');
            }
        } elseif (is_tag()) {
            single_tag_title();
        } elseif (is_day()) {
            echo"<li>Archive for ";
            the_time('F jS, Y');
            echo'</li>';
        } elseif (is_month()) {
            echo"<li>Archive for ";
            the_time('F, Y');
            echo'</li>';
        } elseif (is_year()) {
            echo"<li>Archive for ";
            the_time('Y');
            echo'</li>';
        } elseif (is_author()) {
            echo"<li>Author Archive";
            echo'</li>';
        } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
            echo "<li>Blog Archives";
            echo'</li>';
        } elseif (is_search()) {
            echo"<li>Search Results";
            echo'</li>';
        }
        echo '</ul>';
    }

    /**
    * Get The Excerpt By Id
    * 
    * @param int $post_id Parameter 
    * @since 1.0
    * @return boolean Return 
    */
    function at_responsive_get_excerpt($post_id) {
        global $post;
        $excerpt = false;
        $temp = clone $post;
        $post = get_post($post_id);
        setup_postdata($post);
        $excerpt = get_the_excerpt();
        wp_reset_postdata();
        $post = clone $temp;
        return $excerpt;
    }

    /**
    * Add unique Identifier to POST variable
    * 
    * @param array $pieces 
    *                       
    * @since 1.0
    * @return array $pieces 
    */
    function at_responsive_post_query_clauses($pieces) {
        $uniqid = uniqid();
        $pieces['fields'] .= ", '{$uniqid}' AS uniqID";
        return $pieces;
    }

    /**
    * Add default favicon image URL
    * 
    * @param string $default 
    *                       
    * @since 1.0
    * @return string $default 
    */
    function at_responsive_theme_mod_default_favicon($default) {
        return template_url . '/lib/assets/img/favicon.png'; 
    }


    // add_action('wp_footer', array('at_responsive_theme_mod', 'fbpage2id'));
