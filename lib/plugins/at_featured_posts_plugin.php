<?php
    /**
    * ArsTropica  Responsive Framework at_featured_posts_plugin.php
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
    * ArsTropica  Featured Posts Widget
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
    class AT_Featured_Posts_Widget extends WP_Widget {

        /**
        * Constructor.
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        function AT_Featured_Posts_Widget() {
            add_action('wp_footer', array(&$this, 'load_footer_js'));
            $widget_ops = array('classname' => 'AT_Featured_Posts_Widget', 'description' => 'Display featured posts from a select category');
            $this->WP_Widget('AT_Featured_Posts_Widget', 'ArsTropica  Featured Posts', $widget_ops);
        }

        /**
        * Echo the settings update form.
        * 
        * @param array $instance Parameter 
        * @since 1.0
        * @return void    
        * @access public  
        */
        function form($instance) {
            global $theme_namespace;
            $instance = wp_parse_args((array) $instance, array('title' => 'Featured Posts', 'category' => '', 'showthumbnail' => 'on', 'showexcerpt' => null, 'showfullexcerpt' => null, 'showmeta' => null, 'limit' => 4, 'orderby' => 'modified', 'orientation' => 'vertical', 'homepage' => null));
            extract($instance);
            $cats = get_terms('category');
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: </label><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
        <p><label for="<?php echo $this->get_field_id('category'); ?>">Category:  </label><select id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>" class="widefat"><option value=""<?php selected("", $category); ?>>All Categories</option><?php foreach ($cats as $cat) : ?><option value="<?php echo $cat->term_id; ?>" <?php selected($cat->term_id, $category); ?>><?php echo $cat->name; ?></option><?php endforeach; ?></select></p>
        <p><label for="<?php echo $this->get_field_id('orientation'); ?>">Widget Layout:  </label><select id="<?php echo $this->get_field_id('orientation'); ?>" name="<?php echo $this->get_field_name('orientation'); ?>" class="widefat"><option value="vertical" <?php selected("vertical", $orientation); ?>>Vertical</option><option value="horizontal" <?php selected("horizontal", $orientation); ?>>Horizontal</option><option value="grid" <?php selected("grid", $orientation); ?>>Grid</option><option value="theme" <?php selected("theme", $orientation); ?>>Theme Template</option></select></p>
        <p><label for="<?php echo $this->get_field_id('orderby'); ?>">Order By:  </label><select id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" class="widefat"><option value="modified" <?php selected("modified", $orderby); ?>>Last Modified Date</option><option value="title" <?php selected("title", $orderby); ?>>Title</option><option value="rand" <?php selected("rand", $orderby); ?>>Random</option></select></p>
        <p><label for="<?php echo $this->get_field_id('limit'); ?>">Number of posts to show:</label><input id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo esc_attr($limit); ?>" size="3" /></p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('showthumbnail'); ?>" name="<?php echo $this->get_field_name('showthumbnail'); ?>" <?php checked($showthumbnail, 'on'); ?> />
            <label for="<?php echo $this->get_field_id('showthumbnail'); ?>"><?php _e('Display Thumbnails', $theme_namespace); ?></label>
            <br>
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('showexcerpt'); ?>" name="<?php echo $this->get_field_name('showexcerpt'); ?>" <?php checked($showexcerpt, 'on'); ?> />
            <label for="<?php echo $this->get_field_id('showexcerpt'); ?>"><?php _e('Display Excerpt', $theme_namespace); ?></label>
            <br>
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('showfullexcerpt'); ?>" name="<?php echo $this->get_field_name('showfullexcerpt'); ?>" <?php checked($showfullexcerpt, 'on'); ?> />
            <label for="<?php echo $this->get_field_id('showfullexcerpt'); ?>"><?php _e('Display Full Excerpt', $theme_namespace); ?></label>
            <br>
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('showmeta'); ?>" name="<?php echo $this->get_field_name('showmeta'); ?>" <?php checked($showmeta, 'on'); ?> />
            <label for="<?php echo $this->get_field_id('showmeta'); ?>"><?php _e('Display Post Meta', $theme_namespace); ?></label>
            <br>
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('homepage'); ?>" name="<?php echo $this->get_field_name('homepage'); ?>" <?php checked($homepage, 'on'); ?> />
            <label for="<?php echo $this->get_field_id('homepage'); ?>"><?php _e('Display on Front Page Only', $theme_namespace); ?></label>
        </p>
        <?php
        }

        /**
        * Update a particular instance.
        * 
        * @param array   $new_instance Parameter 
        * @param array $old_instance Parameter 
        * @since 1.0
        * @return array   Return 
        * @access public  
        */
        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title'] = isset($new_instance['title']) ? $new_instance['title'] : '';
            $instance['category'] = isset($new_instance['category']) ? $new_instance['category'] : '';
            $instance['showthumbnail'] = $new_instance['showthumbnail'];
            $instance['showexcerpt'] = $new_instance['showexcerpt'];
            $instance['showfullexcerpt'] = $new_instance['showfullexcerpt'];
            $instance['showmeta'] = $new_instance['showmeta'];
            $instance['orderby'] = isset($new_instance['orderby']) ? $new_instance['orderby'] : 'modified';
            $instance['limit'] = isset($new_instance['limit']) ? $new_instance['limit'] : 4;
            $instance['orientation'] = isset($new_instance['orientation']) ? $new_instance['orientation'] : 'vertical';
            $instance['homepage'] = $new_instance['homepage'];
            return $instance;
        }

        /**
        * Echo the widget content.
        * 
        * @param array $args     Parameter 
        * @param array   $instance Parameter 
        * @since 1.0
        * @return unknown Return 
        * @access public  
        */
        function widget($args, $instance) {
            global $theme_namespace, $at_theme_custom, $wp_query;
            $main_wp_query = clone $wp_query;

            extract($args, EXTR_SKIP);
            $widget_number = $this->number;

            $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
            $category = $instance['category'];
            $showthumbnail = $instance['showthumbnail'];
            $showexcerpt = $instance['showexcerpt'];
            $showfullexcerpt = $instance['showfullexcerpt'];
            $showmeta = $instance['showmeta'];
            $limit = $instance['limit'];
            $orientation = $instance['orientation'];
            $homepage = $instance['homepage'];

            if ($homepage && ((!is_home() ) && (!is_front_page()) || is_paged()))
                return;

            echo $before_widget;

            $mobile_toggle = '<a href="#featured-posts-container-' . $widget_number . '" title="featured Posts" data-toggle="collapse" data-parent="#widget_at_featured_posts_' . $widget_number . '" class="visible-xs mobile-featured-posts-title">' . (("" != trim(strip_tags($title))) ? $title : 'Featured Posts') . '<span class="pull-right"><span class="glyphicon glyphicon-chevron-down"></span></span></a>';

            if (!empty($title))
                echo $before_title . '<span class="hidden-xs">' . $title . '</span>' . $mobile_toggle . $after_title;
            else
                echo $before_title . $mobile_toggle . $after_title;

            $featured_posts = $this->_get_featured_posts();
            if ($featured_posts && @$featured_posts->have_posts()) :
                $wp_query = $featured_posts;
                $post_count = $featured_posts->post_count;
                $grid_columns = floor(12 / $post_count);
                $post_counter = 0;
            ?>
            <div id="featured-posts-container-<?php echo $widget_number; ?>" class="widget-featured-posts-container collapse in">
                <?php
                    switch ($orientation) {
                        case 'theme' : {
                        ?>
                        <?php if ($featured_posts->have_posts()) : ?>
                            <div class="at-featured-posts-thumbnails at-featured-posts-thumbnails-theme row eq-parent content-row post-wrapper">
                                <?php while ($featured_posts->have_posts()) : $featured_posts->the_post(); ?>
                                    <?php
                                        get_template_part('templates/content', 'featured');
                                    ?>
                                    <?php endwhile; ?>
                            </div>
                            <?php
                                endif;
                            wp_reset_postdata();
                            break;
                        }
                        default: {
                            $this->_display_template($featured_posts, $orientation, $showthumbnail, $showexcerpt, $showfullexcerpt, $showmeta, $limit);
                            break;
                        }
                    }
                ?>
            </div>
            <?php
                $wp_query = $main_wp_query;
                endif;
            echo $after_widget;
        }

        /**
        * Check for featured Posts
        * 
        * @since 1.0
        * @return mixed  Return 
        * @access public 
        */
        function has_featured_posts() {
            $fp_query = $this->_get_featured_posts();
            return $fp_query->post_count > 0;
        }

        /**
        * Output Widget JS in Footer.
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        function load_footer_js() {
            $js = <<<SCR
<script type="text/javascript">
    (function($){
        $(window).on('load resize', function(e){
            var \$collapse = $('.widget-featured-posts-container.collapse');
            var \$toggle = \$collapse.closest('.widget').find('.mobile-featured-posts-title');
            if (\$collapse.closest('.ltTabletTall').length > 0) {
                // \$collapse.collapse('hide');
                // \$toggle.find('.glyphicon').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
            }
        });
        $('.widget-featured-posts-container.collapse')
        .on('shown.bs.collapse', function () {
            $(this).closest(".widget").find(".glyphicon-chevron-down").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-up");
        })
        .on('hidden.bs.collapse', function () {
            $(this).closest(".widget").find(".glyphicon-chevron-up").removeClass("glyphicon-chevron-up").addClass("glyphicon-chevron-down");
        });                                            
    })(jQuery);
</script>
SCR;
            if (is_active_widget(false, false, $this->id_base, true)) {
                echo $js;
            }
        }

        /**
        * Do featured Posts Query
        * 
        * @since 1.0
        * @return mixed   Return 
        * @access private 
        */
        private function _get_featured_posts() {
            $settings = $this->get_settings();
            $instance = $settings[$this->number];
            $cat = $instance['category'];
            $limit = $instance['limit'];
            $orderby = $instance['orderby'];
            $order = ($orderby == 'title') ? 'ASC' : 'DESC';

            $args = array(
                'posts_per_page' => $limit,
                'showposts' => $limit,
                'ignore_sticky_posts' => false,
                'orderby' => $orderby,
                'order' => $order,
            );
            if ($cat)
                $args['cat'] = $cat;

            return new WP_Query($args);
        }

        /**
        * Defualt Template
        * 
        * @param mixed   $featured_posts  Parameter 
        * @param string  $orientation     Parameter 
        * @param boolean $showthumbnail   Parameter 
        * @param boolean $showexcerpt     Parameter 
        * @param boolean $showfullexcerpt Parameter 
        * @param boolean $showmeta        Parameter 
        * @param integer $limit           Parameter 
        * @since 1.0
        * @return void    
        * @access private 
        */
        function _display_template($featured_posts = null, $orientation = 'horizontal', $showthumbnail = true, $showexcerpt = false, $showfullexcerpt = false, $showmeta = false, $limit = 4) {
            global $theme_namespace, $at_theme_custom;
            $post_counter = 0;
            if ($featured_posts) :
                if ($orientation == 'horizontal') :
                ?>
                <div class="at-featured-posts-thumbnails at-featured-posts-thumbnails-horizontal row">
                <?php elseif ($orientation == 'grid') : ?>
                <div class="at-featured-posts-thumbnails at-featured-posts-thumbnails-grid row eq-parent">
                    <?php else : ?>
                    <ul class="at-featured-posts-thumbnails at-featured-posts-thumbnails-vertical list">
                    <?php endif; ?>
                <?php foreach ($featured_posts->posts as $post) : ?>
                    <?php
                        $post_title = get_the_title($post->ID);
                        $thumb_attr = array('class' => 'img-responsive', 'alt' => trim(strip_tags($post_title)));
                        $thumb_img = get_the_post_thumbnail($post->ID, 'entry-thumbnail', $thumb_attr);
                        $excerpt_length = $at_theme_custom->get_option('settings/excerptlength', 55);
                        $post_excerpt = str_replace(']]>', ']]&gt;', strip_tags(apply_filters('the_content', $post->post_content)));

                        if ($showfullexcerpt) {
                            $words = explode(' ', $post_excerpt, $excerpt_length + 1);
                            if (count($words) > $excerpt_length) {
                                array_pop($words);
                                array_push($words, '&hellip;');
                                $_excerpt = implode(' ', $words);
                                $post_excerpt = apply_filters('at_responsive_excerpt', __($_excerpt, $theme_namespace));
                            }
                        } else {
                            $post_excerpt = substr(str_replace(']]>', ']]&gt;', strip_tags(apply_filters('the_content', $post->post_content))), 0, 50) . "&hellip;";
                        }
                        $post_link = get_permalink($post->ID);
                    ?>
                    <?php if ($orientation == 'horizontal') : ?>
                        <div class="at-featured-post at-featured-posts-col col-md-3 col-sm-6 co-xs-12">
                            <?php elseif ($orientation == 'grid') : ?>
                            <div class="at-featured-post at-featured-posts-col col-md-6 col-sm-12">
                                <div class="at-featured-post-wrapper eq-height">
                                    <?php else : ?>
                                    <li class="at-featured-post at-featured-posts-item">
                                    <?php endif; ?>
                                <?php if ($showthumbnail) : ?>
                                    <div class="thumbnail">
                                        <a class="at-featured-posts-thumbnail" href="<?php echo $post_link; ?>" title="<?php _e($post_title, $theme_namespace); ?>">
                                            <?php echo $thumb_img; ?>
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                <div class="at-featured-posts-text">
                                    <div class="at-featured-posts-header">
                                        <a href="<?php echo $post_link; ?>" title="<?php _e($post_title, $theme_namespace); ?>">
                                            <span class="at-featured-posts-thumbnail-title"><?php _e($post_title, $theme_namespace); ?></span>
                                        </a>
                                        <?php if ($showmeta && function_exists('at_responsive_get_post_entry')) : ?><div class="entry-meta"><?php echo at_responsive_get_post_entry($post); ?></div><?php endif; ?>
                                    </div>
                                    <?php if ($showexcerpt) : ?><div class="at-featured-posts-thumbnail-excerpt"><?php echo wpautop($post_excerpt); ?></div><?php endif; ?>
                                </div>
                                <?php if ($showmeta) : ?>
                                    <div class="entry-meta">
                                        <?php at_responsive_post_meta(); ?>
                                    </div>
                                    <?php if (!is_preview() && !at_responsive_is_customizer()) at_responsive_post_addthis($post); ?>
                                    <?php endif; ?>                                    
                                <?php if ($orientation == 'horizontal') : ?>
                                </div>
                                <?php elseif ($orientation == 'grid') : ?>
                            </div>
                        </div>
                        <?php else : ?>
                        <div style="width:100%; height: 0px; clear: both;"></div>
                        </li>
                        <?php endif; ?>
                    <?php $post_counter ++; ?>
                    <?php if ($orientation == 'horizontal' && $post_counter > 3) break; ?>
                    <?php endforeach; ?>
                <?php if ($orientation == 'horizontal') : ?>
                </div>
                <?php else : ?>
                </ul>
                <?php endif; ?>
            <?php
                endif;
        }

    }

    /**
    * Register ArsTropica  Featured Posts Wiget
    * 
    * @since 1.0
    * @return void 
    */
    function at_featured_posts_enable() {
        register_widget('AT_Featured_Posts_Widget');
    }

    add_action('widgets_init', 'at_featured_posts_enable', 10);
?>
