<?php

/**
 * ArsTropica  Reponsive Framework
  Comments Include
 * 
 * PHP versions 4 and 5
 * 
 * @category   Theme Functions 
 * @package    WordPress
 * @author     ArsTropica <info@arstropica.com> 
 * @copyright  2014 ArsTropica 
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License 
 * @version    1.0 
 * @link       http://pear.php.net/package/ArsTropica  Reponsive Framework
 * @subpackage ArsTropica  Responsive Framework
 * @see        References to other sections (if any)...
 */
add_action('at_responsive_after_entry', 'at_responsive_get_comments_template');

/**
 * Get comment(s) template, if permitted.
 * 
 * @since 1.0
 * @return unknown Return 
 */
function at_responsive_get_comments_template() {

    global $post;

    if (!post_type_supports($post->post_type, 'comments'))
        return;

    if (comments_open()) {
        comments_template('/templates/comments.php');
    }
}
