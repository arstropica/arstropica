<?php
    /**
    * ArsTropica  Responsive Framework options.php
    * 
    * PHP version 5
    * 
    * @category   Theme Framework Class 
    * @package    WordPress
    * @author     ArsTropica <info@arstropica.com> 
    * @copyright  2014 ArsTropica 
    * @license    http://opensource.org/licenses/gpl-license.php GNU Public License 
    * @version    1.0 
    * @link       http://pear.php.net/package/ArsTropica  Reponsive Framework
    * @subpackage ArsTropica  Responsive Framework
    * @see        References to other sections (if any)...
    */

    /**
    * ArsTropica  Reponsive Framework
    Theme Options
    * 
    * @category   Theme Framework Class 
    * @package    WordPress
    * @author     ArsTropica <info@arstropica.com> 
    * @copyright  2014 ArsTropica 
    * @license    http://opensource.org/licenses/gpl-license.php GNU Public License 
    * @version    Release: @package_version@ 
    * @link       http://pear.php.net/package/ArsTropica  Reponsive Framework
    * @subpackage ArsTropica  Responsive Framework
    * @see        References to other sections (if any)...
    */
    class at_responsive_theme_options {

        /**
        * Options
        * @var unknown 
        * @access private 
        */
        private $options;

        /**
        * Options Sections
        * @var array   
        * @access private 
        */
        private $option_sections;

        /**
        * Description for public
        * @var unknown 
        * @access public  
        */
        public $options_page;

        /**
        * Menu Slug
        * @var string 
        * @access public 
        */
        public $menu_slug = 'at-theme-control';

        /**
        * Google API Key (for Google Fonts)
        * @var string 
        * @access public 
        */
        public $google_api_key;

        /**
        * Image Controls
        * @var array  
        * @access public 
        */
        public $image_controls;

        /**
        * Preset Controls
        * @var array  
        * @access public 
        */
        public $preset_controls;

        /**
        * Transfer Controls
        * @var array  
        * @access public 
        */
        public $transfer_controls;

        /**
        * Transport
        * @var string 
        * @access public 
        */
        public $transport = 'refresh'; // 'PostMessage';

        /**
        * Option Type
        * @var string 
        * @access public 
        */
        public $option_type = 'theme_mod';

        /**
        * Is Demo
        * @var string 
        * @access public 
        */
        public $isdemo;

        /**
        * Start up
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function __construct() {
            $this->isdemo = stristr($_SERVER['REQUEST_URI'], 'at_preview');
            $this->build_options();
            $this->set_google_api();
            add_action('admin_menu', array($this, 'add_plugin_page'));
            add_action('customize_register', array($this, 'page_init'));
            add_action('customize_controls_enqueue_scripts', array($this, 'add_scripts'));
            add_action('customize_controls_print_scripts', array($this, 'print_scripts'));
            add_action('customize_controls_print_styles', array($this, 'customize_styles'));
            add_action("customize_controls_print_footer_scripts", array($this, 'email_tab_modal'));
            add_action('customize_controls_print_footer_scripts', array($this, 'add_media_manager_template_to_customizer'));
            add_action('customize_controls_print_footer_scripts', array($this, 'print_footer_scripts'));
            add_action('load-admin.php', array($this, 'admin_redirect_download_files'));
            add_action('load-admin.php', array($this, 'admin_email_download_files'));
            add_filter('getimagesize_mimes_to_exts', array($this, 'add_ico_mime'), 99999);
            add_filter('upload_mimes', array($this, 'add_ico_ext'), 99999);
            add_filter('getimagesize_mimes_to_exts', array($this, 'add_motif_mime'), 99999);
            add_filter('upload_mimes', array($this, 'add_motif_ext'), 99999);
            add_filter('query_vars', array($this, 'add_export_query_vars'));
        }

        /**
        * Build Variables
        * 
        * Each key must be unique.
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function build_options() {
            $settings = array('@info' => 'Theme Settings', '@desc' => 'Change Theme Settings.', '@priority' => 0);
            $settings['googleapi'] = 'Google API Key';
            $settings['enabledemo'] = 'Enable Front End Theme Customizer for Guests';
            $settings['schemes'] = 'Choose from preset themed color schemes (this will override all theme styling)';
            $settings['stickynav'] = 'Enable Sticky Navigation';
            $settings['widgetplaceholders'] = 'Show Widget Placeholders';
            $settings['slider'] = 'Choose Header Slider';
            $settings['excerptlength'] = 'Number of Words in Excerpt';
            $settings['homeexcerptlength'] = 'Number of Words in Excerpt on Homepage';
            $settings['homepostsperpage'] = 'Number of Posts on Homepage';
            $settings['postsperpage'] = 'Number of Posts per Page';
            $settings['enableyarpp'] = 'Display Related Posts (requires YARPP plugin)';

            $info = array('@info' => 'Company Information', '@desc' => 'Enter Company Information.', '@priority' => 10);
            $info['phonenumber'] = 'Phone Number';
            $info['address'] = 'Company Address';

            $social = array('@info' => 'Social Media', '@desc' => 'Enter Social Media URLs <br>Note: URLs are used in Theme Social Widget.', '@priority' => 20);
            $social['widget'] = 'Enable In-built Theme Social Widget?';
            $social['icons'] = 'Social Icons';

            $seo = array('@info' => 'Theme SEO', '@desc' => 'Configure Theme SEO.', '@priority' => 30);
            $seo['minifyassets'] = 'Minify Theme Assets (for faster loading)';
            $seo['googlepublisher'] = 'Google+ Publisher URL.';
            $seo['googleauthor'] = 'Google+ Author URL (Global Override).';
            $seo['googleverify'] = 'Validate Google Authorship.';
            $seo['facebookpage'] = 'Facebook Page URL (Open Graph SEO)';

            // $plugins = array('@info' => 'Theme Plugins', '@desc' => 'Configure Theme Plugins.', '@priority' => 20);
            $plugins['contactform'] = array('@info' => 'Mobile Contact Form', '@desc' => 'Configure Mobile Contact Form.', '@priority' => 40);
            $plugins['contactform']['enablecontactform'] = 'Enable Mobile Contact Form';
            $plugins['contactform']['contactformpage'] = 'Display on Page';

            $appearance['elements']['navigation'] = array('@info' => 'Navigation Appearance', '@desc' => 'Customize Theme Navigation.', '@priority' => 80);
            $appearance['elements']['navigation']['background'] = 'Navigation Background Color';
            $appearance['elements']['navigation']['border'] = 'Navigation Border';
            $appearance['elements']['navigation']['dropdown'] = 'Sub-Nav Background Color';
            $appearance['elements']['navigation']['icons'] = 'Navigation Icons';
            $appearance['elements']['navigation']['fonts'] = 'Navigation Fonts';
            $appearance['elements']['navigation']['links'] = 'Navigation Links';
            $appearance['elements']['site'] = array('@info' => 'Sitewide Appearance', '@desc' => 'Customize Site-level elements.', '@priority' => 70);
            $appearance['elements']['site']['fonts'] = 'Site Fonts';
            $appearance['elements']['site']['links'] = 'Site Links';
            $appearance['elements']['site']['background'] = 'Site Background';
            $appearance['elements']['site']['backgroundimage'] = 'Site Background Image';
            $appearance['elements']['site']['border'] = 'Site Border';
            $appearance['elements']['content'] = array('@info' => 'Content Appearance', '@desc' => 'Customize Content appearance.', '@priority' => 90);
            $appearance['elements']['content']['entryheader'] = 'Header Background';
            $appearance['elements']['content']['background'] = 'Content Background';
            $appearance['elements']['content']['postmeta'] = 'Post Meta Background';
            $appearance['elements']['content']['border'] = 'Content Border';
            $appearance['elements']['content']['fonts'] = 'Content Fonts';
            $appearance['elements']['content']['links'] = 'Content Links';
            $appearance['elements']['content']['icons'] = 'Sharing Icons';
            $appearance['elements']['content']['readmore'] = 'Read More Button';
            $appearance['elements']['content']['share'] = 'Share Button';
            $appearance['elements']['widgets'] = array('@info' => 'Widget Appearance', '@desc' => 'Customize Widgets appearance.', '@priority' => 100);
            $appearance['elements']['widgets']['background'] = 'Widget Background';
            $appearance['elements']['widgets']['border'] = 'Widget Border';
            $appearance['elements']['widgets']['heading'] = 'Widget Heading Background';
            $appearance['elements']['widgets']['fonts'] = 'Widget Fonts';
            $appearance['elements']['widgets']['links'] = 'Widget Links';
            $appearance['elements']['footer'] = array('@info' => 'Footer Appearance', '@desc' => 'Customize Footer appearance.', '@priority' => 110);
            $appearance['elements']['footer']['background'] = 'Footer Background';
            $appearance['elements']['footer']['border'] = 'Footer Border';
            $appearance['elements']['footer']['fonts'] = 'Footer Fonts';
            $appearance['elements']['footer']['links'] = 'Footer Links';

            $appearance['grid'] = 'Grid Layout';

            $css = array('@info' => 'Custom CSS', '@desc' => 'Add Custom CSS to the blog.', '@priority' => 120);
            $css['customcss'] = 'Include Custom CSS Rules';

            $images = array('@info' => 'Theme Images', '@desc' => 'Upload / Select Default Images.', '@priority' => 130);
            $images['favicon'] = 'Site Favicon';
            $images['companylogo'] = 'Company Logo';
            $images['defaultimg'] = 'Default Thumbnail Image';

            $this->option_sections = array(
            'info' => $info,
            'plugins' => $plugins,
            'settings' => $settings,
            'images' => $images,
            'social' => $social,
            'seo' => $seo,
            'appearance' => $appearance,
            'css' => $css,
            );
        }

        /**
        * Add options page
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function add_plugin_page() {
            global $theme_namespace;
            $theme_details = wp_get_theme();
            $theme_name = $theme_details->get('Name');

            /* Add Main Menu Item */
            $this->options_page = add_menu_page(__($theme_name . " Settings", $theme_namespace), __("Theme Settings", $theme_namespace), "edit_theme_options", 'customize.php', '', template_url . '/lib/assets/img/at_logo_16_t.png', 50.0999);
        }

        /**
        * Options page callback
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function create_admin_page() {
            // Set class property
            $this->theme_info = get_option('at_theme_info');
            $this->theme_options = get_option('at_theme_options');
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>Theme Settings</h2>
            <?php
                if (isset($_GET['tab'])) {
                    $active_tab = $_GET['tab'];
                } else {
                    $active_tab = 'theme_info';
                }
                $active_settings_section = "at_{$active_tab}_page";
                $active_settings_group = "at_{$active_tab}_group";
            ?>
            <h2 class="nav-tab-wrapper">
                <?php foreach ($this->option_sections as $option => $section) : ?>
                    <a href="?page=at-theme-control&tab=theme_<?php echo $option; ?>" class="nav-tab <?php echo ($active_tab == 'theme_' . $option) ? 'nav-tab-active' : ''; ?>"><?php echo $section['@info']; ?></a>
                    <?php endforeach; ?>
            </h2>
            <style>
                label.option-title {
                    display: block;
                    font-size: 12px;
                    padding: 10px 0;
                }
                #custom_css {
                    width: 100%;
                    min-height: 350px;
                }
            </style>
            <form method="post" action="options.php">
                <?php
                    do_settings_sections($active_settings_section);
                    settings_fields($active_settings_group);
                    submit_button();
                ?>
            </form>

        </div>
        <?php
        }

        /**
        * Set Google API Key
        * 
        * @since 1.0
        * @return unknown Return 
        * @access public  
        */
        public function set_google_api()
        {
            global $at_theme_custom;
            
            if (! is_object($at_theme_custom)) {
                
                $at_theme_custom = new at_responsive_theme_mod();
                
            }
            
            $this->google_api_key = $at_theme_custom->get_option('settings/googleapi', false, $at_theme_custom->is_customizer());
            
        }

        /**
        * Register and add settings
        * 
        * @param object  $wp_customize Parameter 
        * @since 1.0
        * @return unknown Return 
        * @access public  
        */
        public function page_init($wp_customize) {
            global $theme_namespace;
            // if (!is_admin())
                // return;
            // error_reporting(E_ERROR);
            require(template_directory . '/lib/classes/class-controls.php');
            $sections = $this->option_sections;
            $this->image_controls = array();
            $this->transfer_controls = array();

            $sidebars_widgets = array_merge(
            array('wp_inactive_widgets' => array()), array_fill_keys(array_keys($GLOBALS['wp_registered_sidebars']), array()), wp_get_sidebars_widgets()
            );
            $wp_customize->remove_section('title_tagline');
            $wp_customize->remove_section('static_front_page');
            $wp_customize->remove_section('nav');
            foreach ($sidebars_widgets as $sidebar_id => $sidebar_widget_ids) {
                $section_id = sprintf('sidebar-widgets-%s', $sidebar_id);
                $wp_customize->remove_section($section_id);
            }
            /* Reset Button */
            $this->add_reset_control(0);

            /* Import Export Section */
            $this->transfer_controls["transfer_options"] = $this->add_transfer_control(0);

            foreach ($sections as $slug => $section) {
                $this->recursive_declare_fields($wp_customize, $section, $slug, $slug, $slug, true);
            }
            if ($this->image_controls) {
                foreach ($this->image_controls as $idx => $control) {
                    $control->add_tab('library', __('Media Library', $theme_namespace), array($this, "mltab_{$idx}"));
                }
            }
            if ($this->preset_controls) {
                foreach ($this->preset_controls as $idx => $control) {
                    $control->add_tab('styles', __('Style Editor', $theme_namespace), array($this, "presettab_{$idx}"));
                }
            }
            if ($this->transfer_controls) {
                foreach ($this->transfer_controls as $idx => $control) {
                    if (! $this->isdemo) $control->add_tab('import', __('Import Theme Settings', $theme_namespace), array($this, "importtab_{$idx}"));
                    $control->add_tab('export', __('Export Theme Settings', $theme_namespace), array($this, "exporttab_{$idx}"));
                    $control->add_tab('email', __('Email Theme Settings', $theme_namespace), array($this, "emailtab_{$idx}"));
                }
            }
        }

        /**
        * Recursively declare settings
        * 
        * @param object  $wp_customize Parameter 
        * @param array   $sections     Parameter 
        * @param boolean $page_id      Parameter 
        * @param boolean $section_id   Parameter 
        * @param mixed   $field_id     Parameter 
        * @param boolean $init         Parameter 
        * @since 1.0
        * @return void    
        * @access public  
        */
        public function recursive_declare_fields($wp_customize, $sections = array(), $page_id = false, $section_id = false, $field_id = false, $init = false) {
            global $theme_namespace;
            if ($sections && $page_id && $section_id && $field_id) {
                if (is_array($sections)) {
                    if (isset($sections['@info'])) {
                        if (!$init)
                            $field_id = "{$section_id}_{$field_id}";
                        $section_args = array(
                        'title' => __($sections['@info'], $theme_namespace),
                        );
                        if (isset($sections['@desc']))
                            $section_args['description'] = __($sections['@desc'], $theme_namespace);
                        if (isset($sections['@priority']))
                            $section_args['priority'] = $sections['@priority'];
                        $wp_customize->add_section("at_theme_{$field_id}_section_id", $section_args);
                    }

                    foreach ($sections as $_field_id => $_section) {
                        if (!in_array($_field_id, array('@desc', '@info', '@priority'))) {
                            $this->recursive_declare_fields($wp_customize, $_section, $page_id, $field_id, $_field_id);
                        }
                    }
                } else {
                    @list($category, $region, $element) = explode("_", $section_id);
                    $options = array_filter(explode("_", $section_id));
                    $setting_id = "at_responsive" . (empty($options) ? "" : "[" . implode("][", $options) . "]") . "[{$field_id}]";

                    switch ($field_id) {
                        case 'googleapi' : {
                            $this->add_default_control($setting_id, $section_id, $sections, 'text', '', 1);
                            break;
                        }
                        case 'enabledemo' : {
                            if ($this->isdemo)
                                $this->add_hidden_control($setting_id, $section_id, $sections, true, 2);
                            else
                                $this->add_default_control($setting_id, $section_id, $sections, 'checkbox', false, 2);
                            break;
                        }
                        case 'grid' : {
                            $this->add_hidden_control($setting_id, $section_id, $sections, true, 3);
                            break;
                        }
                        case 'schemes' : {
                            $this->preset_controls["{$section_id}_{$field_id}"] = $this->add_preset_control($setting_id, $section_id, $sections, 3);
                            break;
                        }
                        case 'enablecontactform' : {
                            $this->add_default_control($setting_id, $section_id, $sections, 'checkbox', true, 5);
                            break;
                        }
                        case 'contactformpage' : {
                            $_pages = array(0 => 'Select Page');
                            $objs = get_pages();
                            foreach ($objs as $obj)
                                $_pages[$obj->ID] = $obj->post_title;
                            $pages = array(
                            'choices' => $_pages
                            );
                            $this->add_default_control($setting_id, $section_id, $sections, 'select', 0, 6, $pages);
                            break;
                        }
                        case 'slider' : {
                            $sliders = at_responsive_get_sliders();
                            if ($sliders) {
                                $choices = array(
                                'choices' => $sliders
                                );
                                $this->add_default_control($setting_id, $section_id, $sections, 'select', 'header-slider', 4, $choices);
                            }
                            break;
                        }
                        case 'layout' : {
                            $choices = array('choices' => array('fullwidth' => 'Full Width', 'left' => 'Sidebar - Content', 'right' => 'Content - Sidebar', 'nosidebar' => 'No Sidebars'));
                            $this->add_default_control($setting_id, $section_id, $sections, 'select', 'fullwidth', 4, $choices);
                            break;
                        }
                        case 'excerptlength' : {
                            $this->add_default_control($setting_id, $section_id, $sections, 'text', 15, 5);
                            break;
                        }
                        case 'homeexcerptlength' : {
                            $this->add_default_control($setting_id, $section_id, $sections, 'text', 55, 5);
                            break;
                        }
                        case 'homepostsperpage' : {
                            $this->add_default_control($setting_id, $section_id, $sections, 'text', 3, 6);
                            break;
                        }
                        case 'postsperpage' : {
                            $this->add_default_control($setting_id, $section_id, $sections, 'text', 9, 7);
                            break;
                        }
                        case 'widgetplaceholders' : {
                            $this->add_default_control($setting_id, $section_id, $sections, 'checkbox', true, 8);
                            break;
                        }
                        case 'stickynav' : {
                            $this->add_default_control($setting_id, $section_id, $sections, 'checkbox', false, 9);
                            break;
                        }
                        case 'enableyarpp' : {
                            $this->add_default_control($setting_id, $section_id, $sections, 'checkbox', false, 10);
                            break;
                        }
                        case 'icons' : {
                            $this->add_links_control($setting_id, $section_id, $sections, false, 10);
                            break;
                        }
                        case 'fonts' : {
                            $this->add_font_control($setting_id, $section_id, $sections, 20);
                            break;
                        }
                        case 'links' : {
                            $this->add_links_control($setting_id, $section_id, $sections, true, 15);
                            break;
                        }
                        case 'entryheader' : {
                            $this->add_color_control($setting_id, $section_id, $sections, 25);
                            break;
                        }
                        case 'background' : {
                            switch ($region) {
                                case 'navigation' : {
                                    $this->add_links_control($setting_id, $section_id, $sections, false, 25);
                                    break;
                                }
                                default : {
                                    $this->add_color_control($setting_id, $section_id, $sections, 30);
                                    break;
                                }
                            }
                            break;
                        }
                        case 'postmeta' : {
                            $this->add_color_control($setting_id, $section_id, $sections, 32);
                            break;
                        }
                        case 'dropdown' : {
                            $this->add_links_control($setting_id, $section_id, $sections, false, 30);
                            break;
                        }
                        case 'backgroundimage' : {
                            $this->image_controls["{$section_id}_{$field_id}"] = $this->add_image_control($setting_id, $section_id, $sections, 35);
                            break;
                        }
                        case 'share' :
                        case 'readmore' : {
                            $priority = ($field_id == 'share') ? 35 : 40;
                            $this->add_links_control($setting_id, $section_id, $sections, false, $priority);
                            break;
                        }
                        case 'heading' : {
                            $this->add_color_control($setting_id, $section_id, $sections, 45);
                            break;
                        }
                        case 'phonenumber' : {
                            $this->add_default_control($setting_id, $section_id, $sections, 'text', '', 50, array('sanitize_callback' => array($this, "sanitize_text_phone")));
                            break;
                        }
                        case 'address' : {
                            $this->add_address_control($setting_id, $section_id, $sections, true, 55);
                            break;
                        }
                        case 'companylogo' :
                        case 'defaultimg' : {
                            $this->image_controls["{$section_id}_{$field_id}"] = $this->add_image_control($setting_id, $section_id, $sections, 60);
                            break;
                        }
                        case 'widget' : {
                            $this->add_social_control($setting_id, $section_id, $sections, 70);
                            break;
                        }
                        case 'favicon' : {
                            $this->add_favicon_control($setting_id, $section_id, $sections, 80);
                            break;
                        }
                        case 'minifyassets' : {
                            $this->add_default_control($setting_id, $section_id, $sections, 'checkbox', true, 85);
                            break;
                        }
                        case 'googlepublisher' : {
                            $this->add_default_control($setting_id, $section_id, $sections, 'text', '', 90, array('sanitize_callback' => array($this, "sanitize_url")));
                            break;
                        }
                        case 'googleauthor' : {
                            $this->add_default_control($setting_id, $section_id, $sections, 'text', '', 95, array('sanitize_callback' => array($this, "sanitize_url")));
                            break;
                        }
                        case 'googleverify' : {
                            $this->add_gplus_control($setting_id, $section_id, $sections, 100);
                            break;
                        }
                        case 'facebookpage' : {
                            $this->add_default_control($setting_id, $section_id, $sections, 'text', '', 105, array('sanitize_callback' => array($this, "sanitize_url")));
                            break;
                        }
                        case 'border' : {
                            $this->add_links_control($setting_id, $section_id, $sections, false, 110);
                            break;
                        }
                        case 'customcss' : {
                            $this->add_textarea_control($setting_id, $section_id, $sections, '', 120);
                            break;
                        }
                    }
                }
            }
        }

        /**
        * Recursive Implode
        * 
        * @param mixed  $array Parameter 
        * @param string $glue  Parameter 
        * @since 1.0
        * @return string Return 
        * @access public 
        */
        public function multi_implode($array, $glue) {
            $ret = '';

            if (is_array($array)) {
                foreach ($array as $item) {
                    if (is_array($item)) {
                        $ret .= self::multi_implode($item, $glue) . $glue;
                    } else {
                        $ret .= $item . $glue;
                    }
                }
                $ret = substr($ret, 0, 0 - strlen($glue));
            } else {
                $ret .= $array;
            }

            return $ret;
        }

        /**
        * Return depth of array
        * 
        * @param array   $array Parameter 
        * @since 1.0
        * @return integer Return 
        * @access public  
        */
        public function array_depth($array) {
            $max_depth = 1;
            if (is_array($array) === false)
                return 0;
            foreach ($array as $value) {
                if (is_array($value)) {
                    $depth = (self::array_depth($value)) + 1;

                    if ($depth > $max_depth) {
                        $max_depth = $depth;
                    }
                }
            }
            return $max_depth;
        }

        /**
        * Add Multiple Address Setting Options
        * 
        * @param object  $wp_customize Parameter 
        * @param string $id           Parameter 
        * @param boolean $full         Parameter 
        * @since 1.0
        * @return void    
        * @access public  
        */
        public function address_add_setting($wp_customize, $id, $full = true) {
            $wp_customize->add_setting($id, array(
            'type' => $this->option_type,
            'capability' => 'edit_theme_options',
            'transport' => $this->transport,
            ));
            $lines = array('street', 'city', 'state', 'zip');
            foreach ($lines as $_line) {
                $setting_id = "{$id}[{$_line}]";
                $wp_customize->add_setting($setting_id, array(
                'type' => $this->option_type,
                'sanitize_callback' => array($this, "sanitize_address_{$_line}"),
                'transport' => $this->transport,
                'capability' => 'edit_theme_options',
                ));
            }
        }

        /**
        * Handle Image Uploader
        * 
        * @param string  $setting_id Parameter 
        * @param string $section_id Section ID
        * @param string  $label      Parameter 
        * @param integer $priority   Parameter 
        * @since 1.0
        * @return object  Return 
        * @access public  
        */
        public function add_image_control($setting_id, $section_id, $label = '', $priority = 0) {
            global $wp_customize, $theme_namespace;

            $setting_args = array(
            'type' => $this->option_type,
            'capability' => 'edit_theme_options',
            'transport' => $this->transport,
            );

            $default = false;
            $default = apply_filters("at_responsive_child_theme_mod_{$setting_id}", $default);

            if ($default) {
                $setting_args['default'] = $default;
            }

            if ($setting_id == 'at_responsive[images][favicon]')
                $setting_args['sanitize_callback'] = array($this, "sanitize_favicon");

            $wp_customize->add_setting($setting_id, $setting_args);

            $control_args = array(
            'label' => __($label, $theme_namespace),
            'section' => "at_theme_{$section_id}_section_id",
            'priority' => $priority,
            'settings' => $setting_id,
            'context' => null
            );
            switch ($setting_id) {
                case 'at_responsive[images][companylogo]' : {
                    $control_args['context'] = 'logos';
                    break;
                }
                case 'at_responsive[images][defaultimg]' : {
                    $control_args['context'] = 'default';
                    break;
                }
                default : {
                    $control_args['context'] = '';
                    break;
                }
            }
            $image_control = new AT_Image_Control($wp_customize, $setting_id, $control_args);
            $wp_customize->add_control($image_control);
            return $image_control;
        }

        /**
        * Handle Uploader
        * 
        * @param string $setting_id Parameter Setting ID
        * @param string $section_id Section ID
        * @param string  $label      Parameter 
        * @param integer $priority   Parameter 
        * @since 1.0
        * @return object  Return 
        * @access public  
        */
        public function add_favicon_control($setting_id, $section_id, $label = '', $priority = 0) {
            global $wp_customize, $theme_namespace;

            $setting_args = array(
            'type' => $this->option_type,
            'capability' => 'edit_theme_options',
            'transport' => $this->transport,
            'sanitize_callback' => array($this, "sanitize_favicon"),
            );

            $default = false;
            // Framework Favicon Filter
            $default = apply_filters("at_responsive_theme_mod_{$setting_id}", $default);
            // Child Theme Favicon Filter
            $default = apply_filters("at_responsive_child_theme_mod_{$setting_id}", $default);

            if ($default) {
                $setting_args['default'] = $default;
            }

            $wp_customize->add_setting($setting_id, $setting_args);
            $favicon_control = new Favicon_Control($wp_customize, $setting_id, array(
            'label' => __($label, $theme_namespace),
            'section' => "at_theme_{$section_id}_section_id",
            'priority' => $priority,
            'settings' => $setting_id,
            'context' => 'favicon'
            ));
            $wp_customize->add_control($favicon_control);
            return $favicon_control;
        }

        /**
        * Handle Links
        * 
        * @param string $setting_id Parameter Setting ID
        * @param string $section_id Section ID
        * @param string  $label      Parameter 
        * @param boolean $full       Parameter 
        * @param integer $priority   Parameter 
        * @since 1.0
        * @return object  Return 
        * @access public  
        */
        public function add_links_control($setting_id, $section_id, $label = '', $full = true, $priority = 0) {
            global $wp_customize, $theme_namespace;

            $this->links_add_setting($wp_customize, $setting_id, $full);
            $links_control = new Links_Control($wp_customize, $setting_id, array(
            'label' => __($label, $theme_namespace),
            'section' => "at_theme_{$section_id}_section_id",
            'settings' => $setting_id,
            'priority' => $priority,
            'full' => $full,
            ));
            $wp_customize->add_control($links_control);
            return $links_control;
        }

        /**
        * Handle Social Media
        * 
        * @param string $id         Parameter 
        * @param string $section_id Section ID
        * @param string  $label      Parameter 
        * @param integer $priority   Parameter 
        * @since 1.0
        * @return object  Return 
        * @access public  
        */
        public function add_social_control($id, $section_id, $label = '', $priority = 0) {
            global $wp_customize, $theme_namespace;

            $wp_customize->add_setting("{$id}[enable]", array(
            'type' => $this->option_type,
            'capability' => 'edit_theme_options',
            'transport' => $this->transport,
            ));
            $profiles = array('Facebook', 'Twitter', 'LinkedIn', 'Google', 'Pinterest', 'Houzz', 'YouTube', 'Vimeo', 'Instagram', 'Flickr', 'RSS', 'Website');
            foreach ($profiles as $_profile) {
                $setting_id = "{$id}[{$_profile}]";
                $args = array(
                'type' => $this->option_type,
                'sanitize_callback' => array($this, "sanitize_url"),
                'capability' => 'edit_theme_options',
                'transport' => $this->transport,
                );
                if ($_profile == "RSS")
                    $args['default'] = get_bloginfo('rss2_url');
                $wp_customize->add_setting($setting_id, $args);
            }
            $social_control = new Social_Control($wp_customize, $id, array(
            'label' => __($label, $theme_namespace),
            'section' => "at_theme_{$section_id}_section_id",
            'priority' => $priority,
            'settings' => "{$id}[enable]",
            'type' => 'checkbox',
            ));
            $wp_customize->add_control($social_control);
            return $social_control;
        }

        /**
        * Handle Color Control
        * 
        * @param string $setting_id Parameter Setting ID
        * @param string $section_id Section ID
        * @param string  $label      Parameter 
        * @param integer $priority   Parameter 
        * @since 1.0
        * @return object  Return 
        * @access public  
        */
        public function add_color_control($setting_id, $section_id, $label = '', $priority = 0) {
            global $wp_customize, $theme_namespace;

            $wp_customize->add_setting($setting_id, array(
            'type' => $this->option_type,
            'sanitize_callback' => array($this, "sanitize_hex_color"),
            'transport' => $this->transport,
            'capability' => 'edit_theme_options',
            ));
            $color_control = new WP_Customize_Color_Control($wp_customize, $setting_id, array(
            'label' => __($label, $theme_namespace),
            'section' => "at_theme_{$section_id}_section_id",
            'settings' => $setting_id,
            'priority' => $priority,
            ));
            $wp_customize->add_control($color_control);

            return $color_control;
        }

        /**
        * Handle Reset Control
        * 
        * @param integer $priority Parameter 
        * @since 1.0
        * @return object  Return 
        * @access public  
        */
        public function add_reset_control($priority = 0) {
            global $wp_customize, $theme_namespace;

            $section_args = array(
            'title' => __('Reset Theme Options', $theme_namespace),
            );
            $section_args['description'] = __('Reset all Theme Options', $theme_namespace);
            $section_args['priority'] = 200;
            $wp_customize->add_section("at_theme_reset_section_id", $section_args);

            $setting_id = 'at_responsive_reset_control';

            $wp_customize->add_setting($setting_id, array(
            'type' => 'button',
            'capability' => 'edit_theme_options',
            'transport' => 'postMessage',
            ));
            $reset_control = new Reset_Control($wp_customize, $setting_id, array(
            'section' => "at_theme_reset_section_id",
            'settings' => $setting_id,
            'priority' => $priority,
            ));
            $wp_customize->add_control($reset_control);

            return $reset_control;
        }

        /**
        * Handle Transfer Control
        * 
        * @param integer $priority Parameter 
        * @since 1.0
        * @return object  Return 
        * @access public  
        */
        public function add_transfer_control($priority = 0) {
            global $wp_customize, $theme_namespace;

            $section_args = array(
            'title' => __('Import / Export', $theme_namespace),
            );
            $section_args['description'] = __('Import / Export Theme Options', $theme_namespace);
            $section_args['priority'] = 199;
            $wp_customize->add_section("at_theme_transfer_section_id", $section_args);

            $setting_id = 'at_responsive_transfer_control';

            $wp_customize->add_setting($setting_id, array(
            'type' => 'upload',
            'capability' => 'edit_theme_options',
            'transport' => 'postMessage',
            ));
            $transfer_control = new Transfer_Control($wp_customize, $setting_id, array(
            'section' => "at_theme_transfer_section_id",
            'settings' => $setting_id,
            'priority' => $priority,
            ));
            $wp_customize->add_control($transfer_control);

            return $transfer_control;
        }

        /**
        * Handle GPlus Validation
        * 
        * @param string $setting_id Parameter Setting ID
        * @param string $section_id Section ID
        * @param string  $label      Parameter 
        * @param integer $priority   Parameter 
        * @since 1.0
        * @return object  Return 
        * @access public  
        */
        public function add_gplus_control($setting_id, $section_id, $label = '', $priority = 0) {
            global $wp_customize, $theme_namespace;

            $sanitize_callback = array($this, "sanitize_url");
            $wp_customize->add_setting($setting_id, array(
            'default' => '',
            'type' => $this->option_type,
            'sanitize_callback' => $sanitize_callback,
            'capability' => 'edit_theme_options',
            'transport' => $this->transport,
            ));

            $recent_posts = wp_get_recent_posts();
            $rposts = array();

            if ($recent_posts)
                foreach ($recent_posts as $rpost)
                    $rposts[esc_attr($rpost['post_title'])] = (get_permalink($rpost['ID']));

            $gplus_args = array(
            'settings' => $setting_id,
            'label' => __($label, $theme_namespace),
            'section' => "at_theme_{$section_id}_section_id",
            'type' => 'select',
            'priority' => $priority,
            'choices' => $rposts,
            );

            $gplus_control = new GPlus_Control($wp_customize, $setting_id, $gplus_args);

            $wp_customize->add_control($gplus_control);

            return $gplus_control;
        }

        /**
        * Handle Preset Control
        * 
        * @param string $setting_id Parameter Setting ID
        * @param string $section_id Section ID
        * @param string  $label      Parameter 
        * @param integer $priority   Parameter 
        * @since 1.0
        * @return object  Return 
        * @access public  
        */
        public function add_preset_control($setting_id, $section_id, $label = '', $priority = 0) {
            global $wp_customize, $theme_namespace;

            $presets_id = 'at_responsive[settings][presets]';

            $wp_customize->add_setting($setting_id, array(
            'type' => $this->option_type,
            'capability' => 'edit_theme_options',
            'transport' => $this->transport,
            ));

            $wp_customize->add_setting($presets_id, array(
            'type' => $this->option_type,
            'capability' => 'edit_theme_options',
            'transport' => $this->transport,
            ));

            $presets = array();
            $theme_options = get_theme_mod('at_responsive');
            if ($theme_options && isset($theme_options['settings']['presets'])) {
                $presets = json_decode(rawurldecode($theme_options['settings']['presets']), true);
            }

            $preset_args = array(
            'label' => __($label, $theme_namespace),
            'section' => "at_theme_{$section_id}_section_id",
            'settings' => $setting_id,
            'priority' => $priority,
            'choices' => $presets,
            );

            $preset_control = new Preset_Control($wp_customize, $setting_id, $preset_args);
            $wp_customize->add_control($preset_control);

            $styles_args = array(
            'section' => "at_theme_{$section_id}_section_id",
            'settings' => $presets_id,
            'priority' => $priority,
            );

            $styles_control = new Styles_Control($wp_customize, $presets_id, $styles_args);
            $wp_customize->add_control($styles_control);

            return $preset_control;
        }

        /**
        * Handle Default Control
        * 
        * @param string $setting_id Parameter Setting ID
        * @param string $section_id Section ID
        * @param string  $label      Parameter 
        * @param string  $type       Parameter 
        * @param string  $default    Parameter 
        * @param integer $priority   Parameter 
        * @param array   $extra_args Parameter Additional Arguments
        * @since 1.0
        * @return object  Return 
        * @access public  
        */
        public function add_default_control($setting_id, $section_id, $label = '', $type = 'text', $default = '', $priority = 0, $extra_args = array()) {
            global $wp_customize, $theme_namespace;

            $sanitize_callback = array($this, "sanitize_{$type}");
            if ($extra_args && isset($extra_args['sanitize_callback'])) {
                $sanitize_callback = $extra_args['sanitize_callback'];
                unset($extra_args['sanitize_callback']);
            }

            $default = apply_filters("at_responsive_child_theme_mod_{$setting_id}", $default);

            $wp_customize->add_setting($setting_id, array(
            'default' => $default,
            'type' => $this->option_type,
            'sanitize_callback' => $sanitize_callback,
            'transport' => $this->transport,
            'capability' => 'edit_theme_options',
            ));
            $args = array_merge(array(
            'settings' => $setting_id,
            'label' => __($label, $theme_namespace),
            'section' => "at_theme_{$section_id}_section_id",
            'type' => $type,
            'priority' => $priority,
            ), $extra_args);

            $default_control = new WP_Customize_Control(
            $wp_customize, $setting_id, $args
            );
            $wp_customize->add_control($default_control);

            return $default_control;
        }

        /**
        * Handle Hidden Control
        * 
        * @param string $setting_id Parameter Setting ID
        * @param string $section_id Section ID
        * @param string  $label      Parameter 
        * @param string  $type       Parameter 
        * @param string  $default    Parameter 
        * @param integer $priority   Parameter 
        * @param array   $extra_args Parameter Additional Arguments
        * @since 1.0
        * @return object  Return 
        * @access public  
        */
        public function add_hidden_control($setting_id, $section_id, $label = '', $default = '', $priority = 0) {
            global $wp_customize, $theme_namespace;

            $default = apply_filters("at_responsive_theme_mod_{$setting_id}", $default);
            $default = apply_filters("at_responsive_child_theme_mod_{$setting_id}", $default);

            $wp_customize->add_setting($setting_id, array(
            'default' => $default,
            'type' => $this->option_type,
            'transport' => $this->transport,
            'capability' => 'edit_theme_options',
            ));
            $args = array(
            'settings' => $setting_id,
            'label' => __($label, $theme_namespace),
            'section' => "at_theme_{$section_id}_section_id",
            'type' => 'hidden',
            'priority' => $priority,
            );

            $hidden_control = new Hidden_Control(
            $wp_customize, $setting_id, $args
            );
            $wp_customize->add_control($hidden_control);

            return $hidden_control;
        }

        /**
        * Handle Textarea Control
        * 
        * @param string $setting_id Parameter Setting ID
        * @param string $section_id Section ID
        * @param string  $label      Parameter 
        * @param string  $default    Parameter 
        * @param integer $priority   Parameter 
        * @since 1.0
        * @return object  Return 
        * @access public  
        */
        public function add_textarea_control($setting_id, $section_id, $label = '', $default = '', $priority = 0) {
            global $wp_customize, $theme_namespace;

            $sanitize_callback = array($this, "sanitize_textarea");

            $wp_customize->add_setting($setting_id, array(
            'default' => $default,
            'type' => $this->option_type,
            'sanitize_callback' => $sanitize_callback,
            'transport' => $this->transport,
            'capability' => 'edit_theme_options',
            ));
            $args = array(
            'settings' => $setting_id,
            'label' => __($label, $theme_namespace),
            'section' => "at_theme_{$section_id}_section_id",
            'type' => 'textarea',
            'priority' => $priority,
            );

            $default_control = new TextArea_Control(
            $wp_customize, $setting_id, $args
            );
            $wp_customize->add_control($default_control);

            return $default_control;
        }

        /**
        * Handle Address Control
        * 
        * @param string $setting_id Parameter Setting ID
        * @param string $section_id Section ID
        * @param string  $label      Parameter 
        * @param boolean $full       Parameter 
        * @param integer $priority   Parameter 
        * @since 1.0
        * @return object  Return 
        * @access public  
        */
        public function add_address_control($setting_id, $section_id, $label = '', $full = true, $priority = 0) {
            global $wp_customize, $theme_namespace;

            $this->address_add_setting($wp_customize, $setting_id);
            $address_control = new Address_Control($wp_customize, $setting_id, array(
            'label' => __($label, $theme_namespace),
            'section' => "at_theme_{$section_id}_section_id",
            'settings' => $setting_id,
            'priority' => $priority,
            'full' => $full,
            ));
            $wp_customize->add_control($address_control);

            return $address_control;
        }

        /**
        * Handle Font Control
        * 
        * @param string $setting_id Parameter Setting ID
        * @param string $section_id Section ID
        * @param string  $label      Parameter 
        * @param integer $priority   Parameter 
        * @since 1.0
        * @return object  Return 
        * @access public  
        */
        public function add_font_control($setting_id, $section_id, $label = '', $priority = 0) {
            global $wp_customize, $theme_namespace;

            $this->font_add_setting($wp_customize, $setting_id);
            $font_control = new Fonts_Control($wp_customize, $setting_id, array(
            'label' => __($label, $theme_namespace),
            'section' => "at_theme_{$section_id}_section_id",
            'settings' => $setting_id,
            'priority' => $priority,
            ));
            $wp_customize->add_control($font_control);

            return $font_control;
        }

        /**
        * Media Library Tab Handler
        * 
        * @param string $ctrlstr Parameter 
        * @since 1.0
        * @return void    
        * @access private 
        */
        private function media_library_tab($ctrlstr) {
            $setting_id = "at_responsive[" . (implode('][', explode('_', $ctrlstr))) . ']';

            if (isset($this->image_controls[$ctrlstr])) :
                $control = $this->image_controls[$ctrlstr];
            ?>
            <a class="choose-from-library-link button"
                data-controller = "<?php esc_attr_e($setting_id); ?>">
                <?php _e('Open Library'); ?>
            </a>

            <?php
                endif;
        }

        /**
        * Preset Tab Handler
        * 
        * @param string $ctrlstr Parameter 
        * @since 1.0
        * @return void    
        * @access private 
        */
        private function preset_tab($ctrlstr) {
            $setting_id = "at_responsive[" . (implode('][', explode('_', $ctrlstr))) . ']';

            if (isset($this->preset_controls[$ctrlstr])) :
                $control = $this->preset_controls[$ctrlstr];
                $css = $control->value();
            ?>
            <table width="100%" border="0" style="padding-top: 10px;">
                <tr>
                    <td style="width: 10%"><label style="font-style: italic; font-size: 70%">Name: </label></td>
                    <td style="width: 90%"><input type="text" value="" class="at-preset-name" style="width:100%;"></td>
                </tr>
                <tr>
                    <td style="width: 10%"><label style="font-style: italic; font-size: 70%">CSS: </label></td>
                    <td style="width: 90%"><textarea class="at-preset-css-code" rows="5" style="width:100%;"><?php echo esc_textarea($css); ?></textarea></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <a class="load-preset button"
                            data-controller = "<?php esc_attr_e($setting_id); ?>"
                            style="margin-right: 10px;" title="Load Presets from Current Options">
                            <?php _e('Current Snapshot'); ?>
                        </a>
                        <a class="save-preset button"
                            data-controller = "<?php esc_attr_e($setting_id); ?>"
                            style="margin-right: 10px;" title="Save current CSS as Preset">
                            <?php _e('Save Preset'); ?>
                        </a>
                        <a class="remove-preset button"
                            data-controller = "<?php esc_attr_e($setting_id); ?>"
                            style="margin-right: 10px;" title="Remove Current Preset">
                            <?php _e('Remove Preset'); ?>
                        </a>
                    </td>
                </tr>
            </table>
            <?php
                endif;
        }

        /**
        * Import Settings Handler
        * 
        * @param string $ctrlstr Parameter 
        * @since 1.0
        * @return void    
        * @access private 
        */
        private function import_tab($ctrlstr) {
            $setting_id = "at_responsive[" . (implode('][', explode('_', $ctrlstr))) . ']';

            if (isset($this->transfer_controls[$ctrlstr])) :
                $control = $this->transfer_controls[$ctrlstr];
            ?>
            <label>
                <span class="customize-control-title">Import Theme Settings</span>
                <br>
                <div>
                    <a href="#" class="button-secondary upload at-import-settings"><?php _e('Import'); ?></a>
                </div>
            </label>
            <?php
                endif;
        }

        /**
        * Export Settings Handler
        * 
        * @param string $ctrlstr Parameter 
        * @since 1.0
        * @return void    
        * @access private 
        */
        private function export_tab($ctrlstr) {
            $setting_id = "at_responsive[" . (implode('][', explode('_', $ctrlstr))) . ']';

            if (isset($this->transfer_controls[$ctrlstr])) :
                $control = $this->transfer_controls[$ctrlstr];
                $export_link = $this->create_export_download_link("url");
            ?>
            <span class="customize-control-title">Export Theme Settings</span>
            <br>
            <a class="button-secondary button at-export-settings"
                href="<?php echo $export_link; ?>"
                target="_blank">
                <?php _e('Export'); ?>
            </a>

            <?php
                endif;
        }

        /**
        * Email Settings Handler
        * 
        * @param string $ctrlstr Parameter 
        * @since 1.0
        * @return void    
        * @access private 
        */
        private function email_tab($ctrlstr) {
            $setting_id = "at_responsive[" . (implode('][', explode('_', $ctrlstr))) . ']';

            if (isset($this->transfer_controls[$ctrlstr])) :
                $control = $this->transfer_controls[$ctrlstr];
            ?>
            <span class="customize-control-title">Email Theme Settings</span>
            <br>

            <a class="button-secondary button at-export-settings"
                data-toggle="modal"
                href="#at-transfer-modal">
                <?php _e('Send Mail'); ?>
            </a>

            <?php
                endif;
        }

        /**
        * Add Multiple Link Setting Options
        * 
        * @param object  $wp_customize Parameter 
        * @param string $id           Parameter 
        * @param boolean $full         Parameter 
        * @since 1.0
        * @return void    
        * @access public  
        */
        public function email_tab_modal() {
            $export_link = $this->create_export_download_link("email");

        ?>
        <!-- Modal -->
        <div class="modal fade" id="at-transfer-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="at-transfer-title">
                            Email Theme Settings Export File
                        </h4>
                    </div><!-- /.modal-header -->
                    <div class="modal-body">
                        <div class="col-md-12 notifications">
                            <div class="alert alert-success hidden success"><strong><span class="glyphicon glyphicon-send"></span> Theme Settings have been emailed to your recipient.</strong></div>      
                            <div class="alert alert-danger hidden error"><span class="glyphicon glyphicon-alert"></span><strong class="msg"> Oops, something went wrong.  Please contact <?php echo get_option('admin_email'); ?> for further assistance.</strong></div>
                            <div class="alert alert-danger hidden invalid"><span class="glyphicon glyphicon-alert"></span><strong class="msg"></strong> Some fields are invalid.</div>
                        </div>

                        <div class="col-md-12 modal-inner-content">
                            <form name="post" action="<?php echo $export_link; ?>" method="post" id="at-transfer-form" class="initial-form hide-if-no-js">
                                <fieldset>
                                    <!-- Text input-->
                                    <div class="form-group">
                                        <label class="hidden-xs control-label" for="at-transfer-name">Your Name</label>  
                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
                                            <input id="at-transfer-name" name="at-transfer-name" type="text" placeholder="Enter Your Name (required)" class="form-control required name" required="">
                                        </div>
                                    </div>
                                    <!-- Text input-->
                                    <div class="form-group">
                                        <label class="hidden-xs control-label" for="at-transfer-email">Your Email</label>  
                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
                                            <input id="at-transfer-email" name="at-transfer-email" type="text" placeholder="Enter Your Email (required)" class="form-control required email" required="">
                                        </div>
                                    </div>
                                    <!-- Text input-->
                                    <div class="form-group">
                                        <label class="hidden-xs control-label" for="at-transfer-recipient">Recipient's Email</label>  
                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
                                            <input id="at-transfer-recipient" name="at-transfer-recipient" type="text" placeholder="Enter Recipient's Email (required)" class="form-control required email" required="">
                                        </div>
                                    </div>
                                    <!-- Textarea -->
                                    <div class="form-group">
                                        <div class="input-group">                     
                                            <div class="input-group-addon"><span class="glyphicon glyphicon-comment"></span></div>
                                            <textarea class="form-control" id="at-transfer-comment" name="at-transfer-comment" rows="6" placeholder="Enter your comment (optional)"></textarea>
                                        </div>
                                    </div>
                                    <!-- Attachment -->
                                    <div class="form-group">
                                        <div class="input-group">                     
                                            <div class="input-group-addon"><span class="glyphicon glyphicon-paperclip"></span></div>
                                            <input id="at-transfer-attachment" name="at-transfer-attachment" type="text" readonly="readonly" placeholder="Theme Settings File Attachment" class="form-control">
                                        </div>
                                    </div>
                                </fieldset>
                                <input type="hidden" name="at-transfer-subject" value="Theme Settings from <?php bloginfo('name'); ?>" />
                                <?php wp_nonce_field( 'theme_export_options', 'at-transfer-nonce' ); ?>
                            </form>
                        </div><!-- /.modal-inner-content -->
                        <div style="width: 100%; height: 0px; clear: both;"></div>
                    </div><!-- /.modal-body -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <!--Submit-->
                        <button type="button" id="at-transfer-submit" class="btn btn-primary">Send</button>
                    </div><!-- /.modal-footer -->
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <script type="text/javascript">
            (function($) {
                /* form validation plugin */
                $.fn.goValidate = function(options) {
                    var defaults = {
                        submit: false
                    };
                    var options = $.extend(defaults, options);

                    var success = false;

                    var $form = this,
                    $inputs = $form.find(':input');

                    var validators = {
                        name: {
                            regex: /^[A-Za-z -]{3,}$/
                        },
                        pass: {
                            regex: /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/
                        },
                        email: {
                            regex: /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/
                        },
                        phone: {
                            regex: /^[2-9]\d{2}-\d{3}-\d{4}$/,
                        }
                    };
                    var validate = function(klass, value) {
                        var isValid = true,
                        error = '';

                        if (!value && /required/.test(klass)) {
                            error = 'This field is required';
                            isValid = false;
                        } else {
                            klass = klass.split(/\s/);
                            $.each(klass, function(i, k) {
                                if (validators[k]) {
                                    if (value && !validators[k].regex.test(value)) {
                                        isValid = false;
                                        error = validators[k].error;
                                    }
                                }
                            });
                        }
                        return {
                            isValid: isValid,
                            error: error
                        }
                    };
                    var showError = function($input) {
                        var klass = $input.attr('class'),
                        value = $input.val(),
                        test = validate(klass, value);

                        $input.removeClass('invalid');
                        $input.closest('.form-group').removeClass('has-error');

                        $('#form-error').addClass('hide');

                        if (!test.isValid) {
                            $input.addClass('invalid');
                            $input.closest('.form-group').addClass('has-error');

                            if (typeof $input.data("shown") == "undefined" || $input.data("shown") == false) {
                                $input.popover('show');
                            }

                        }
                        else {
                            $input.popover('hide');
                        }
                    };

                    $inputs.keyup(function() {
                        showError($(this));
                    });

                    $inputs.on('shown.bs.popover', function() {
                        $(this).data("shown", true);
                    });

                    $inputs.on('hidden.bs.popover', function() {
                        $(this).data("shown", false);
                    });

                    if (options.submit) {
                        $form.submit(function(e) {
                            $inputs.each(function() { /* test each input */
                                if ($(this).is('.required') || $(this).hasClass('invalid')) {
                                    showError($(this));
                                }
                            });
                            if ($form.find(':input.invalid').length) { /* form is not valid */
                                e.preventDefault();
                                $('#at-transfer-modal .notifications .invalid').removeClass('hidden');
                                $('#form-error').toggleClass('hide');
                                success = false;
                            } else {
                                $('#at-transfer-modal .notifications .invalid').addClass('hidden');
                                success = true;
                            }
                        });
                        return success;
                    } else {
                        $inputs.each(function() { /* test each input */
                            if ($(this).is('.required') || $(this).hasClass('invalid')) {
                                showError($(this));
                            }
                        });
                        if ($form.find(':input.invalid').length) { /* form is not valid */
                            $('#at-transfer-modal .notifications .invalid').removeClass('hidden');
                            $('#form-error').toggleClass('hide');
                        } else {
                            $('#at-transfer-modal .notifications .invalid').addClass('hidden');
                            success = true;
                        }
                        return success;
                    }
                    return this;
                };

                $('#at-transfer-submit').on('click', function(e) {
                    e.preventDefault();
                    $('#at-transfer-form').submit();
                    return false;
                });

                $('#at-transfer-form').on('submit', function(e) {
                    e.preventDefault();
                    var validated = $(this).goValidate({submit: false});

                    if (validated) {
                        var frmSerialized = $(this).serialize();
                        var submit_url = $(this).attr('action');
                        $.ajax({
                            type: 'POST',
                            url: submit_url,
                            data: frmSerialized,
                            success: function(data) {
                                var success = false;
                                try {
                                    if (data && typeof data == 'object') {
                                        if ((typeof data['outcome'] != 'undefined') && (data['outcome'] == 1)) {
                                            success = true;
                                            $('#at-transfer-modal .notifications .success').removeClass('hidden');
                                        }
                                    }
                                    if (!success)
                                        $('#at-transfer-modal .notifications .error').removeClass('hidden');
                                } catch (e) {
                                    $('#at-transfer-modal .notifications .error').removeClass('hidden');
                                }
                            },
                            error: function(){
                                $('#at-transfer-modal .notifications .error').removeClass('hidden');
                            },
                            complete: function() {
                                $('#at-transfer-form').hide();
                                $('#at-transfer-submit').hide();
                            },
                            dataType: 'json',
                            async: true
                        });
                    }
                });
            })(jQuery);
        </script>
        <?php
        }

        /**
        * Add Multiple Link Setting Options
        * 
        * @param object  $wp_customize Parameter 
        * @param string $id           Parameter 
        * @param boolean $full         Parameter 
        * @since 1.0
        * @return void    
        * @access public  
        */
        public function links_add_setting($wp_customize, $id, $full = true) {
            $wp_customize->add_setting($id, array(
            'type' => $this->option_type,
            'transport' => $this->transport,
            'capability' => 'edit_theme_options',
            ));
            $link_states = ($full) ? array('link', 'visited', 'hover', 'active') : array('color', 'hover');
            foreach ($link_states as $_state) {
                $setting_id = "{$id}[{$_state}]";
                $wp_customize->add_setting($setting_id, array(
                'type' => $this->option_type,
                'sanitize_callback' => array($this, "sanitize_hex_color"),
                'transport' => $this->transport,
                'capability' => 'edit_theme_options',
                ));
            }
        }

        /**
        * Add Multiple Font Setting Options
        * 
        * @param object  $wp_customize Parameter 
        * @param string $id           Parameter 
        * @since 1.0
        * @return void    
        * @access public  
        */
        public function font_add_setting($wp_customize, $id) {
            $wp_customize->add_setting($id, array(
            'type' => $this->option_type,
            'transport' => $this->transport,
            'capability' => 'edit_theme_options',
            ));
            $font_setting_id = "{$id}[font]";
            $wp_customize->add_setting($font_setting_id, array(
            'type' => $this->option_type,
            'transport' => $this->transport,
            'capability' => 'edit_theme_options',
            ));
            $link_setting_id = "{$id}[color]";
            $wp_customize->add_setting($link_setting_id, array(
            'type' => $this->option_type,
            'sanitize_callback' => array($this, "sanitize_hex_color"),
            'transport' => $this->transport,
            'capability' => 'edit_theme_options',
            ));
        }

        /**
        * Enqueue Scripts
        * 
        * @param string $hook Parameter 
        * @since 1.0
        * @return void    
        * @access public  
        */
        public function add_scripts($hook = null) {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_media();
            wp_print_media_templates();
            wp_enqueue_script('at-responsive-admin-script', template_url . '/lib/assets/js/at-responsive-admin.js', array(), false, true);
            wp_enqueue_script('at-responsive-admin-bootstrap-script', template_url . '/lib/assets/js/bootstrap/js/bootstrap.min.js', array(), false, true);
        }

        /**
        * Get Font List from Google
        * 
        * @param string $url Parameter 
        * @param bool $cached Parameter 
        * @since 1.0
        * @return string    
        * @access public  
        */
        public function get_fonts($url, $cached = false) {
            $success = false;
            $cachedFontURL = template_url . '/lib/assets/js/webfonts.json';
            $fonts = file_get_contents($url);
            if ($fonts) {
                $fontArr = json_decode($fonts, true);
                if ($fontArr && isset($fontArr["items"])) {
                    $success = true;
                    $js = <<<JS
                    <script type="text/javascript">
                    jQuery(function($){
                    var api = wp.customize;
                    var setFonts = function(fonts) {
                        if (typeof fonts != 'undefined'){
                            var font_choosers = $('.wp-custom-font-family-chooser');
                            font_choosers.each(function(){
                                var _chooser = $(this);
                                var current_value = _chooser.data('selected-option');
                                for (var i = 0; i < fonts.items.length; i++) {      
                                    _chooser
                                    .append($("<option></option>")
                                        .attr("value", fonts.items[i].family)
                                        .attr("selected", fonts.items[i].family === current_value)
                                        .text(fonts.items[i].family))
                                    .removeAttr('disabled');
                                }    
                            });                             
                        }
                    };
                    var initFonts = function(){
                        setFonts($fonts);
                    };
                    api.bind( 'ready', initFonts );                    
                    });
                    </script>
JS;
                    return $js;
                }
            }
            if (! $success && ! $cached) {
                return $this->get_fonts($cachedFontURL, true);
            }
            return false;
        }

        /**
        * Print Scripts
        * 
        * @param string $hook Parameter 
        * @since 1.0
        * @return void    
        * @access public  
        */
        public function print_scripts($hook = null) {
            echo '<script type="text/javascript">var google_api_key = "' . $this->google_api_key . '";</script>';
            echo '<script type="text/javascript">var ajax_url = "' . admin_url('admin-ajax.php') . '";</script>';
            $fontUrl = "https://www.googleapis.com/webfonts/v1/webfonts?key=" . $this->google_api_key;
            echo $this->get_fonts($fontUrl);
        }

        /**
        * Print Footer Scripts
        * 
        * @param string $hook Parameter 
        * @since 1.0
        * @return void    
        * @access public  
        */
        public function print_footer_scripts($hook = null) {
        }

        /**
        * Res Ipsa Loquitur
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function add_media_manager_template_to_customizer() {
            wp_print_media_templates();
        }

        /**
        * Customize Styles
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function customize_styles() {
            wp_enqueue_style('arstropica-customize-styles', template_url . '/lib/assets/css/admin-customize.css');
            wp_enqueue_style('arstropica-customize-modal-styles', template_url . '/lib/assets/js/bootstrap/css/customize-bootstrap.css', array(), '1.1');
        }

        /**
        * Set Option Field Name
        * 
        * @param string $page     Parameter 
        * @param string|null $category Parameter 
        * @param string|null $region   Parameter 
        * @param string|null $element  Parameter 
        * @param string|null $field    Parameter 
        * @since 1.0
        * @return mixed   Return 
        * @access public  
        */
        public function get_option_field_name($page, $category = null, $region = null, $element = null, $field = null) {
            $elements = array($category, $region, $element, $field);
            $option_name = false;
            $option_name = "at_theme_" . "{$page}";
            foreach ($elements as $element) {
                if ($element)
                    $option_name .= "[{$element}]";
            }
            return $option_name;
        }

        /**
        * Get Option Field Value
        * 
        * @param string $page     Parameter 
        * @param string|null $category Parameter 
        * @param string|null $region   Parameter 
        * @param string|null $element  Parameter 
        * @param string|null $field    Parameter 
        * @since 1.0
        * @return mixed   Return 
        * @access public  
        */
        public function get_option_field_value($page, $category = null, $region = null, $element = null, $field = null) {
            global $at_theme_options;
            $elements = array_filter(array($category, $region, $element, $field));
            $option_name = $at_theme_options->get_option_field_name($page);
            $option = false;
            if ($option_name) {
                $option = get_option($option_name, false);
                if ($option) {
                    foreach ($elements as $element) {
                        if (isset($option[$element])) {
                            $option = $option[$element];
                        }
                    }
                }
            }
            return $option;
        }

        /**
        * Add Theme Export Query Variable
        * 
        * @param array  $query_vars Parameter 
        * @since 1.0
        * @return array  Return 
        * @access public 
        */
        public function add_export_query_vars($query_vars) {
            $query_vars[] = 'theme_export_options';
            return $query_vars;
        }

        /**
        * Add a template redirect which looks for that query var and if found calls the download function
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function admin_redirect_download_files() {
            //download theme export
            if (isset($_REQUEST['theme_export_options']) && $_REQUEST['theme_export_options'] == 'safe_download') {
                $this->download_export_file();
                exit;
                // die();
            }
        }

        /**
        * Options Export Download Handler Function
        * 
        * @param string $content   Parameter 
        * @param string $file_name Parameter 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function download_export_file($content = null, $file_name = null) {
            global $theme_namespace, $at_theme_custom, $current_user;
            if (!wp_verify_nonce($_REQUEST['nonce'], 'theme_export_options'))
                wp_die('Security check');

            get_currentuserinfo();
            if (! is_object($at_theme_custom)) {
                $at_theme_custom = new at_responsive_theme_mod();
            }
            if ($current_user->user_login == 'guest') {
                // $cached = @unserialize($at_theme_custom->_session_read('at_responsive_preview'));
                $cached = $at_theme_custom->_session_read('at_responsive_preview');
                $options = isset($cached) ? $cached : $at_theme_custom->get_option(false, false, true);
            } else {
                //here you get the options to export and set it as content, ex:
                // $options = get_theme_mod('at_responsive');
                $options = $at_theme_custom->get_option();
            }
            $content = "<!*!* START export Code !*!*>\n" . base64_encode(serialize($options)) . "\n<!*!* END export Code !*!*>";
            $file_name = "{$theme_namespace}-options.motif";
            header('HTTP/1.1 200 OK');


            if (!current_user_can('edit_themes') && !current_user_can('edit_theme_options')) {
                wp_die('<p>' . __('You do not have sufficient permissions to use this feature on this site.') . '</p>');
            }
            if ($content === null || $file_name === null) {
                wp_die('<p>' . __('Error Downloading file.') . '</p>');
            }
            $fsize = strlen($content);
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header('Content-Description: File Transfer');
            header("Content-Disposition: attachment; filename=" . $file_name);
            header("Content-Length: " . $fsize);
            header("Expires: 0");
            header("Pragma: public");
            echo $content;
            exit;
        }

        /**
        * Function to create the export / email download link
        * 
        * @param string $mode url / link / email default url
        * @param boolean $echo true or false 
        * @since 1.0
        * @return string Return 
        * @access public 
        */
        public function create_export_download_link($mode = "url", $echo = false) {
            // $site_url = get_bloginfo('url');
            $admin_url = admin_url('admin.php');
            $args = array();
            switch ($mode) {
                case 'url' :
                case 'link' : {
                    $args['theme_export_options'] = 'safe_download';
                    $args['nonce'] = wp_create_nonce('theme_export_options');
                    break;
                }
                case 'email' : {
                    $args['theme_export_options'] = 'safe_email';
                    break;
                }
                default : {
                    $args['theme_export_options'] = 'safe_download';
                    $args['nonce'] = wp_create_nonce('theme_export_options');
                    break;
                }
            }
            $export_url = add_query_arg($args, $admin_url);
            switch ($mode) {
                case 'url' : 
                case 'email' : {
                    return $export_url;
                    break;
                }
                case 'link' : {
                    echo '<a href="' . $export_url . '" target="_blank">Download Export</a>';
                    return;
                    break;
                }
                default : {
                    return $export_url;
                    break;
                }
            }
        }

        /**
        * Add a template redirect which looks for that query var and if found calls the download function
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function admin_email_download_files() {
            //download theme export
            if (isset($_REQUEST['theme_export_options']) && $_REQUEST['theme_export_options'] == 'safe_email') {
                if (isset($_REQUEST['at-transfer-name'], $_REQUEST['at-transfer-email'], $_REQUEST['at-transfer-recipient'])) {
                    $sender_name = $_REQUEST['at-transfer-name'];
                    $sender_email = $_REQUEST['at-transfer-email'];
                    $recipient_email = $_REQUEST['at-transfer-recipient'];
                    $comment = isset($_REQUEST['at-transfer-comment']) ? $_REQUEST['at-transfer-comment'] : null;
                    $subject = isset($_REQUEST['at-transfer-subject']) ? $_REQUEST['at-transfer-subject'] : null;
                    $this->email_export_file($recipient_email, $sender_email, $sender_name, $subject, $comment);
                } else {
                    $output = array('outcome' => 0, 'notice' => 'Some required fields are missing.');
                    echo json_encode($output);
                }
                exit;
                // die();
            }
        }

        /**
        * Options Export Download Handler Function
        * 
        * @param string $content   Parameter 
        * @param string $file_name Parameter 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function email_export_file($recipient_email, $sender_email, $sender_name = "Guest User", $subject = null, $content = null) {
            global $theme_namespace, $at_theme_custom, $current_user, $wp_filesystem;
            $success = false;
            $output = array('outcome' => 0, 'notice' => 'You are not authorized to perform this action.');
            if ( empty( $_REQUEST ) || ! check_admin_referer( 'theme_export_options', 'at-transfer-nonce' ) ) {
                exit(json_encode($output, JSON_FORCE_OBJECT));
            }

            get_currentuserinfo();
            if (! is_object($at_theme_custom)) {
                $at_theme_custom = new at_responsive_theme_mod();
            }
            if ($current_user->user_login == 'guest') {
                // $cached = @unserialize($at_theme_custom->_session_read('at_responsive_preview'));
                $cached = $at_theme_custom->_session_read('at_responsive_preview');
                $options = isset($cached) ? $cached : $at_theme_custom->get_option(false, false, true);
            } else {
                //here you get the options to export and set it as content, ex:
                $options = $at_theme_custom->get_option();
            }
            $attachment = "<!*!* START export Code !*!*>\n" . base64_encode(serialize($options)) . "\n<!*!* END export Code !*!*>";
            $file_name = "{$theme_namespace}-options-" . time() . ".motif";
            $upload_dir = wp_upload_dir();
            $tmp_file_name = $upload_dir['path'] . DIRECTORY_SEPARATOR . $file_name;

            $file_saved = file_put_contents($tmp_file_name, $attachment);

            if ($file_saved) {
                if (! $subject) $subject = 'Mail from ' . get_bloginfo('name') . ' ' . get_bloginfo('url'); 
                if (! $content) 
                    $content = "\nFile Attachment: Theme Settings Import File\n";
                else
                    $content .= "\nFile Attachment: Theme Settings Import File\n";

                $headers = "From: {$sender_name} <{$sender_email}>" . "\r\n";

                $attachments = array($tmp_file_name);

                $success = wp_mail($recipient_email, $subject, $content, $headers, $attachments );

                unlink($tmp_file_name);
            }
            if ($success) {
                $output['outcome'] = 1;
                $output['notice'] = "Your mail has been sent.";
            } else {
                $output['outcome'] = 0;
                $output['notice'] = "Your mail could not be sent at this time";
            }

            exit(json_encode($output, JSON_FORCE_OBJECT));
        }

        /**
        * Construct Section ID from component parts
        * 
        * @param string  $page     Parameter 
        * @param string|null $category Parameter 
        * @param string|null $region   Parameter 
        * @param string|null $element  Parameter 
        * @param string|null $field    Parameter 
        * @since 1.0
        * @return mixed   Return 
        * @access public  
        */
        public function get_section_id($page, $category = null, $region = null, $element = null, $field = null) {
            $section_id = false;
            $elements = array_filter(array($category, $region, $element, $field));
            if ($elements)
                array_pop($elements);
            $section_id = "at_theme_" . $page . implode("_", $elements) . "_section_id";
            return $section_id;
        }

        /**
        * Overloaded method for intantiated class context
        * 
        * @param string $name      Parameter 
        * @param array   $arguments Parameter 
        * @since 1.0
        * @return unknown Return 
        * @access public  
        */
        public function __call($name, $arguments = array()) {
            // error_log(var_export(array($name, $arguments), true) . "\n\n", 3, ABSPATH . 'sanitize.txt');
            $method = $element = $callback = null;
            @list($callback, $category, $element ) = explode('_', $name);
            $argument = isset($arguments[0]) ? $arguments[0] : null;
            if (isset($callback)) {
                switch ($callback) {
                    case 'sanitize' : {
                        return self::sanitize($argument, $category, $element);
                        break;
                    }
                    case 'mltab' : {
                        $controller = str_replace('mltab_', '', $name);
                        return self::media_library_tab($controller);
                        break;
                    }
                    case 'presettab' : {
                        $controller = str_replace('presettab_', '', $name);
                        return self::preset_tab($controller);
                        break;
                    }
                    case 'importtab' : {
                        $controller = str_replace('importtab_', '', $name);
                        return self::import_tab($controller);
                        break;
                    }
                    case 'exporttab' : {
                        $controller = str_replace('exporttab_', '', $name);
                        return self::export_tab($controller);
                        break;
                    }
                    case 'emailtab' : {
                        $controller = str_replace('emailtab_', '', $name);
                        return self::email_tab($controller);
                        break;
                    }
                    default: {
                        return $argument;
                        break;
                    }
                }
            } else {
                return $argument;
            }
        }

        /**  As of PHP 5.3.0  Overloaded method for static class context */
        public static function __callStatic($name, $arguments = array()) {
            self::__call($name, $arguments);
        }

        /**
        * Sanitize each setting field as needed
        *
        * @param array $input Contains all settings fields as array keys
        */
        public function sanitize($input, $category = null, $element = null) {
            // error_log(var_export(array($input, $category, $element), true) . "\n\n", 3, ABSPATH . 'sanitize.txt');
            if ($input) {
                $tmp = $input;
                if (is_array($tmp)) {
                    foreach ($tmp as &$_input) {
                        self::_sanitize($_input, $category, $element);
                    }
                    $input = $tmp;
                } else {
                    $input = self::_sanitize($tmp, $category, $element);
                }
            }
            return $input;
        }

        /**
        * Sanitize Helper Method
        * 
        * @param mixed   &$input   Parameter 
        * @param string $category Parameter 
        * @param string $element  Parameter 
        * @since 1.0
        * @return mixed   Return 
        * @access public  
        */
        public function _sanitize(&$input, $category = null, $element = null) {
            if (is_array($input)) {
                foreach ($input as &$_input) {
                    self::_sanitize($_input, $category, $element);
                }
            } else {
                $tmp = $input;
                switch ($category) {
                    case 'url' : {
                        $input = esc_url($tmp);
                        break;
                    }
                    case 'favicon' : {
                        $ico = $tmp;
                        $input = '';
                        if (filter_var($ico, FILTER_VALIDATE_URL)) {
                            $image_info = getimagesize($ico);
                            if ($image_info && (isset($image_info['mime'])) && stristr($image_info['mime'], 'ico')) {
                                $input = esc_url($ico);
                            }
                        }
                        break;
                    }
                    case 'hex' : {
                        if ($tmp)
                            $input = sanitize_hex_color($tmp);
                        break;
                    }
                    case 'address' : {
                        switch ($element) {
                            case 'city' :
                            case 'street' : {
                                $input = trim(wp_filter_nohtml_kses($tmp));
                                break;
                            }
                            case 'state' : {
                                $input = self::_strip_non_alpha_space(wp_filter_nohtml_kses($tmp));
                                break;
                            }
                            case 'zip' : {
                                $safe_zipcode = intval($tmp);
                                if (!$safe_zipcode)
                                    $safe_zipcode = '';

                                if (strlen($safe_zipcode) > 5)
                                    $safe_zipcode = substr($safe_zipcode, 0, 5);
                                $input = $safe_zipcode;
                                break;
                            }
                        }
                        break;
                    }
                    case 'text' : {
                        switch ($element) {
                            case 'phone' : {
                                $safe_phone = intval($tmp);
                                if (!$safe_phone)
                                    $safe_phone = '';

                                if (strlen($safe_phone) < 10)
                                    $safe_phone = '';
                                elseif (strlen($safe_phone) > 11)
                                    $safe_phone = substr($safe_phone, 1, 10);
                                $input = $safe_phone;
                                break;
                            }
                            case 'default' : {
                                $input = trim(wp_filter_nohtml_kses($tmp));
                                break;
                            }
                        }
                        break;
                    }
                    case 'textarea' : {
                        $input = esc_textarea($tmp);
                        break;
                    }
                    default : {
                        // Silence
                    }
                }
            }
            return $input;
        }

        /**
        * Strip non Alpha-Space Characters
        * 
        * @param string $input Parameter 
        * @since 1.0
        * @return string  Return 
        * @access public  
        */
        public function _strip_non_alpha_space($input) {
            $pattern = '/(^[^a-z])||[^a-z ]/i';
            return preg_replace($pattern, "", $input);
        }

        /**
        * Add Ico Mime Type to Allowed Extension
        * 
        * @param array  $mime Parameter 
        * @since 1.0
        * @return array  Return 
        * @access public 
        */
        public function add_ico_mime($mime) {
            $mime['image/vnd.microsoft.icon'] = "ico";
            $mime['image/x-icon'] = "ico";
            $mime['image/ico'] = "ico";
            return $mime;
        }

        /**
        * Add Ico File Extension to Allowed Mimes
        * 
        * @param array  $site_mimes Parameter 
        * @since 1.0
        * @return array  Return 
        * @access public 
        */
        public function add_ico_ext($site_mimes) {
            if (isset($site_mimes['ico']) === false)
                $site_mimes['ico'] = 'image/vnd.microsoft.icon';
            return $site_mimes;
        }

        /**
        * Add Motif Mime Type to Allowed Extension
        * 
        * @param array  $mime Parameter 
        * @since 1.0
        * @return array  Return 
        * @access public 
        */
        public function add_motif_mime($mime) {
            if (isset($mime['text/plain ']) === false)
                $mime['text/plain'] = "motif";

            return $mime;
        }

        /**
        * Add Motif File Extension to Allowed Mimes
        * 
        * @param array  $site_mimes Parameter 
        * @since 1.0
        * @return array  Return 
        * @access public 
        */
        public function add_motif_ext($site_mimes) {
            if (isset($site_mimes['motif']) === false)
                $site_mimes['motif'] = 'text/plain';
            return $site_mimes;
        }

    }
?>