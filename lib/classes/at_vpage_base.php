<?php

/**
 * Base Virtual Page Plugin Class
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
 * Base Virtual Page Plugin Class
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
class at_vpage_base {

    /**
     * Array of Post / Page IDs registerd for the class
     * @var array 
     */
    static $active_post_ids = array();

    /**
     * Constructor
     * 
     * @since 1.0
     * @return void   
     * @access public 
     */
    function __construct() {
        
    }

    /**
     * Set Content Template
     * 
     * @param int|string $id Parameter 
     * @since 1.0
     * @return void    
     * @access private 
     */
    function _set_template_option($id) {
        wp_cache_set($id, $this->template_slug, 'at_responsive_template');
    }

    /**
     * Clear Content Template
     * 
     * @since 1.0
     * @return void    
     * @access private 
     */
    function _clear_template_option() {
        wp_cache_delete($this->page_id, 'at_responsive_template');
        // delete_transient( 'at_responsive_'.$this->page_id );
    }

    /**
     * Get Blog Domain
     * 
     * @param mixed $custom_blog_id Parameter 
     * @since 1.0
     * @return mixed   Return 
     * @access public  
     */
    function get_domain_mapped($custom_blog_id = null) {

        // Enable WordPress DB connection
        global $wpdb;

        $is_multisite = is_multisite();
        if (!$is_multisite) {
            return parse_url(get_bloginfo('url'), PHP_URL_HOST);
        } elseif (!$custom_blog_id) {
            $custom_blog_id = get_current_blog_id();
        }
        // To reduce the number of database queries, save the results the first time we encounter each blog ID.
        static $return_url = array();

        $wpdb->dmtable = $wpdb->base_prefix . 'domain_mapping';

        if (!isset($return_url[$custom_blog_id])) {
            $s = $wpdb->suppress_errors();

            if (get_site_option('dm_no_primary_domain') == 1) {
                $domain = $wpdb->get_var("SELECT domain FROM {$wpdb->dmtable} WHERE blog_id = '{$custom_blog_id}' LIMIT 1");
                if (null == $domain) {
                    $return_url[$custom_blog_id] = untrailingslashit(get_site_url($custom_blog_id));
                    return $return_url[$custom_blog_id];
                }
            } else {
                // get primary domain, if we don't have one then return original url.
                $domain = $wpdb->get_var("SELECT domain FROM {$wpdb->dmtable} WHERE blog_id = '{$custom_blog_id}' AND active = 1 LIMIT 1");
                if (null == $domain) {
                    $return_url[$wpdb->blogid] = parse_url(untrailingslashit(get_site_url($custom_blog_id)), PHP_URL_HOST);
                    return $return_url[$custom_blog_id];
                }
            }

            $wpdb->suppress_errors($s);
            if ($domain) {
                $return_url[$custom_blog_id] = $domain;
                $setting = $return_url[$custom_blog_id];
            } else {
                $return_url[$custom_blog_id] = false;
            }
        } elseif ($return_url[$custom_blog_id] !== FALSE) {
            $setting = $return_url[$custom_blog_id];
        }

        return $setting;
    }

    /**
     * Check Valid JSON
     * 
     * @param string $str Parameter 
     * @since 1.0
     * @return boolean Return 
     * @access public  
     */
    function is_json($str) {
        return @is_array(json_decode($str, true));
    }

    /**
     * Check Valid XML
     * 
     * @param string $xml Parameter 
     * @since 1.0
     * @return unknown Return 
     * @access public  
     */
    function is_xml($xml) {
        libxml_use_internal_errors(true);

        $doc = new DOMDocument('1.0', 'utf-8');

        $doc->loadXML($xml);

        $errors = libxml_get_errors();

        return empty($errors);
    }

    /**
     * Format US Phone Number
     * 
     * @param string $n Parameter 
     * @since 1.0
     * @return mixed   Return 
     * @access public  
     */
    function format_number($n) {
        $n = preg_replace("/[^0-9,.]/", "", $n);
        return "(" . substr($n, 0, 3) . ") " . substr($n, 3, 3) . "-" . substr($n, 6);
    }

    /**
     * Convert XML to PHP Array
     * 
     * @param string|SimpleXML $xml Parameter 
     * @since 1.0
     * @return unknown Return 
     * @access public  
     */
    function xml2array($xml) {
        if (is_string($xml))
            $ob = simplexml_load_string($xml);
        else
            $ob = $xml;
        $json = json_encode($ob);
        $array = json_decode($json, true);
        return $array;
    }

    /**
     * Get all object ids used by this class or its descendants
     * 
     * @since 1.0
     * @return array  Return 
     * @access public 
     * @static 
     */
    static function get_post_ids() {
        return self::$active_post_ids;
    }

}

?>
