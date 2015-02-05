<?php
/**
 * ArsTropica  Responsive Framework at_related_posts_plugin.php
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
 * Related Posts Plugin Class
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
class at_related_posts {

    /**
     * Description for static
     * @var object 
     * @access public 
     * @static 
     */
    static $rp_query;

    /**
     * Constructor.
     * 
     * @since 1.0
     * @return void   
     * @access public 
     */
    function __construct() {
        
    }

    /**
     * Initialization
     * 
     * @param integer $limit number of posts returned
     * @since 1.0
     * @return void    
     * @access private 
     */
    function _init($limit = 4) {
        $this->get_related_posts($limit);
    }

    /**
     * Return Related Posts Query Object
     * 
     * @param integer $limit Parameter 
     * @since 1.0
     * @return object  Return 
     * @access public  
     */
    function get_related_posts($limit = 4) {
        if (!self::$rp_query) {
            self::$rp_query = $this->_get_related_posts($limit);
        }
        return self::$rp_query;
    }

    /**
     * Check for Related Posts
     * 
     * @since 1.0
     * @return mixed  Return 
     * @access public 
     */
    function has_related_posts() {
        if (!self::$rp_query) {
            self::$rp_query = $this->_get_related_posts();
        }
        return self::$rp_query->post_count > 0;
    }

    /**
     * Do Related Posts Query
     * 
     * @param integer $limit Parameter 
     * @since 1.0
     * @return mixed   Return 
     * @access private 
     */
    private function _get_related_posts($limit = 4) {
        $post = get_post();

        // Support for the Yet Another Related Posts Plugin
        if (function_exists('yarpp_get_related')) {
            $related = yarpp_get_related(array('limit' => $limit), $post->ID);
            return new WP_Query(array(
                'post__in' => wp_list_pluck($related, 'ID'),
                'posts_per_page' => $limit,
                'showposts' => $limit,
                'ignore_sticky_posts' => false,
                'post__not_in' => array($post->ID),
            ));
        }

        $args = array(
            'posts_per_page' => $limit,
            'ignore_sticky_posts' => true,
            'post__not_in' => array($post->ID),
        );

        // Get posts from the same category.
        $categories = get_the_category();
        if (!empty($categories)) {
            $category = array_shift($categories);
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'category',
                    'field' => 'id',
                    'terms' => $category->term_id,
                ),
            );
        }

        return new WP_Query($args);
    }

    /**
     * Related Posts Display
     * 
     * @param boolean $show_excerpt Parameter 
     * @param boolean $cols         Parameter 
     * @since 1.0
     * @return void    
     * @access public  
     */
    function do_related_posts_widget($show_excerpt = false, $cols = false) {
        global $theme_namespace;
        if (!self::$rp_query)
            $this->_init();
        $related_posts = self::$rp_query;
        $grid_values = at_responsive_get_content_grid_values();
        $grid_classes = at_responsive_get_content_grid_classes();
        if ($cols) {
            $column_width = $cols;
        } else {
            $column_width = isset($grid_values['row']) ? $grid_values['row'] : 12;
        }

        if ($related_posts && @$related_posts->have_posts()) :
            $post_count = $related_posts->post_count;
            $grid_columns = floor(12 / $post_count);
            $uniqid = uniqid();
            ?>
            <aside id="widget_at_related_posts_<?php echo $uniqid; ?>" class="col-md-<?php echo $column_width; ?> <?php echo $grid_classes['row']; ?> widget at_widget at-related-posts-widget" role="complementary">
                <div class="widget-frame">
                    <h4 class="widgettitle dotted">
                        <span class="hidden-xs">Related Posts</span>
                        <a href="#related-posts-container-<?php echo $uniqid; ?>" title="Related Posts" data-toggle="collapse" data-parent="#widget_at_related_posts_<?php echo $uniqid; ?>" class="visible-xs mobile-related-posts-title">Related Posts<span class="pull-right"><span class="glyphicon glyphicon-chevron-down"></span></span></a>
                    </h4>
                    <div class="widget-wrap">
                        <div class="widget-content">
                            <div id="related-posts-container-<?php echo $uniqid; ?>" class="widget-related-posts-container collapse in">
                                <div class="at-related-posts-thumbnails-horizontal row eq-parent">
                                    <?php foreach ($related_posts->posts as $post) : ?>
                                        <?php
                                        $post_title = get_the_title($post->ID);
                                        $thumb_attr = array('class' => 'img-responsive', 'alt' => trim(strip_tags($post_title)));
                                        $thumb_img = get_the_post_thumbnail($post->ID, 'entry-thumbnail', $thumb_attr);
                                        $post_excerpt = substr(str_replace(']]>', ']]&gt;', strip_tags(apply_filters('the_content', $post->post_content))), 0, 50) . "&hellip;";
                                        ;
                                        $post_link = get_permalink($post->ID);
                                        ?>
                                        <div class="at-related-posts-col col-md-3 col-sm-6 co-xs-12 eq-height">
                                            <a class="at-related-posts-thumbnail" href="<?php echo $post_link; ?>" title="<?php _e($post_title, $theme_namespace); ?>">
                                                <span class="thumbnail"><?php echo $thumb_img; ?></span>
                                                <span class="at-related-posts-thumbnail-title"><?php _e($post_title, $theme_namespace); ?></span>
                                            </a>
                                            <?php if ($show_excerpt) : ?><div class="at-related-posts-thumbnail-excerpt"><?php echo wpautop($post_excerpt); ?></div><?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
            <script type="text/javascript">
                (function($) {
                    $(window).on('load resize', function(e) {
                        var $collapse = $('.widget-related-posts-container.collapse');
                        var $toggle = $collapse.closest('.widget').find('.mobile-related-posts-title');
                        if ($collapse.closest('.ltTabletTall').length > 0) {
                            $collapse.collapse('hide');
                            $toggle.find('.glyphicon').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
                        } else {
                            // $collapse.collapse('show');
                            // $toggle.find('.glyphicon').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
                        }
                    });
                    $('.widget-related-posts-container.collapse')
                            .on('shown.bs.collapse', function() {
                                $(this).closest(".widget").find(".glyphicon-chevron-down").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-up");
                            })
                            .on('hidden.bs.collapse', function() {
                                $(this).closest(".widget").find(".glyphicon-chevron-up").removeClass("glyphicon-chevron-up").addClass("glyphicon-chevron-down");
                            });
                })(jQuery);
            </script>
            <?php
        endif;
    }

}

// Enable Plugin

/**
 * Enable Plugin, if set in Theme Settings
 * 
 * @since 1.0
 * @return void 
 */
function at_related_posts_enable() {
    global $at_theme_custom, $at_related_posts;
    if (class_exists('at_responsive_theme_mod')) {
        if (!is_object($at_theme_custom)) {
            $at_theme_custom = new at_responsive_theme_mod();
        }
        $enable_plugin = $at_theme_custom->get_option('settings/enableyarpp', false);
        if ($enable_plugin) {
            $at_related_posts = new at_related_posts();
        }
    }
}

add_action('init', 'at_related_posts_enable', 10);
?>
