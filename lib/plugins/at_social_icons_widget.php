<?php
/**
 * ArsTropica  Responsive Framework at_social_icons_widget.php
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
 * Theme Social Media Icons Widget
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
class AT_Social_Icons_Widget extends WP_Widget {

    /**
     * Constructor.
     * 
     * @since 1.0
     * @return void   
     * @access public 
     */
    function AT_Social_Icons_Widget() {
        $widget_ops = array('classname' => 'AT_Social_Icons_Widget', 'description' => 'Display Theme Social Icons with this widget');
        $this->WP_Widget('AT_Social_Icons_Widget', 'Theme Social Icons', $widget_ops);
    }

    /**
     * Echo the settings update form
     * 
     * @param array $instance Current settings
     * @since 1.0
     * @return void   
     * @access public 
     */
    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => '', 'style' => ''));
        $title = $instance['title'];
        $style = $instance['style'];
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('style'); ?>">Style: <select id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>"><option value="circle"<?php selected("circle", $style); ?>>Circular</option><option value="open"<?php selected("open", $style); ?>>Open</option><option value="square"<?php selected("square", $style); ?>>Square</option></select></label></p>
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
        $instance['style'] = isset($new_instance['style']) ? $new_instance['style'] : '';
        return $instance;
    }

    /**
     * Echo the widget content.
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
        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);

        $mobile_toggle = '<span class="pull-right visible-xs"><span type="button" title="' . $title . '" data-parent="#' . $this->id . '" data-toggle="collapse" data-target="#mobile_' . $this->id . '"><span class="glyphicon glyphicon-chevron-up"></span></span></span>';

        echo '<div class="social-media">' . "\n";
        if (!empty($title))
            echo $before_title . $title . $mobile_toggle . $after_title;
        else
            echo $before_title . $mobile_toggle . $after_title;


        // front
        $style = empty($instance['style']) ? 'circular' : $instance['style'];
        $icon_classes = array(
            'base' => array(
                'Facebook' => 'icon-facebook',
                'Twitter' => 'icon-twitter',
                'LinkedIn' => 'icon-linkedin',
                'Google' => 'icon-gplus',
                'Pinterest' => 'icon-pinterest',
                'Houzz' => 'icon-houzz',
                'YouTube' => 'icon-youtube',
                'Vimeo' => 'icon-vimeo',
                'Instagram' => 'icon-instagram',
                'Flickr' => 'icon-flickr',
                'RSS' => 'icon-rss',
                'Website' => 'icon-globe',
            ),
            'fontello' => array(
                'Facebook' => 'icon-facebook',
                'Twitter' => 'icon-twitter',
                'LinkedIn' => 'icon-linkedin',
                'Google' => 'icon-gplus',
                'Pinterest' => 'icon-pinterest',
                'Houzz' => 'icon-houzz',
                'YouTube' => 'icon-youtube',
                'Vimeo' => 'icon-vimeo',
                'Instagram' => 'icon-instagram',
                'Flickr' => 'icon-flickr',
                'RSS' => 'icon-rss',
                'Website' => 'icon-globe',
            ),
            'fontello' => array(
                'Facebook' => 'icon-facebook',
                'Twitter' => 'icon-twitter',
                'LinkedIn' => 'icon-linkedin',
                'Google' => 'icon-gplus',
                'Pinterest' => 'icon-pinterest',
                'Houzz' => 'icon-houzz',
                'YouTube' => 'icon-youtube',
                'Vimeo' => 'icon-vimeo',
                'Instagram' => 'icon-instagram',
                'Flickr' => 'icon-flickr',
                'RSS' => 'icon-rss',
                'Website' => 'icon-globe',
            ),
        );
        $base_class = 'social-icon ';
        $social_profiles = $at_theme_custom->get_option('social/widget');
        $social_profiles = array_filter($social_profiles);
        if ($social_profiles) {
            unset($social_profiles['enable']);
            if ($social_profiles) {
                echo '<ul class="social-icons-sidebar hidden-xs">' . "\n";
                foreach ($social_profiles as $service_name => $url) {
                    $link = '<li>';
                    $link .= '<a href="' . $url . '" target="_blank" title="Connect to ' . esc_attr(get_bloginfo('name')) . ' via ' . $service_name . '">';
                    switch ($style) {
                        default :
                        case 'open' : {
                                $link .= '<i class="' . $base_class . $icon_classes['base'][$service_name] . ' ' . $style . ' fontello ' . $icon_classes['fontello'][$service_name] . '-open"></i>';
                                break;
                            }
                        case 'square' : {
                                $link .= '<i class="' . $base_class . $icon_classes['base'][$service_name] . ' ' . $style . ' fontello ' . $icon_classes['fontello'][$service_name] . '-squared"></i>';
                                break;
                            }
                        case 'circle' : {
                                $link .= '<i class="' . $base_class . $icon_classes['base'][$service_name] . ' ' . $style . ' fontello ' . $icon_classes['fontello'][$service_name] . '-circular"></i>';
                                break;
                            }
                    }
                    $link .= '<span class="screen-reader-text">' . $service_name . '</span></a>';
                    $link .= '</li>';
                    echo $link . "\n";
                }
                echo '</ul>' . "\n";
                echo '<div class="visible-xs panel-collapse collapse in" id="mobile_' . $this->id . '">' . "\n";
                echo "<div class=\"table-responsive borderless\">\n";
                echo "<table class=\"table table-hover social-icons-sidebar-mobile\">\n";
                echo "<tbody>\n";
                foreach ($social_profiles as $service_name => $url) {
                    $link = '<a class="' . $base_class . $icon_classes['base'][$service_name] . '" href="' . $url . '" target="_blank" title="Connect to ' . esc_attr(get_bloginfo('name')) . ' via ' . $service_name . '">';
                    $link .= ucwords($service_name);
                    $link .= '</a>';
                    echo "<tr>\n";
                    echo "<td>$link</td>\n";
                    echo "</tr>\n";
                }
                echo "</tbody>\n";
                echo "</table>\n";
                echo "</div>\n";
                echo '</div>' . "\n";
                echo "<script type=\"text/javascript\">\n";
                echo "(function(\$){\n";
                echo "\$('#mobile_" . $this->id . ".panel-collapse')\n";
                echo ".on('shown.bs.collapse', function () {\n";
                echo "\$(this).closest(\".widget\").find(\".glyphicon-chevron-down\").removeClass(\"glyphicon-chevron-down\").addClass(\"glyphicon-chevron-up\");\n";
                echo "})\n";
                echo ".on('hidden.bs.collapse', function () {\n";
                echo "\$(this).closest(\".widget\").find(\".glyphicon-chevron-up\").removeClass(\"glyphicon-chevron-up\").addClass(\"glyphicon-chevron-down\");\n";
                echo "});\n";
                echo "})(jQuery);\n";
                echo "</script>\n";
            }
        }

        echo '</div>' . "\n";
        echo $after_widget;
    }

}

/**
 * Register and load the widget, if enabled in Theme Settings.
 * 
 * @since 1.0
 * @return void 
 */
function at_social_icons_load_widget() {
    global $at_theme_custom;
    if (class_exists('at_responsive_theme_mod')) {
        if (!is_object($at_theme_custom)) {
            $at_theme_custom = new at_responsive_theme_mod();
        }
        $enable_widget = $at_theme_custom->get_option('social/widget/enable', false);
        if ($enable_widget) {
            register_widget('AT_Social_Icons_Widget');
        }
    }
}

add_action('widgets_init', 'at_social_icons_load_widget', 10);
?>