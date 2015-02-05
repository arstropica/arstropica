<?php

    /**
    * ArsTropica  Responsive Framework theme_mod.php
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
    * ArsTropica  Reponsive Framework Theme Mod Values
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
    class at_responsive_theme_mod {

        /**
        * Description for public
        * @var unknown 
        * @access public  
        */
        public $theme_options;

        /**
        * Description for public
        * @var unknown 
        * @access public  
        */
        public $theme_options_customizer;

        /**
        * Description for public
        * @var unknown 
        * @access public  
        */
        public $preset;

        /**
        * Description for private
        * @var string  
        * @access private 
        */
        private $google_api_key;

        /**
        * Start up
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function __construct() {
            $this->theme_options = $this->get_theme_options(false, false, false);
            $this->theme_options_customizer = $this->get_theme_options(false, false, $this->is_customizer());
            $this->google_api_key = $this->get_option('settings/googleapi', false, $this->is_customizer());
            $this->preset = $this->check_presets();
            if (!is_admin()) {
                add_filter('body_class', array($this, 'apply_presets'));
                add_action('wp_print_styles', array($this, 'enqueue_fonts'), 99);
                add_action('wp_print_styles', array($this, 'theme_mod_css'), 100);
                // add_action( 'wp_enqueue_scripts', array($this, 'enable_sticky_nav') );
            } else {
                add_action('wp_ajax_at_theme_reset', array($this, 'at_theme_reset'));
                add_action('wp_ajax_at_theme_transfer', array($this, 'at_theme_transfer'));
                add_action('wp_ajax_at_theme_css', array($this, 'theme_mod_css'));
                add_action('wp_ajax_at_theme_padd', array($this, 'theme_preset_add'));
                add_action('wp_ajax_at_theme_options', array($this, 'get_theme_options_ajax'));
                add_action('wp_ajax_at_theme_preset', array($this, 'get_theme_preset_css'));
                add_action('wp_ajax_at_preset_merge', array($this, 'theme_merge_preset'));
                add_action('customize_save_after', array($this, 'save_favicon'), 99999);
                add_action('customize_save_after', array($this, 'fbpage2id'), 99999);
            }
            if ($this->is_customizer()) {
                $this->filter_previewer_options();
                $this->set_previewer_options();
            }
        }

        /**
        * Get Theme Option(s)
        * 
        * @param boolean $option_path Parameter 
        * @param boolean $default     Parameter 
        * @param boolean $customize   Parameter 
        * @since 1.0
        * @return unknown Return 
        * @access private 
        */
        private function get_theme_options($option_path = false, $default = false, $customize = true) {
            $output = $default;
            $theme_options = false;
            $keys = ($option_path) ? explode("/", $option_path) : false;
            if ($this->theme_options_customizer && $customize) {
                $theme_options = $this->theme_options_customizer;
            } elseif ($this->theme_options && ! $customize) {
                $theme_options = $this->theme_options;
            } elseif ($this->is_customizer() && $customize) {
                $customized = json_decode(wp_unslash($_REQUEST['customized']), true);
                if ($customized) {
                    $_theme_options = array();
                    foreach ($customized as $vvar => $theme_option) {
                        if (is_string($vvar) && (strpos($vvar, 'at_responsive') === 0)) {
                            $ref = &$_theme_options;
                            $options_path = explode("][", trim(str_replace("at_responsive", "", $vvar), "[]"));
                            if ($options_path) {
                                foreach ($options_path as $_path) {
                                    if (!isset($ref[$_path])) {
                                        $ref[$_path] = array();
                                    }
                                    $ref = &$ref[$_path];
                                }
                                $ref = $theme_option;
                            }
                        }
                    }
                    // $ref = isset($_path) ? $_path : null;
                    $theme_options = $_theme_options;
                } else {
                    $_theme_options = array();
                }
            } else {
                $theme_options = get_theme_mod('at_responsive', false);
            }
            if ($theme_options) {
                $preset = isset($theme_options['settings']['schemes']) ? $theme_options['settings']['schemes'] : false;
                if ((empty($preset) === false) && (has_action('wp_ajax_at_theme_css') === false)) {
                    if (@isset($theme_options['settings']['presets'])) {
                        $presets = json_decode(rawurldecode($theme_options['settings']['presets']), true);
                        if (isset($presets[$preset]))
                            $theme_options['elements'] = json_decode($presets[$preset], true);
                    }
                }
                if ($keys) {
                    foreach ($keys as $key) {
                        if (isset($theme_options[$key])) {
                            $theme_options = $theme_options[$key];
                        } else {
                            return $output;
                        }
                    }
                }
                $output = self::_utf8_encode($theme_options);
            }
            return $output;
        }

        /**
        * Get Theme Option(s)
        * 
        * @param boolean $option_path Parameter 
        * @param boolean $default     Parameter 
        * @param boolean $customize     Parameter 
        * @since 1.0
        * @return boolean Return 
        * @access public  
        */
        public function get_option($option_path = false, $default = false, $customize = true) {
            return $this->get_theme_options($option_path, $default, $customize);
        }

        /**
        * Set Theme Option(s)
        * 
        * @param mixed $values      Parameter 
        * @param boolean $option_path Parameter 
        * @param boolean $customize   Parameter 
        * @since 1.0
        * @return integer Return 
        * @access private 
        */
        private function set_theme_options($values, $option_path = false, $customize = false) {
            $keys = ($option_path) ? explode("/", $option_path) : false;
            if ($this->theme_options_customizer && $customize) {
                $theme_options = $this->theme_options_customizer;
            } elseif ($customize) {
                $theme_options = $this->get_theme_options(false, array(), $customize);
            } else {
                $mods = get_theme_mods();
                $theme_options = isset($mods['at_responsive']) ? $mods['at_responsive'] : false;
            }
            if ($keys) {
                $existing = &$theme_options;
                foreach ($keys as $key) {
                    if (!isset($existing[$key])) {
                        $existing[$key] = array();
                    }
                    $existing = &$existing[$key];
                }
                $existing = $values;
            } else {
                $theme_options = $values;
            }
            set_theme_mod('at_responsive', $theme_options);
            $this->theme_options = $theme_options;
            return $theme_options;
        }

        /**
        * Set Theme Option(s)
        * 
        * @param mixed $values      Parameter 
        * @param boolean $option_path Parameter 
        * @param boolean $customize   Parameter 
        * @since 1.0
        * @return boolean Return 
        * @access public  
        */
        public function set_option($values, $option_path = false, $customize = false) {
            return $this->set_theme_options($values, $option_path, $customize);
        }

        /**
        * Set Previewer Options
        * 
        * @since 1.0
        * @return boolean Return 
        * @access public 
        */
        public function set_previewer_options() {
            if ($this->is_customizer() && (! headers_sent())) {
                $theme_options_customizer = $this->get_theme_options(false, array(), true);
                $this->_session_write(array('at_responsive_preview' => serialize($theme_options_customizer)));
                return true;
            }
            return false;
        }

        /**
        * Filter Previewer Options
        * 
        * @since 1.0
        * @return boolean Return 
        * @access public 
        */
        public function filter_previewer_options() {
            if ($this->is_customizer()) {
                add_filter( "theme_mod_at_responsive", array($this, "maybe_get_preview_theme_mod") );
            }
            return false;
        }

        /**
        * Write Session Variable
        * 
        * @param mixed $values      Parameter 
        * @param mixed $keys Parameter 
        * @param boolean $close   Parameter 
        * 
        * @since 1.0
        * @return array Return 
        * @access public 
        */
        public function _session_write($values, $keys=null, $close=true) {
            if (empty($values)) return false;
            $input = array();
            $keys = is_array($keys) ? $keys : (empty($keys) ? array() : array($keys));
            $values = is_array($values) ? $values : array($values);
            if (empty($keys)) {
                $input = $values;
            } else {
                $input = @array_combine($keys, $values);
            }
            if ($input) {
                @session_start();
                $_SESSION = array_merge($_SESSION, $input);
                if ($close) session_write_close();
            }
            return $input;
        }

        /**
        * Read Session Variable
        * 
        * @param string $key Parameter 
        * @param boolean $toJSON   Parameter 
        * 
        * @since 1.0
        * @return mixed Return 
        * @access public 
        */
        public function _session_read($key, $toJSON=true){
            @session_start();
            if (isset($_SESSION[$key]))
                return ($toJSON) ? json_encode(array($key => $_SESSION[$key]), JSON_FORCE_OBJECT) : array($key => $_SESSION[$key]);            
        }

        /**
        * Reset Session Variable
        * 
        * @param string $key Parameter 
        * 
        * @since 1.0
        * @return void 
        * @access public 
        */
        public function _session_reset($key){
            @session_start();
            $_SESSION[$key] = null;
            session_write_close();
        }

        /**
        * Filter for Previewer Setting
        * 
        * @param string $current_mod The value of the current theme modification.
        * @since 1.0
        * @return void 
        * @access public  
        */
        public function maybe_get_preview_theme_mod($current_mod) {
            // If preview is on
            if ($this->is_customizer()) {
                $current_filter = current_filter();
                if ($current_filter && strpos($current_filter, "theme_mod_at_responsive") === 0) {
                    $_path = implode("/", explode("][", trim(str_replace("theme_mod_at_responsive", "", $current_filter), "[]")));
                    if ($_path || $_path === "") {
                        return $this->get_option($_path, $current_mod, true);
                    }
                }
            }
            return $current_mod;                                
        }

        /**
        * Return JSON encoded options
        * 
        * @since 1.0
        * @return unknown Return 
        * @access public  
        */
        public function get_theme_options_ajax() {
            $ajax = json_encode($this->get_theme_options(false, false, false), JSON_FORCE_OBJECT);
            if (has_action('wp_ajax_at_theme_options')) {
                exit($ajax);
            }
            return $ajax;
        }

        /**
        * Check Presets
        * 
        * @since 1.0
        * @return mixed  Return 
        * @access public 
        */
        public function check_presets() {
            // $this->preset = $this->get_theme_options('settings/schemes', false);
            return $this->get_theme_options('settings/schemes', false);
        }

        /**
        * Apply Preset Classes to Body Tag
        * 
        * @param array  $c Parameter 
        * @since 1.0
        * @return array  Return 
        * @access public 
        */
        public function apply_presets($c) {
            $preset = $this->get_theme_options('settings/schemes');
            if ($preset) {
                $c[] = "at-responsive-preset";
                $c[] = "$preset";
            }
            return $c;
        }

        /**
        * Enqueue Custom Fonts
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function enqueue_fonts() {
            $fonts = $this->get_fonts();
            if ($fonts) {
                wp_register_style('at-responsive-google-fonts', 'http://fonts.googleapis.com/css?family=' . implode('|', array_map('urlencode', $fonts)));
                wp_enqueue_style('at-responsive-google-fonts');
            }
        }

        /**
        * Get Custom Fonts
        * 
        * @since 1.0
        * @return mixed  Return 
        * @access public 
        */
        public function get_fonts() {
            $theme_options = $this->theme_options;
            $fonts = array();
            if ($theme_options && isset($theme_options['elements'])) {
                foreach ($theme_options['elements'] as $region => $features) {
                    foreach ($features as $feature => $property) {
                        if (($feature == 'fonts') && isset($property['font'])) {
                            $fonts[] = $property['font'];
                        }
                    }
                }
            }
            if ($fonts) {
                return array_unique(array_filter($fonts));
            } else {
                return false;
            }
        }

        /**
        * Enable Sticky Nav
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function enable_sticky_nav() {
            $enable_sticky_nav = $this->get_theme_options('settings/stickynav');
            if ($enable_sticky_nav) {
                wp_enqueue_script('fixed-nav', template_url . '/lib/assets/js/fixed-nav.js', array(), false, true);
            }
        }

        /**
        * Get Phone Number
        * 
        * @param boolean $default Parameter 
        * @since 1.0
        * @return unknown Return 
        * @access public  
        */
        public function get_phone($default = false) {
            $output = $default;
            $phone_number = preg_replace('/[^0-9]/i', '', $this->get_theme_options('info/phonenumber'));
            if ($phone_number && strlen($phone_number) >= 10) {
                $output = substr($phone_number, 0, 3) . " &bull; " . substr($phone_number, 3, 3) . " &bull; " . substr($phone_number, 6, 4);
            }
            return $output;
        }

        /**
        * Get Address String
        * 
        * @param boolean $default Parameter 
        * @since 1.0
        * @return unknown Return 
        * @access public  
        */
        public function get_address($default = false) {
            $output = $default;
            $address = $this->get_theme_options('info/address');
            if ($address && is_array($address) && (array_filter($address) !== array())) {
                if (@isset($address['street'], $address['city'], $address['state'], $address['zip']))
                    $output = $address['street'] . ", " . $address['city'] . ", " . $address['state'] . ", " . $address['zip'];
            }
            return $output;
        }

        /**
        * Get Favicon
        * 
        * @param boolean $src  Parameter 
        * @param boolean $echo Parameter 
        * @since 1.0
        * @return boolean Return 
        * @access public  
        */
        public function get_favicon($src = false, $echo = true) {
            $favicon = false;
            $favicon_url = $this->get_theme_options('images/favicon', '');
            if ($favicon_url) {
                if ($src) {
                    $favicon = $favicon_url;
                } else {
                    $favicon_html = '<link rel="icon" href="' . $favicon_url . '" type="image/x-icon" />' . "\n";
                    // Cross Browser Compatibility
                    $favicon_html .= '<link rel="shortcut icon" href="' . $favicon_url . '" type="image/x-icon" />' . "\n";
                    $favicon = $favicon_html;
                }
            }
            if ($echo)
                echo $favicon;
            else
                return $favicon;
        }

        /**
        * Generate Google Maps Link
        * 
        * @since 1.0
        * @return mixed  Return 
        * @access public 
        */
        public function get_gmap_link() {
            $output = false;
            $address = $this->get_address();
            if ($address) {
                $output = "http://maps.google.com/?q=" . $address;
            }
            return $output;
        }

        /**
        * Generate Custom Options CSS
        * 
        * @param boolean $preset Parameter 
        * @param boolean $return Parameter 
        * @since 1.0
        * @return mixed   Return 
        * @access public  
        */
        public function theme_mod_css($preset = false, $return = false) {
            $theme_options = $this->get_theme_options(false, false, $this->is_customizer());
            $preset = $preset ? $preset : (has_action('wp_ajax_at_theme_css') ? false : $this->check_presets());
            $factory = array();
            $selectors = array();
            $selectors['regions'] = array('navigation' => 'BODY.at-responsive-theme HEADER nav.navbar', 'site' => 'BODY.at-responsive-theme', 'content' => 'BODY.at-responsive-theme .content-row ARTICLE', 'widgets' => 'HTML .at_widget.widget .widget-frame', 'footer' => 'BODY.at-responsive-theme footer#footer');
            $selectors['features'] = array(
                'entryheader' => array('content' => '.singular-parent .entry-header'),
                'postmeta' => array('content' => ':not(.singular-parent) .entry-meta .post-meta'),
                'background' => array('navigation' => '', 'site' => '', 'content' => ' .content-wrapper', 'widgets' => '', 'footer' => ''),
                'backgroundimage' => array('site' => ''),
                'border' => array('navigation' => '', 'site' => '', 'content' => ' .content-wrapper', 'widgets' => '', 'footer' => ''),
                'dropdown' => array('navigation' => ' .dropdown-menu'),
                'icons' => array('navigation' => '', 'content' => ' ul.social-share-in-title li a > i.iconf', 'social' => ' i.iconf'),
                'fonts' => array('navigation' => ' *:not(I):not(.glyphicon):not(.social-icon):not(.ab-icon)', 'site' => ' *:not(I):not(.glyphicon):not(.social-icon):not(.ab-icon)', 'content' => ' *:not(I):not(.glyphicon):not(.social-icon):not(.ab-icon)', 'widgets' => ' *:not(I):not(.glyphicon):not(.social-icon):not(.ab-icon)', 'footer' => ' *:not(I):not(.glyphicon):not(.social-icon):not(.ab-icon)'),
                'links' => array('navigation' => ' A', 'site' => ' A', 'content' => ' A', 'widgets' => ' A', 'footer' => ' A'),
                'readmore' => array('content' => ' .read-n-share A.read-more'),
                'share' => array('content' => ' A.share-post SPAN.glyphicon'),
                'heading' => array('widgets' => ' H4.widgettitle'),
            );
            $selectors['state'] = array(
                'regions' => array(
                    'default' => array(
                        'features' => array(
                            'default' => array('link' => ':link', 'visited' => ':visited', 'hover' => ':hover', 'active' => ':active', 'color' => '', 'font' => ''),
                            'entryheader' => array('color' => ''),
                            'postmeta' => array('color' => ''),
                            'background' => array('color' => ''),
                            'backgroundimage' => array('background-image' => ''),
                            'border' => array('color' => ''),
                            'dropdown' => array('color' => ''),
                            'icons' => array('color' => '', 'hover' => ''),
                            'links' => array('link' => ':link', 'visited' => ':visited', 'hover' => ':hover', 'active' => ':active'),
                            'fonts' => array('color' => '', 'font' => ''),
                        ),
                    ),
                    'navigation' => array(
                        'features' => array(
                            'entryheader' => array('color' => '', 'hover' => ':hover', 'font' => ''),
                            'postmeta' => array('color' => '', 'hover' => ':hover', 'font' => ''),
                            'background' => array('color' => '', 'hover' => ' li A:not(.navicon):hover', 'font' => ''),
                            'backgroundimage' => array('background-image' => ''),
                            'border' => array('color' => ', BODY.at-responsive-theme HEADER nav.navbar .icon-button.navbar-nav > .dropdown, BODY.at-responsive-theme HEADER nav.navbar .navbar-collapse.collapse .dropdown UL > LI, .ltDeskTop BODY.at-responsive-theme .navbar-layout.layout-top', 'hover' => ' li A:hover', 'font' => ''),
                            'dropdown' => array('color' => ' li A', 'hover' => ' li A:hover', 'font' => ''),
                            'icons' => array('color' => ' .dropdown *[class*="icon"]', 'hover' => ' .dropdown *:hover *[class*="icon"]'),
                            'links' => array('link' => ':link', 'visited' => ':visited', 'hover' => ':hover', 'active' => ':active'),
                            'fonts' => array('color' => '', 'font' => ''),
                        )
                    ),
                    'content' => array(
                        'features' => array(
                            'default' => array('link' => ':link', 'visited' => ':visited', 'hover' => ':hover', 'active' => ':active', 'color' => '', 'font' => ''),
                            'entryheader' => array('color' => ''),
                            'postmeta' => array('color' => ''),
                            'background' => array('color' => ''),
                            'backgroundimage' => array('background-image' => ''),
                            'border' => array('color' => ''),
                            'icons' => array('color' => '', 'hover' => ':hover'),
                            'links' => array('link' => ':link', 'visited' => ':visited', 'hover' => ':hover', 'active' => ':active'),
                            'fonts' => array('color' => '', 'font' => ''),
                        )
                    ),
                )
            );
            $properties = array();
            $properties['state'] = array(
                'default' => array(
                    'features' => array('entryheader' => 'background-color', 'postmeta' => 'background-color', 'background' => 'background-color', 'backgroundimage' => 'background-image', 'border' => 'border-color', 'dropdown' => 'background-color', 'icons' => 'color', 'fonts' => 'font-family', 'links' => 'color', 'readmore' => 'background-color', 'share' => 'color', 'heading' => 'background-color')
                ),
                'color' => array(
                    'features' => array('entryheader' => 'background-color', 'postmeta' => 'background-color', 'background' => 'background-color', 'backgroundimage' => 'background-image', 'border' => 'border-color', 'dropdown' => 'background-color', 'icons' => 'color', 'fonts' => 'color', 'links' => 'color', 'readmore' => 'background-color', 'share' => 'color', 'heading' => 'background-color')
                ),
                'font' => array(
                    'features' => array('entryheader' => 'background-color', 'postmeta' => 'background-color', 'background' => 'background-color', 'backgroundimage' => 'background-image', 'border' => 'border-color', 'dropdown' => 'background-color', 'icons' => 'color', 'fonts' => 'font-family', 'links' => 'color', 'readmore' => 'background-color', 'share' => 'color', 'heading' => 'background-color')
                ),
            );

            $rules = array();

            if ((empty($preset) === false) && (has_action('wp_ajax_at_theme_css') === false)) {
                if (@isset($theme_options['settings']['presets'])) {
                    $presets = json_decode(rawurldecode($theme_options['settings']['presets']), true);
                    if (isset($presets[$preset]))
                        $theme_options['elements'] = json_decode($presets[$preset], true);
                }
            }
            if ($theme_options && isset($theme_options['elements'])) {
                $i = 0;
                foreach ($theme_options['elements'] as $region => $features) {
                    $factory[$i]['region'] = $selectors['regions'][$region];
                    if ($features) {
                        if (is_array($features)) {
                            foreach ($features as $feature => $states) {
                                unset($factory[$i]['feature']);
                                unset($factory[$i]['state']);
                                $factory[$i]['feature'] = $selectors['features'][$feature][$region];
                                if ($states) {
                                    if (is_array($states)) {
                                        foreach ($states as $state => $value) {
                                            unset($factory[$i]['state']);
                                            if ($value) {
                                                if ($state == 'font') {
                                                    $value = '"' . $value . '"';
                                                }
                                                $value .= " !important";
                                                if (isset($properties['state'][$state])) {
                                                    $property = $properties['state'][$state]['features'][$feature];
                                                } else {
                                                    $property = $properties['state']['default']['features'][$feature];
                                                }
                                                if (isset($selectors['state']['regions'][$region])) {
                                                    if (isset($selectors['state']['regions'][$region]['features'][$feature])) {
                                                        if (isset($selectors['state']['regions'][$region]['features'][$feature][$state])) {
                                                            $factory[$i]['state'] = $selectors['state']['regions'][$region]['features'][$feature][$state];
                                                            $rules[] = implode("", $factory[$i]) . " { {$property} : {$value}; }";
                                                            if ($region == 'navigation' && $feature == 'links') {
                                                                $rules[] = implode("", $factory[$i]) . " SPAN.caret { border-bottom-color : {$value}; }";
                                                                $rules[] = implode("", $factory[$i]) . " SPAN.caret { border-top-color : {$value}; }";
                                                            }
                                                            if ($region == 'content' && $feature == 'links') {
                                                                $rules[] = implode("", $factory[$i]) . " .entry-date { {$property} : {$value}; }";
                                                            }
                                                            continue 1;
                                                        }
                                                    } else {
                                                        if (isset($selectors['state']['regions'][$region]['features']['default'][$state]) === false)
                                                            var_dump($region, $feature, $state);
                                                        $factory[$i]['state'] = $selectors['state']['regions'][$region]['features']['default'][$state];
                                                        $rules[] = implode("", $factory[$i]) . " { {$property} : {$value}; }";
                                                        continue 1;
                                                    }
                                                } else {
                                                    if (isset($selectors['state']['regions']['default']['features'][$feature][$state])) {
                                                        $factory[$i]['state'] = $selectors['state']['regions']['default']['features'][$feature][$state];
                                                        $rules[] = implode("", $factory[$i]) . " { {$property} : {$value}; }";
                                                        continue 1;
                                                    } else {
                                                        $factory[$i]['state'] = $selectors['state']['regions']['default']['features']['default'][$state];
                                                        $rules[] = implode("", $factory[$i]) . " { {$property} : {$value}; }";
                                                        continue 1;
                                                    }
                                                }
                                                $rules[] = implode("", $factory[$i]) . " { {$property} : {$value}; }";
                                                continue 1;
                                            }
                                        }
                                    } elseif ($states) {
                                        if ($properties['state']['default']['features'][$feature] == 'background-image') {
                                            $states = 'url("' . $states . '")';
                                        }
                                        $states .= " !important";
                                        $rules[] = implode("", $factory[$i]) . " { {$properties['state']['default']['features'][$feature]} : {$states}; }";
                                        continue 1;
                                    }
                                }
                            }
                        }
                    }
                    $i ++;
                }
                // $rules[] = "BODY .row.content-wrapper.post-wrapper A.share-post i.icon-forward:before, BODY .row.content-wrapper.post-wrapper A.share-post i.icon-forward:hover:before  { -webkit-background-clip: text; -webkit-text-fill-color: transparent; display: initial; }";
            }

            if ($theme_options && isset($theme_options['social']['widget']) && (@empty($theme_options['social']['widget']['enable']) === false) && (count($theme_options['social']['widget']) > 1) && (@empty($theme_options['social']['icons']) === false) && (array_filter($theme_options['social']['icons']) !== array())) {
                foreach ($theme_options['social']['icons'] as $state => $color) {
                    if ($color) {
                        $shadow = $this->alterBrightness(substr($color, 1), -100);
                        $rules[] = ".social-icon" . ($state == 'hover' ? ':hover' : '') . ":before { color: {$color}; " . ($state != 'hover' ? '' : '-webkit-transition: all 1s ease-in-out; -moz-transition: all 1s ease-in-out; -o-transition: all 1s ease-in-out;') . " }";
                    }
                }
            }

            if ($theme_options && isset($theme_options['css']['customcss']) && (@empty($theme_options['css']['customcss']) === false)) {
                $rules[] = htmlspecialchars_decode($theme_options['css']['customcss']);
            }

            // if (1 == get_current_blog_id())
            if (has_action('wp_ajax_at_theme_css')) {
                echo implode("\n", $rules);
                exit;
            } elseif ($return) {
                return implode("\n", $rules);
            } else {
                // add_action('wp_footer', function() use ($rules, $theme_options, $factory) {echo '<textarea rows="10" cols="25" style="width: 100%;">' . '<style type="text/css">' . "\n" . implode("\n", $rules) . "\n" . '</style>' . "\n\n" . print_r($theme_options, true)  . "\n\n" . print_r(($factory), true) . '</textarea>';}, 10 ); 
                echo '<style type="text/css">' . "\n" . implode("\n", $rules) . "\n" . '</style>';
            }
        }

        /**
        * alter brightness of color
        * 
        * @param string $color  Parameter 
        * @param int|float $amount Parameter 
        * @since 1.0
        * @return integer Return 
        * @access private 
        */
        private function alterBrightness($color, $amount) {
            $rgb = hexdec($color); // convert color to decimal value
            //extract color values:
            $red = $rgb >> 16;
            $green = ($rgb >> 8) & 0xFF;
            $blue = $rgb & 0xFF;

            //manipulate and convert back to hexadecimal
            return dechex(($red + $amount) << 16 | ($green + $amount) << 8 | ($blue + $amount));
        }

        /**
        * Output Mobile Header Logo
        * 
        * @param mixed  $height Parameter 
        * @since 1.0
        * @return mixed  Return 
        * @access public 
        */
        public function mobile_header_logo($height = 64) {
            $output = false;
            $mobile_logo = false;
            $logo = $this->get_theme_options('images/companylogo');
            if ($logo) {
                $upload_dir = wp_upload_dir();
                $working_dir = $upload_dir['basedir'] . '/default_img/';
                $img_info = pathinfo($logo);
                $img_base_filename = $img_info['filename'] . '.' . $img_info['extension'];
                $img_filename = $img_info['filename'] . '-' . $height . '.' . $img_info['extension'];
                if (file_exists($working_dir . $img_filename)) {
                    $mobile_logo = at_responsive_file2url($working_dir . $img_filename);
                    $_size = getimagesize($working_dir . $img_filename);
                    $image_wh = array('width' => $_size[0], 'height' => $_size[1]);
                } else {
                    $path = str_replace(home_url('/'), ABSPATH, $logo);
                    $image = wp_get_image_editor($path);
                    if (!is_wp_error($image)) {
                        $image->resize(0, $height, true);
                        $image_wh = $image->get_size();
                        $image->save($working_dir . $img_filename);
                        $mobile_logo = at_responsive_file2url($working_dir . $img_filename);
                    }
                }
            }
            $output = '<div class="mobile-logo-wrapper">';
            if ($mobile_logo) {
                $output .= '<div class="mobile-logo-image"><img class="logo mobile" src="' . $mobile_logo . '" alt="' . get_bloginfo('name') . '" width="' . $image_wh['width'] . '" height="' . $image_wh['height'] . '" /></div>';
            } else {
                $output .= '<div class="mobile-logo-heading"><h3>' . get_bloginfo('name') . '</h3></div>';
            }
            $output .= '</div>';
            return $output;
        }

        /**
        * ArsTropica  Reset Theme Options
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function at_theme_reset() {
            $theme_options = $this->get_theme_options();
            if ($theme_options) {
                $reset_value = esc_attr($_POST['reset_value']);

                if ($reset_value && current_user_can('edit_theme_options')) {
                    unset($theme_options['elements']);
                    unset($theme_options['settings']['schemes']);
                    set_theme_mod('at_responsive', $theme_options);
                    die('Theme Options Reset!');
                } else {
                    die('You do not have permissions to do this.');
                }
            } else {
                die('Theme Options not found!');
            }
            die('Oops, Something went wrong!');
        }

        /**
        * ArsTropica  Import Theme Options
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function at_theme_transfer() {
            $theme_options = $this->get_theme_options();
            $import_code = false;
            $attachment_id = $_POST['import_value'];
            if ($theme_options) {
                if (current_user_can('edit_theme_options')) {
                    $attachment_url = wp_get_attachment_url($attachment_id);
                    $import_value = file_get_contents($attachment_url);
                    if ($import_value && stristr($import_value, "<!*!* START export Code !*!*>\n") && stristr($import_value, "\n<!*!* END export Code !*!*>")) {
                        $import_code = str_replace("<!*!* START export Code !*!*>\n", "", $import_value);
                        $import_code = str_replace("\n<!*!* END export Code !*!*>", "", $import_code);
                        $import_code = @base64_decode($import_code);
                        $import_code = @unserialize($import_code);
                        if ($import_code) {
                            set_theme_mod('at_responsive', $import_code);
                            wp_delete_attachment($attachment_id);
                            die('Theme Options Reset!');
                        } else {
                            wp_delete_attachment($attachment_id);
                            die('Theme Options could not be imported! Invalid content.');
                        }
                    } else {
                        wp_delete_attachment($attachment_id);
                        die('Theme Options could not be imported! Invalid File.');
                    }
                } else {
                    wp_delete_attachment($attachment_id);
                    die('You do not have permissions to do this.');
                }
            } else {
                wp_delete_attachment($attachment_id);
                die('Theme Options could not be imported! No existing Option to replace.');
            }
            wp_delete_attachment($attachment_id);
            die('Oops, Something went wrong!');
        }

        /**
        * Add Theme Preset
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function theme_preset_add() {
            $at_preset_name = @esc_attr($_REQUEST['at_preset_name']);
            $at_preset_value = @esc_attr($_REQUEST['at_preset_value']);

            if (current_user_can('edit_theme_options')) {
                if ($at_preset_name) {
                    $theme_options = $this->get_theme_options(false, false, false);
                    $presets = isset($theme_options['settings']['presets']) ? $theme_options['settings']['presets'] : false;
                    if ($presets) {
                        if (isset($presets[$at_preset_name])) {
                            unset($presets[$at_preset_name]);
                        }
                    } else {
                        $presets = array();
                    }
                    if (empty($at_preset_value) === false) {
                        $presets[$at_preset_name] = $at_preset_value;
                    }
                    $theme_options['settings']['presets'] = $presets;
                    set_theme_mod('at_responsive', $theme_options);
                    if (has_action('wp_ajax_at_theme_padd')) {
                        exit(json_encode($theme_options, JSON_FORCE_OBJECT));
                    }
                }
            }
        }

        /**
        * Get Theme Preset CSS
        * 
        * @since 1.0
        * @return boolean Return 
        * @access public  
        */
        public function get_theme_preset_css() {
            $at_preset_value = false;
            if (isset($_REQUEST['at_preset_name'])) {
                $at_preset_name = @esc_attr($_REQUEST['at_preset_name']);
                $at_preset_value = $this->theme_mod_css($at_preset_name);
            }
            if (has_action('wp_ajax_at_theme_preset')) {
                exit($at_preset_value);
            }
            return $at_preset_value;
        }

        /**
        * Get Theme Preset
        * 
        * @param string $preset Parameter 
        * @since 1.0
        * @return array   Return 
        * @access public  
        */
        public function get_theme_preset($preset) {
            $theme_options = $this->get_theme_options();
            if (empty($preset) === false) {
                if (@isset($theme_options['settings']['presets'])) {
                    $presets = json_decode(rawurldecode($theme_options['settings']['presets']), true);
                    if (isset($presets[$preset]))
                        $theme_options['elements'] = json_decode($presets[$preset], true);
                }
            }
            return $theme_options;
        }

        /**
        * Merge Preset into Theme Options
        * 
        * @param boolean $preset Parameter 
        * @since 1.0
        * @return boolean Return 
        * @access public  
        */
        public function theme_merge_preset($preset = false) {
            $modified_theme_options = false;
            if (isset($_REQUEST['at_preset_name']) || $preset) {
                $at_preset_name = $preset ? $preset : @esc_attr($_REQUEST['at_preset_name']);
                $modified_theme_options = $this->get_theme_preset($at_preset_name);
                if ($modified_theme_options) {
                    set_theme_mod('at_responsive', $modified_theme_options);
                    if (has_action('wp_ajax_at_preset_merge')) {
                        exit("1");
                    }
                }
            }
            if (has_action('wp_ajax_at_preset_merge')) {
                exit("0");
            }
            return $modified_theme_options;
        }

        /**
        * Save Favicon
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function save_favicon() {
            $theme_options = $this->get_theme_options(false, false, $this->is_customizer());
            if ($this->is_customizer()) {
                $customized = json_decode(wp_unslash($_REQUEST['customized']), true);
                if ($customized && isset($customized['at_responsive[images][favicon]'])) {
                    $theme_options['images']['favicon'] = $customized['at_responsive[images][favicon]'];
                    set_theme_mod('at_responsive', $theme_options);
                }
            }
        }

        /**
        * Check if Customizer is Active
        * 
        * @since 1.0
        * @return boolean Return 
        * @access public  
        */
        public function is_customizer() {
            if (isset($_REQUEST['wp_customize']) || isset($_REQUEST['at_customize'])) {
                return true;
            } else {
                return false;
            }
        }

        /**
        * Retrieve FB Page ID and save to Options
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function fbpage2id() {
            // $fbpageurl = ("https://www.facebook.com/pages/ArchViz-by-Tropical-Designs/356995995923");
            $result = false;
            if ($this->is_customizer() && current_user_can('edit_theme_options')) {
                $theme_options = $this->get_theme_options(false, false, $this->is_customizer());
                $customized = json_decode(wp_unslash($_REQUEST['customized']), true);
                if ($customized && isset($customized['at_responsive[seo][facebookpage]'])) {
                    $fbpageurl = $customized['at_responsive[seo][facebookpage]'];
                    $_fbpageurl = esc_url($fbpageurl);
                    if ($_fbpageurl) {
                        $fb_graph_api = "https://graph.facebook.com/";
                        $fb_parts = array();
                        $pattern = "/(?:https?:\/\/)?(?:www\.)?facebook\.com\/((\w)*#!\/)?(?:pages\/)?([\w\-]*\/)(?:\/)?([^\/?]*)/i";
                        preg_match($pattern, $fbpageurl, $fb_raw_parts);
                        if ($fb_raw_parts) {
                            unset($fb_raw_parts[0]);
                            foreach ($fb_raw_parts as $fb_raw_part) {
                                $fb_maybe = preg_replace('/[^A-Za-z0-9\-]/', '', $fb_raw_part);
                                if ($fb_maybe)
                                    $fb_parts[] = $fb_maybe;
                            }
                            if ($fb_parts) {
                                foreach ($fb_parts as $fb_part) {
                                    $_fbjson = wp_remote_get($fb_graph_api . $fb_part);
                                    if (!is_wp_error($_fbjson) && isset($_fbjson['body'])) {
                                        $_fbres = json_decode($_fbjson['body'], true);
                                        if (!$_fbres || @isset($_fbres['error']))
                                            continue;
                                        if (!@isset($_fbres['id']))
                                            continue;
                                        if (isset($_fbres['id'])) {
                                            $result = $_fbres['id'];
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if ($result) {
                        $theme_options['seo']['facebookid'] = $result;
                    } else {
                        unset($theme_options['seo']['facebookid']);
                    }
                    set_theme_mod('at_responsive', $theme_options);
                }
            }
        }

        /**
        * Esc DB values for output
        * 
        * @param mixed $values
        */
        private static function _utf8_encode($values, $type = null) {

            $type = $type ?: gettype($values);

            switch ($type) {

                case "boolean" :
                case "integer" :
                case "double" :
                case "object" :
                case "resource" :
                case "NULL" : 
                default : {

                    // return $values;

                    break;

                }

                case "string" : {

                    $values = utf8_encode($values);
                    
                    break;

                }

                case "array" : {

                    $values = array_map(array('self', '_utf8_encode'), $values);
                    
                    break;

                }

            }
            
            return $values;

        }

    }

?>
