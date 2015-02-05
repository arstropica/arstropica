<?php

/**
 * ArsTropica  Responsive Framework plaintext_cat_walker.php
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
 * Custom walker to get flat lists of catagory and post_tag terms
 * to feed into Bootstrap's typeahead attribute for the search box
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
class Plaintext_Cat_Walker extends Walker_Category {

    /**
     * Starts the list before the elements are added.
     * 
     * @param string  &$output Parameter 
     * @param integer $depth   Parameter 
     * @param array   $args    Parameter 
     * @since 1.0
     * @return void    
     * @access public  
     */
    function start_lvl(&$output, $depth = 0, $args = array()) {
        // $output .= "-start-$depth";
        $output .= '", ';
    }

    /**
     * Ends the list of after the elements are added.
     * 
     * @param string  &$output Parameter 
     * @param integer $depth   Parameter 
     * @param array   $args    Parameter 
     * @since 1.0
     * @return void    
     * @access public  
     */
    function end_lvl(&$output, $depth = 0, $args = array()) {
        $output .= "\"-end-$depth";
        // $output .= '"';
    }

    /**
     * Start the element output.
     * 
     * @param string  &$output  Parameter 
     * @param object  $category Parameter 
     * @param integer $depth    Parameter 
     * @param array   $args     Parameter 
     * @param integer $id       Parameter 
     * @since 1.0
     * @return void    
     * @access public  
     */
    function start_el(&$output, $category, $depth = 0, $args = array(), $id = 0) {
        $output .= '"' . esc_attr($category->name);
    }

    /**
     * Ends the element output, if needed.
     * 
     * @param string  &$output Parameter 
     * @param object $page    Parameter 
     * @param integer $depth   Parameter 
     * @param array   $args    Parameter 
     * @since 1.0
     * @return void    
     * @access public  
     */
    function end_el(&$output, $page, $depth = 0, $args = array()) {
        $output .= '", ';
    }

}

/**
 * Function to return JSON object of categories and tags
 * 
 * @since 1.0
 * @return boolean Return 
 */
function at_responsive_get_typeahead() {
    /* Typeahead search terms bank, cached with transient
      -------------------------------------------------- */
    global $theme_namespace, $use_theme_transients;
    $typeahead = false;
    if (false === ( $typeahead = get_transient($theme_namespace . '_search_terms') )) {
        $plaintext_cat_walker = new Plaintext_Cat_Walker;
        $args = array(
            'echo' => 0,
            'title_li' => '',
            'style' => 'none',
                // 'walker' => $plaintext_cat_walker
        );
        // $cats = wp_list_categories( $args )
        // $cats = rtrim( $cats, ', ' );
        $cats = array_filter(
                array_map('trim', array_map('strip_tags', explode("\n", str_replace(",", "\,", wp_list_categories($args)
                                        )
                                )
                        )
                )
        );

        $args = array(
            'echo' => 0,
            'title_li' => '',
            'style' => 'none',
            'taxonomy' => 'post_tag',
                // 'walker' => $plaintext_cat_walker
        );
        // $tags = wp_list_categories( $args );
        // $tags = rtrim( $tags, ', ' );
        $tags = array_filter(
                array_map('trim', array_map('strip_tags', explode("\n", str_replace(",", "\,", wp_list_categories($args)
                                        )
                                )
                        )
                )
        );

        $typeahead = json_encode(array_unique(array_merge($cats, $tags)), JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_APOS);
        if ($use_theme_transients)
            set_transient($theme_namespace . '_search_terms', $typeahead, MINUTE_IN_SECONDS);
    }
    return $typeahead;
}

/* Filter search query to add categories and tags to results */
add_filter('posts_where', 'at_responsive_search_typeahead');

/**
 * Filter search query to add categories and tags to results
 * 
 * @param string $where Parameter 
 * @since 1.0
 * @return string Return 
 */
function at_responsive_search_typeahead($where) {
    if (is_search()) {

        global $wpdb;
        $query = get_search_query();
        $query = like_escape($query);

        // include taxonomy in search
        $where .=" OR {$wpdb->posts}.ID IN (SELECT {$wpdb->posts}.ID FROM {$wpdb->posts},{$wpdb->term_relationships},{$wpdb->terms} WHERE {$wpdb->posts}.ID = {$wpdb->term_relationships}.object_id AND {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->terms}.term_id AND {$wpdb->terms}.name LIKE '%$query%')";
    }
    return $where;
}
