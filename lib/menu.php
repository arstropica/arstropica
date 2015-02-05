<?php

/**
 * ArsTropica  Reponsive Framework Menu Class
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
 * Class Name: at_responsive_menu_walker
 * GitHub URI: https://github.com/twittem/wp-bootstrap-navwalker
 * Description: A custom WordPress nav walker class to implement the Bootstrap 3 navigation style in a custom theme using the WordPress built in menu manager.
 * Version: 2.0.4
 * Author: Edward McIntyre - @twittem
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
class at_responsive_menu_walker extends Walker_Nav_Menu {

    /**
     * @see Walker::start_lvl() 
     * @since 3.0.0 
     *        
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of page. Used for padding.
     */
    public function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $c = apply_filters('at_responsive_dropdown_menu_classes', array('dropdown-menu'));

        $output .= apply_filters("at_responsive_child_menu_start_lvl", "\n{$indent}<ul role=\"menu\" class=\"" . implode(' ', $c) . "\">\n", $indent);
    }

    /**
     * Ends the list of after the elements are added.
     *
     * @see Walker::end_lvl() 
     *        
     * @since 3.0.0 
     *        
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    function end_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= apply_filters("at_responsive_child_menu_end_lvl", "$indent</ul>\n", $indent);
    }

    /**
     * @see Walker::start_el() 
     * @since 3.0.0 
     *        
     * @param string $output       Passed by reference. Used to append additional content.
     * @param object $item         Menu item data object.
     * @param int    $depth        Depth of menu item. Used for padding.
     * @param int    $current_page Menu item ID.
     * @param array  $args         
     */
    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $indent = ( $depth ) ? str_repeat("\t", $depth) : '';

        /**
         * Dividers, Headers or Disabled
         * =============================
         * Determine whether the item is a Divider, Header, Disabled or regular
         * menu item. To prevent errors we use the strcasecmp() function to so a
         * comparison that is not case sensitive. The strcasecmp() function returns
         * a 0 if the strings are equal.
         */
        if (strcasecmp($item->attr_title, 'divider') == 0 && $depth === 1) {
            $output .= $indent . '<li role="presentation" class="divider">';
        } else if (strcasecmp($item->title, 'divider') == 0 && $depth === 1) {
            $output .= $indent . '<li role="presentation" class="divider">';
        } else if (strcasecmp($item->attr_title, 'dropdown-header') == 0 && $depth === 1) {
            $output .= $indent . '<li role="presentation" class="dropdown-header">' . esc_attr($item->title);
        } else if (strcasecmp($item->attr_title, 'disabled') == 0) {
            $output .= $indent . '<li role="presentation" class="disabled"><a href="#">' . esc_attr($item->title) . '</a>';
        } else {

            $class_names = $value = '';

            $classes = empty($item->classes) ? array() : (array) $item->classes;
            $classes[] = 'menu-item-' . $item->ID;

            $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));

            $args = apply_filters('at_responsive_nav_menu_args', $args, $item);

            if (@$args->has_children)
                $class_names .= ' dropdown';

            if (in_array('current-menu-item', $classes))
                $class_names .= ' active';

            $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

            $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
            $id = $id ? ' id="' . esc_attr($id) . '"' : '';

            $output .= $indent . '<li' . $id . $value . $class_names . '>';

            $atts = array();
            $atts['title'] = !empty($item->title) ? $item->title : '';
            $atts['target'] = !empty($item->target) ? $item->target : '';
            $atts['rel'] = !empty($item->xfn) ? $item->xfn : '';

            // If item has_children add atts to a.
            if (@$args->has_children && $depth === 0) {
                if (@$args->touch)
                    $atts['href'] = '#';
                else
                    $atts['href'] = !empty($item->url) ? $item->url : '#';

                $atts['data-toggle'] = 'dropdown';
                $atts['class'] = 'dropdown-toggle';
                $atts['aria-haspopup'] = 'true';
            } else {
                $atts['href'] = !empty($item->url) ? $item->url : '';
            }

            $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);

            $attributes = '';
            foreach ($atts as $attr => $value) {
                if (!empty($value)) {
                    $value = ( 'href' === $attr ) ? esc_url($value) : esc_attr($value);
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }

            $item_output = @$args->before;

            /*
             * Glyphicons
             * ===========
             * Since the the menu item is NOT a Divider or Header we check the see
             * if there is a value in the attr_title property. If the attr_title
             * property is NOT null we apply it as the class name for the glyphicon.
             */
            if (!empty($item->attr_title))
                $item_output .= '<a' . $attributes . '><span class="glyphicon ' . esc_attr($item->attr_title) . '"></span>&nbsp;';
            else
                $item_output .= '<a' . $attributes . '>';

            $item_output .= @$args->link_before . apply_filters('the_title', $item->title, $item->ID) . @$args->link_after;
            $item_output .= ( @$args->has_children && 0 === $depth ) ? ' <span class="caret"></span></a>' : '</a>';
            $item_output .= @$args->after;

            $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
        }
    }

    /**
     * Traverse elements to create list from elements.
     *
     * Display one element if the element doesn't have any children otherwise,
     * display the element and its children. Will only traverse up to the max
     * depth and no ignore elements under that depth.
     *
     * This method shouldn't be called directly, use the walk() method instead.
     *
     * @see Walker::start_el() 
     * @since 2.5.0 
     *         
     * @param object $element           Data object
     * @param array  $children_elements List of elements to continue traversing.
     * @param int    $max_depth         Max depth to traverse.
     * @param int    $depth             Depth of current element.
     * @param array  $args              
     * @param string $output            Passed by reference. Used to append additional content.
     * @since 1.0
     * @return null   Null on failure with no changes to parameters.
     */
    public function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output) {
        if (!$element)
            return;

        $id_field = $this->db_fields['id'];

        // Display this element.
        if (is_object($args[0]))
            $args[0]->has_children = !empty($children_elements[$element->$id_field]);

        parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }

    /**
     * Menu Fallback
     * =============
     * If this function is assigned to the wp_nav_menu's fallback_cb variable
     * and a manu has not been assigned to the theme location in the WordPress
     * menu manager the function with display nothing to a non-logged in user,
     * and will add a link to the WordPress menu manager if logged in as an admin.
     *
     * @param array $args passed from the wp_nav_menu function.
     *                    
     */
    public static function fallback($args) {
        if (current_user_can('manage_options')) {

            extract($args);

            $fb_output = null;

            if ($container) {
                $fb_output = '<' . $container;

                if ($container_id)
                    $fb_output .= ' id="' . $container_id . '"';

                if ($container_class)
                    $fb_output .= ' class="' . $container_class . '"';

                $fb_output .= '>';
            }

            $fb_output .= '<ul';

            if ($menu_id)
                $fb_output .= ' id="' . $menu_id . '"';

            if ($menu_class)
                $fb_output .= ' class="' . $menu_class . '"';

            $fb_output .= '>';
            $fb_output .= '<li><a href="' . admin_url('nav-menus.php') . '">Add a menu</a></li>';
            $fb_output .= '</ul>';

            if ($container)
                $fb_output .= '</' . $container . '>';

            echo $fb_output;
        }
    }

}

