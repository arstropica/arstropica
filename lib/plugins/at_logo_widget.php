<?php
/**
 * ArsTropica  Responsive Framework at_logo_widget.php
 * 
 * PHP version 5
 * 
 * @category   Theme WordPress Plugins 
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
 * Company Logo Widget
 * 
 * @category   Theme WordPress Plugins 
 * @package    WordPress
 * @author     ArsTropica <info@arstropica.com> 
 * @copyright  2014 ArsTropica 
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License 
 * @version    Release: @package_version@ 
 * @link       http://pear.php.net/package/ArsTropica  Reponsive Framework
 * @subpackage ArsTropica  Responsive Framework
 * @see        References to other sections (if any)...
 */
class AT_Logo_Widget extends WP_Widget {

    /**
     * Widget Constructor.
     * 
     * @since 1.0
     * @return void   
     * @access public 
     */
    function AT_Logo_Widget() {
        $widget_ops = array('classname' => 'AT_Logo_Widget', 'description' => 'Display Logo Link to Website with this widget');
        $this->WP_Widget('AT_Logo_Widget', 'Company Logo &amp; Link', $widget_ops);
    }

    /**
     * Echo the settings update form
     * 
     * @param array  $instance Current settings
     * @since 1.0
     * @return void   
     * @access public 
     */
    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => ''));
        $title = $instance['title'];
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
        <?php
    }

    /**
     * Update a particular instance.
     * 
     * @param array $new_instance New settings for this instance as input by the user via form()
     * @param array $old_instance Old settings for this instance
     * @since 1.0
     * @return array Settings to save or bool false to cancel saving
     * @access public  
     */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = isset($new_instance['title']) ? $new_instance['title'] : '';
        return $instance;
    }

    /**
     * Echo the widget content
     * 
     * @param array $args Display arguments including before_title, after_title, before_widget, and after_widget.
     * @param array $instance The settings for the particular instance of the widget
     * @since 1.0
     * @return void    
     * @access public  
     */
    function widget($args, $instance) {
        global $at_theme_custom;
        extract($args, EXTR_SKIP);

        echo $before_widget;
        $title = empty($instance['title']) ? false : apply_filters('widget_title', $instance['title']);

        if (!empty($title))
            echo $before_title . $title . $after_title;;


        // front
        $company_link = $at_theme_custom->get_option('social/widget/Website');
        $company_logo = $at_theme_custom->get_option('images/companylogo');
        if ($company_link) {
            // echo '<a href="' . $company_link . '"target="_blank" title="' . ($title ? $title : 'Visit our Website!') . '" style="display: block; margin: 0 auto; max-width: 99.8%" class="at-logo-container">' . ($company_logo ? '<img src="' . $company_logo . '" alt="' . (esc_attr(get_bloginfo('name'))) . '" />' : get_bloginfo('name')) . '<span class="screen-reader-text">' . ($title ? $title : 'Visit our Website!') . '</span></a>';
            echo '<a href="' . $company_link . '"target="_blank" title="' . ($title ? $title : 'Visit our Website!') . '" style="display: block; width: 90%; height: 100%; margin: 0 auto; min-height: 82px;' . ($company_logo ? ' background-image: url(' . $company_logo . '); background-repeat: no-repeat; background-position: center; background-size: contain;' : '') . '" class="at-logo-container">&nbsp;<span class="screen-reader-text">' . ($title ? $title : 'Visit our Website!') . '</span></a>';
        }

        echo $after_widget;
    }

}

/**
 * Register and load the widget, if enabled in Theme Settings
 * 
 * @since 1.0
 * @return void 
 */
function at_logo_load_widget() {
    global $at_theme_custom;
    if (class_exists('at_responsive_theme_mod')) {
        if (!is_object($at_theme_custom)) {
            $at_theme_custom = new at_responsive_theme_mod();
        }
        $enable_widget = $at_theme_custom->get_option('social/widget/Website', false);
        if ($enable_widget) {
            register_widget('AT_Logo_Widget');
        }
    }
}

add_action('widgets_init', 'at_logo_load_widget', 10);
?>