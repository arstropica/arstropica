<?php
/**
 * ArsTropica  Responsive Framework Comments.php
 * 
 * PHP versions 4 and 5
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
 * Use Bootstrap's media object for listing comments
 * 
 * @category   Theme Framework Class 
 * @package    WordPress
 * @author     ArsTropica <info@arstropica.com> 
 * @copyright  2014 ArsTropica 
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License 
 * @version    Release: @package_version@ 
 * @link       http://twitter.github.com/bootstrap/components.html#media
 * @subpackage ArsTropica  Responsive Framework
 * @see        References to other sections (if any)...
 */
class AT_Responsive_Walker_Comment extends Walker_Comment {

    /**
     * HTML comment list class.
     * 
     * @param string &$output Parameter 
     * @param integer $depth   Parameter 
     * @param array   $args    Parameter 
     * @since 1.0
     * @return void   
     * @access public 
     */
    function start_lvl(&$output, $depth = 0, $args = array()) {
        $GLOBALS['comment_depth'] = $depth + 1;
        ?>
        <ul <?php comment_class('media unstyled comment-' . get_comment_ID()); ?>>
            <?php
        }

        /**
         * Start the list before the elements are added.
         * 
         * @param string &$output Parameter 
         * @param integer $depth   Parameter 
         * @param array   $args    Parameter 
         * @since 1.0
         * @return void   
         * @access public 
         */
        function end_lvl(&$output, $depth = 0, $args = array()) {
            $GLOBALS['comment_depth'] = $depth + 1;
            echo '</ul>';
        }

        /**
         * Start the element output.
         * 
         * @param string &$output Parameter 
         * @param unknown $comment Parameter 
         * @param integer $depth   Parameter 
         * @param array   $args    Parameter 
         * @param integer $id      Parameter 
         * @since 1.0
         * @return unknown Return 
         * @access public 
         */
        function start_el(&$output, $comment, $depth = 0, $args = array(), $id = 0) {
            $depth++;
            $GLOBALS['comment_depth'] = $depth;
            $GLOBALS['comment'] = $comment;

            if (!empty($args['callback'])) {
                call_user_func($args['callback'], $comment, $args, $depth);
                return;
            }

            extract($args, EXTR_SKIP);
            ?>

            <li id="comment-<?php comment_ID(); ?>" <?php comment_class('media comment-' . get_comment_ID()); ?> itemprop="comment" itemscope itemtype="http://schema.org/UserComments">
                <?php include(locate_template('templates/comment.php')); ?>
                <?php
            }

            /**
             * End Element's output.
             * 
             * @param string &$output Parameter 
             * @param unknown $comment Parameter 
             * @param integer $depth   Parameter 
             * @param array   $args    Parameter 
             * @since 1.0
             * @return unknown Return 
             * @access public 
             */
            function end_el(&$output, $comment, $depth = 0, $args = array()) {
                if (!empty($args['end-callback'])) {
                    call_user_func($args['end-callback'], $comment, $args, $depth);
                    return;
                }
                echo "</div></li>\n";
            }

        }

        /**
         * Get Commenter's avatar.
         * 
         * @param string $avatar Parameter 
         * @since 1.0
         * @return unknown Return 
         */
        function at_responsive_get_avatar($avatar) {
            $avatar = str_replace("class='avatar", "class='avatar pull-left media-object", $avatar);
            return $avatar;
        }

        add_filter('get_avatar', 'at_responsive_get_avatar');
        