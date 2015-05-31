<?php
    /**
    * ArsTropica  Responsive Framework class-controls.php
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
    * WP Customizer Font Family Control
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
    class Font_Family_Control extends WP_Customize_Control {

        /**
        * Description for public
        * @var string 
        * @access public 
        */
        public $type = 'select';

        /**
        * Render the control's content.
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function render_content() {
            global $at_theme_custom;
            if (! is_object($at_theme_custom)) {
                
                $at_theme_custom = new at_responsive_theme_mod();
                
                $google_api_key = $at_theme_custom->get_option('settings/googleapi', false, $at_theme_custom->is_customizer());
                
            }
        ?>
        <label>
            <span class="customize-control-title" style="font-weight: normal; font-style: italic; font-size: 85%;"><?php echo esc_html($this->label); ?></span>
            <select style="width:100%;" class="wp-custom-font-family-chooser" <?php $this->link(); ?> data-selected-option="<?php echo esc_attr($this->value()); ?>" <?php disabled($google_api_key, false, true); ?>>
                <option value="">Choose Font</option>
            </select>
        </label>
        <?php
        }

    }

    /**
    * WP Customizer Link Color Control
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
    class Link_Color_Control extends WP_Customize_Color_Control {

        /**
        * Render the control's content.
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function render_content() {
            $this_default = $this->setting->default;
            $default_attr = '';
            if ($this_default) {
                if (false === strpos($this_default, '#'))
                    $this_default = '#' . $this_default;
                $default_attr = ' data-default-color="' . esc_attr($this_default) . '"';
            }
            // The input's value gets set by JS. Don't fill it.
        ?>

        <label>
            <span class="customize-control-title" style="font-weight: normal; font-style: italic; font-size: 85%;"><?php echo esc_html($this->label); ?></span>
            <div class="customize-control-content">
                <input class="color-picker-hex" type="text" maxlength="7" placeholder="<?php esc_attr_e('Hex Value'); ?>"<?php echo $default_attr; ?> />
            </div>
        </label>
        <?php
        }

    }

    /**
    * WP Customizer Font Color Control
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
    class Fonts_Control extends WP_Customize_Control {

        /**
        * Description for public
        * @var string 
        * @access public 
        */
        public $type = 'color';

        /**
        * Constructor.
        * 
        * @param object  $manager Parameter 
        * @param string $id      Parameter 
        * @param array   $args    Parameter 
        * @since 1.0
        * @return void    
        * @access public  
        */
        public function __construct($manager, $id, $args = array('priority' => 0)) {
            global $theme_namespace;
            parent::__construct($manager, $id, $args);
            $font_setting_id = "{$id}[font]";
            $manager->add_control(
            new Font_Family_Control($manager, $font_setting_id, array(
            'label' => __("Font Family", $theme_namespace),
            'section' => $args['section'],
            'settings' => $font_setting_id,
            'priority' => $args['priority'] + 1,
            ))
            );
            $link_setting_id = "{$id}[color]";
            $manager->add_control(
            new Link_Color_Control($manager, $link_setting_id, array(
            'label' => __("Font Color", $theme_namespace),
            'section' => $args['section'],
            'settings' => $link_setting_id,
            'priority' => $args['priority'] + 2,
            ))
            );
        }

        /**
        * Render the control's content.
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function render_content() {
        ?>
        <label>
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
        </label>
        <?php
        }

    }

    /**
    * WP Customizer Link Control
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
    class Links_Control extends WP_Customize_Control {

        /**
        * Description for public
        * @var string 
        * @access public 
        */
        public $type = 'color';

        /**
        * Constructor
        * 
        * @param object  $manager Parameter 
        * @param string $id      Parameter 
        * @param array   $args    Parameter 
        * @since 1.0
        * @return void    
        * @access public  
        */
        public function __construct($manager, $id, $args = array('priority' => 0)) {
            global $theme_namespace;
            $full = (isset($args['full'])) ? $args['full'] : true;
            unset($args['full']);
            parent::__construct($manager, $id, $args);
            $link_states = ($full) ? array('link', 'visited', 'hover', 'active') : array('color', 'hover');
            foreach ($link_states as $_index => $_state) {
                $setting_id = "{$id}[{$_state}]";
                $manager->add_control(
                new Link_Color_Control($manager, $setting_id, array(
                'label' => __(":{$_state}", $theme_namespace),
                'section' => $args['section'],
                'settings' => $setting_id,
                'priority' => $args['priority'] + $_index + 1,
                ))
                );
            }
        }

        /**
        * Render the control's content.
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function render_content() {
        ?>
        <label>
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
        </label>
        <?php
        }

    }

    /**
    * WP Customizer Text Field Control
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
    class TextBox_Control extends WP_Customize_Control {

        /**
        * Description for public
        * @var string 
        * @access public 
        */
        public $width;

        /**
        * Description for public
        * @var unknown 
        * @access public  
        */
        public $type;

        /**
        * Constructor
        * 
        * @param WP_Customize_Manager $manager Parameter 
        * @param string $id      Parameter 
        * @param array   $args    Parameter 
        * @since 1.0
        * @return void    
        * @access public  
        */
        public function __construct($manager, $id, $args = array('priority' => 0, 'width' => 100, 'class' => 'text')) {
            $this->width = isset($args['width']) ? $args['width'] : 100;
            unset($args['width']);
            $this->type = isset($args['class']) ? $args['class'] : 'text';
            unset($args['class']);
            parent::__construct($manager, $id, $args);
        }

        /**
        * Render the control's content.
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function render_content() {
        ?>
        <label style="<?php echo 'width: ' . $this->width . '%; float: left; display: inline-block;'; ?>">
            <span class="customize-control-title" style="font-weight: normal; font-style: italic; font-size: 85%;"><?php echo esc_html($this->label); ?></span>
            <input type="text" value="<?php echo esc_attr($this->value()); ?>" <?php $this->link(); ?> />
        </label>
        <?php
        }

    }

    /**
    * WP Customizer Address Control
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
    class Address_Control extends WP_Customize_Control {

        /**
        * Description for public
        * @var string 
        * @access public 
        */
        public $type = 'text';

        /**
        * Constructor.
        * 
        * @param object  $manager Parameter 
        * @param string $id      Parameter 
        * @param array   $args    Parameter 
        * @since 1.0
        * @return void    
        * @access public  
        */
        public function __construct($manager, $id, $args = array('priority' => 0)) {
            global $theme_namespace;
            parent::__construct($manager, $id, $args);
            $lines = array('street' => 100, 'city' => 100, 'state' => 50, 'zip' => 25);
            $_index = 0;
            foreach ($lines as $_line => $_width) {
                $setting_id = "{$id}[{$_line}]";
                $manager->add_control(
                new TextBox_Control($manager, $setting_id, array(
                'settings' => $setting_id,
                'label' => __(ucwords($_line), $theme_namespace),
                'section' => $args['section'],
                'type' => 'text',
                'width' => $_width,
                'priority' => $args['priority'] + $_index + 1,
                ))
                );
                $_index ++;
            }
        }

        /**
        * Render the control's content.
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function render_content() {
        ?>
        <label>
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
        </label>
        <?php
        }

    }

    /**
    * WP Customizer Social Media URL Control
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
    class Social_Control extends WP_Customize_Control {

        /**
        * Constructor.
        * 
        * @param object  $manager Parameter 
        * @param string $id      Parameter 
        * @param array   $args    Parameter 
        * @since 1.0
        * @return void    
        * @access public  
        */
        public function __construct($manager, $id, $args = array('priority' => 0)) {
            global $theme_namespace;
            $args['type'] = 'checkbox';
            parent::__construct($manager, "{$id}[enable]", $args);
            $profiles = array('Facebook', 'Twitter', 'LinkedIn', 'Google', 'Pinterest', 'Houzz', 'YouTube', 'Vimeo', 'Instagram', 'Flickr', 'RSS', 'Website');
            $_index = 0;
            foreach ($profiles as $_profile) {
                $setting_id = "{$id}[{$_profile}]";
                $manager->add_control(
                new TextBox_Control($manager, $setting_id, array(
                'settings' => $setting_id,
                'label' => __($_profile . ' URL', $theme_namespace),
                'section' => $args['section'],
                'type' => 'text',
                'width' => 100,
                'class' => 'text',
                'priority' => $args['priority'] + $_index + 1,
                ))
                );
                $_index ++;
            }
        }

    }

    /**
    * WP Customizer Google Plus Control
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
    class GPlus_Control extends WP_Customize_Control {

        /**
        * Description for public
        * @var string 
        * @access public 
        */
        public $type = 'select';

        /**
        * Constructor.
        * 
        * @param WP_Customize_Manager $manager Parameter 
        * @param string $id      Parameter 
        * @param array   $args    Parameter 
        * @since 1.0
        * @return void    
        * @access public  
        */
        public function __construct($manager, $id, $args = array('priority' => 0)) {
            global $theme_namespace;

            parent::__construct($manager, $id, $args);
        }

        /**
        * Render the control's content.
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function render_content() {
        ?>
        <label>
            <span class="customize-control-title" style="font-weight: normal; font-style: italic; font-size: 85%;"><?php echo esc_html($this->label); ?></span>
            <select style="width:75%;" class="at_responsive_gplus_chooser dropdown" <?php $this->link(); ?> data-selected-option="<?php echo esc_attr($this->value()); ?>">
                <option value="">Choose Post</option>
                <?php
                    if (empty($this->choices) === false) {
                        foreach ($this->choices as $label => $value)
                            echo '<option value="' . esc_attr($value) . '" ' . selected($this->value(), $label, false) . '>' . $label . '</option>';
                    }
                ?>
            </select>
        </label>
        <br style="width: 100%; clear: both; height: 0px;" />
        <a href="#" class="button-secondary upload at-validate-gplus" style="margin-top: 5px;"><?php _e('Validate'); ?></a>
        <script type="text/javascript">
            (function($) {
                $('.at-validate-gplus').on('click', function(e) {
                    e.preventDefault();
                    var test_post_url, vWindow;
                    var gsdt_url = "https://developers.google.com/structured-data/testing-tool?url=";
                    var raw_test_post_url = $('.at_responsive_gplus_chooser').val();
                    if (raw_test_post_url) {
                        test_post_url = decodeURIComponent(raw_test_post_url);
                        vWindow = window.open(gsdt_url + test_post_url, 'GPlus Validation', 'scrollbars=no,width=1096,height=800,left=0,top=0');
                        vWindow.creator = self;
                        vWindow.focus();
                    }
                    return false;
                });
            })(jQuery);
        </script>
        <?php
        }

    }

    /**
    * WP Customizer Theme Preset Control
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
    class Preset_Control extends WP_Customize_Control {

        /**
        * Description for public
        * @var string 
        * @access public 
        */
        public $type = 'presets';

        /**
        * Description for protected
        * @var array     
        * @access protected 
        */
        protected $tabs = array();

        /**
        * Add Tab UI Element to Control.
        * 
        * @param string $id       Parameter 
        * @param string $label    Parameter 
        * @param callback $callback Parameter 
        * @since 1.0
        * @return void    
        * @access public  
        */
        public function add_tab($id, $label, $callback) {
            $this->tabs[$id] = array(
            'label' => $label,
            'callback' => $callback,
            );
        }

        /**
        * Render the control's content.
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function render_content() {
        ?>
        <label>
            <span class="customize-control-title" style="font-weight: normal; font-style: italic; font-size: 85%;"><?php echo esc_html($this->label); ?></span>
            <select style="width:75%;" class="at_responsive_preset_chooser dropdown" <?php $this->link(); ?> data-selected-option="<?php echo esc_attr($this->value()); ?>">
                <option value="">Choose Preset</option>
                <?php
                    if (empty($this->choices) === false) {
                        foreach ($this->choices as $label => $value)
                            echo '<option ' . selected($this->value(), $label, false) . '>' . $label . '</option>';
                    }
                ?>
            </select>
            <a href="#" class="at-merge-preset" style="vertical-align: bottom; text-decoration: underline; margin-left: 10px; line-height: 32px; float: left;"><?php _e('Merge'); ?></a> 
            <a href="#" class="at-edit-preset" style="vertical-align: bottom; text-decoration: underline; margin-left: 10px; line-height: 32px; float: left;"><?php _e('Edit'); ?></a> 
        </label>
        <br style="width: 100%; clear: both; height: 0px;" />
        <a href="#" class="button-secondary upload at-new-preset" style="margin-top: 5px;"><?php _e('Add New'); ?></a>
        <div class="library">
            <ul>
                <?php foreach ($this->tabs as $id => $tab): ?>
                    <li data-customize-tab='<?php echo esc_attr($id); ?>' tabindex='0'>
                        <?php echo esc_html($tab['label']); ?>
                    </li>
                    <?php endforeach; ?>
            </ul>
            <?php foreach ($this->tabs as $id => $tab): ?>
                <div class="library-content" data-customize-tab='<?php echo esc_attr($id); ?>'>
                    <?php call_user_func($tab['callback']); ?>
                </div>
                <?php endforeach; ?>
        </div>
        <?php
        }

    }

    /**
    * WP Customizer Style Preset Control
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
    class Styles_Control extends WP_Customize_Control {

        /**
        * Description for public
        * @var string 
        * @access public 
        */
        public $type = 'styles';

        /**
        * Render the control's content.
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function render_content() {
        ?>
        <input type="hidden" class="at-presets-css" value="<?php echo esc_attr($this->value()); ?>" <?php $this->link(); ?> />
        <?php
        }

    }

    /**
    * WP Customizer Settings Reset Control
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
    class Reset_Control extends WP_Customize_Control {

        /**
        * Description for public
        * @var string 
        * @access public 
        */
        public $type = 'button';

        /**
        * Render the control's content.
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function render_content() {
        ?>
        <label>
            <div>
                <a href="#" class="button-secondary upload at-reset-settings"><?php _e('Reset Settings'); ?></a>
            </div>
            <input type="hidden" value="" <?php $this->link(); ?> />
            <span class="at-reset-info customize-control-title"></span>
        </label>
        <?php
        }

    }

    /**
    * WP Customizer Image Uploader Control
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
    class AT_Image_Control extends WP_Customize_Image_Control {

        /**
        * Constructor.
        *
        * @since 3.4.0 
        * @uses WP_Customize_Image_Control::__construct() 
        *        
        * @param WP_Customize_Manager $manager 
        */
        public function __construct($manager, $id, $args = array()) {
            parent::__construct($manager, $id, $args);
        }

        /**
        * Search for images within the defined context
        * 
        * @since 1.0
        * @return unknown Return 
        * @access public  
        */
        public function tab_uploaded() {
            $my_context_uploads = get_posts(array(
            'post_type' => 'attachment',
            'meta_key' => '_wp_attachment_context',
            'meta_value' => $this->context,
            'orderby' => 'post_date',
            'nopaging' => true,
            ));
        ?>

        <div class="uploaded-target"></div>

        <?php
            if (empty($my_context_uploads))
                return;

            foreach ((array) $my_context_uploads as $my_context_upload) {
                $this->print_tab_image(esc_url_raw($my_context_upload->guid));
            }
        }

    }

    /**
    * WP Customizer Favicon Control
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
    class Favicon_Control extends WP_Customize_Image_Control {

        /**
        * Description for public
        * @var unknown 
        * @access public  
        */
        public $setting_id;

        /**
        * Constructor.
        * 
        * @param WP_Customize_Manager $manager Parameter 
        * @param string $id      Parameter 
        * @param array   $args    Parameter 
        * @since 1.0
        * @return void    
        * @access public  
        */
        public function __construct($manager, $id, $args = array()) {
            $this->extensions[] = 'ico';
            $this->setting_id = $id;
            parent::__construct($manager, $id, $args);
        }

        /**
        * Search for images within the defined context
        * 
        * @since 1.0
        * @return unknown Return 
        * @access public  
        */
        public function tab_uploaded() {
            $my_context_uploads = get_posts(array(
            'post_type' => 'attachment',
            'meta_key' => '_wp_attachment_context',
            'meta_value' => $this->context,
            'orderby' => 'post_date',
            'nopaging' => true,
            ));
        ?>

        <div class="uploaded-target"></div>

        <?php
            if (empty($my_context_uploads))
                return;
        ?>
        <div class="uploaded-favicons" data-controller="<?php esc_attr_e($this->setting_id); ?>">
            <?php
                foreach ((array) $my_context_uploads as $my_context_upload) {
                    $this->print_tab_image(esc_url_raw($my_context_upload->guid));
                }
            ?>
        </div>
        <?php
        }

    }

    /**
    * WP Customizer Textarea Control
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
    class TextArea_Control extends WP_Customize_Control {

        /**
        * Description for public
        * @var string 
        * @access public 
        */
        public $type = 'textarea';

        /**
        * Render the control's content.
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function render_content() {
        ?>
        <label>
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea($this->value()); ?></textarea>
        </label>
        <?php
        }

    }

    /**
    * WP Customizer Import/Export Control
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
    class Transfer_Control extends WP_Customize_Upload_Control {

        /**
        * Description for public
        * @var string 
        * @access public 
        */
        public $type = 'transfer';

        /**
        * Description for protected
        * @var array     
        * @access protected 
        */
        protected $tabs = array();

        /**
        * Constructor.
        * 
        * @param WP_Customize_Manager $manager Parameter 
        * @param string $id      Parameter 
        * @param array   $args    Parameter 
        * @since 1.0
        * @return void    
        * @access public  
        */
        public function __construct($manager, $id, $args = array()) {
            $this->extensions[] = 'motif';
            $this->setting_id = $id;
            parent::__construct($manager, $id, $args);
        }

        /**
        * Add Tab UI Element to Control.
        * 
        * @param string $id       Parameter 
        * @param string $label    Parameter 
        * @param callback $callback Parameter 
        * @since 1.0
        * @return void    
        * @access public  
        */
        public function add_tab($id, $label, $callback) {
            $this->tabs[$id] = array(
            'label' => $label,
            'callback' => $callback,
            );
        }

        /**
        * Render the control's content.
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function render_content() {
            $i = 0;
        ?>
        <label>
            <span class="customize-control-title" style="font-weight: normal; font-style: italic; font-size: 85%;"><?php echo esc_html($this->label); ?></span>
        </label>
        <br style="width: 100%; clear: both; height: 0px;" />
        <input type="hidden" value="" <?php $this->link(); ?> />
        <span class="at-transfer-info customize-control-title"></span>
        <div class="library">
            <ul>
                <?php foreach ($this->tabs as $id => $tab): ?>
                    <li data-customize-tab='<?php echo esc_attr($id); ?>' tabindex='<?php echo $i; ?>'>
                        <?php echo esc_html($tab['label']); ?>
                    </li>
                    <?php
                        $i ++;
                        endforeach;
                ?>
            </ul>
            <?php foreach ($this->tabs as $id => $tab): ?>
                <div class="library-content" data-customize-tab='<?php echo esc_attr($id); ?>'>
                    <?php call_user_func($tab['callback']); ?>
                </div>
                <?php endforeach; ?>
        </div>
        <?php
        }

    }

    /**
    * WP Customizer Hidden Control
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
    class Hidden_Control extends WP_Customize_Control {

        /**
        * Description for public
        * @var string 
        * @access public 
        */
        public $type = 'hidden';

        /**
        * Render the control's content.
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        public function render_content() {
        ?>
        <input type="hidden" <?php $this->link(); ?> />
        <?php
        }

    }

?>