/* Add Parent Link to Child Menu */
add_filter('wp_nav_menu_objects', 'at_responsive_touch_dropdown_filter', 10, 2);

/**
 * Add parent link as child for touch menu
 * 
 * @param array  $sorted_menu_items Parameter 
 * @param object $args              Parameter 
 * @since 1.0
 * @return array  Return 
 */
function at_responsive_touch_dropdown_filter($sorted_menu_items, $args) {
    global $wp_rewrite;
    $new_sorted_menu_items = $unsorted_menu_items = $menu_items_with_children = array();
    if (isset($args->touch) && $args->touch) {
        $menu_hierarchy = array();

        foreach ($sorted_menu_items as $index => &$menu_item) {
            if ($menu_item->menu_item_parent > 0) {
                @$menu_hierarchy[$menu_item->menu_item_parent][$menu_item->ID] = clone $menu_item;
            }
            $unsorted_menu_items[$menu_item->ID] = clone $menu_item;
        }

        $parent_counter = 1;
        foreach ($menu_hierarchy as $parent_item_id => $children_items) {
            $menu_order_list = array();
            foreach ($children_items as $child_menu_id => $child_menu_item) {
                $menu_order_list[] = $unsorted_menu_items[$child_menu_id]->menu_order;
                $unsorted_menu_items[$child_menu_id]->menu_order += $parent_counter;
            }
            $parent_item = clone $unsorted_menu_items[$parent_item_id];
            $parent_item->menu_item_parent = $parent_item_id;
            $parent_item->menu_order = min($menu_order_list);
            // $parent_item->classes[] = 'hidden-xs';
            $unsorted_menu_items[] = clone $parent_item;
            $parent_counter ++;
        }
        foreach ((array) $unsorted_menu_items as $menu_item) {
            while (isset($new_sorted_menu_items[$menu_item->menu_order])) {
                $menu_item->menu_order ++;
            }
            // $menu_item->title .= ' ' . $menu_item->menu_order;
            $new_sorted_menu_items[$menu_item->menu_order] = $menu_item;
            if ($menu_item->menu_item_parent)
                $menu_items_with_children[$menu_item->menu_item_parent] = true;
        }
        ksort($new_sorted_menu_items);
    } else {
        return $sorted_menu_items;
    }

    // var_dump($menu_hierarchy);
    // var_dump($sorted_menu_items);
    // var_dump($new_sorted_menu_items);
    return $new_sorted_menu_items;
}
