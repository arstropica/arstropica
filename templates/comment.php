<?php
/**
 * ArsTropica  Responsive Framework comment.php
 * 
 * PHP version 5
 * 
 * @category   Theme Template
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
 * Description for global
 * @global unknown
 */
global $theme_namespace;
?>

<?php echo get_avatar($comment, $size = '72'); ?>
<div class="media-body">
    <h4 class="media-heading" itemprop="creator"><?php echo get_comment_author_link(); ?></h4>
    <time datetime="<?php echo comment_date('c'); ?>" itemprop="commentTime"><a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>"><?php printf(__('%1$s', $theme_namespace), get_comment_date(), get_comment_time()); ?></a></time>
    <?php edit_comment_link(__('(Edit)', $theme_namespace), '', ''); ?>

    <?php if ($comment->comment_approved == '0') : ?>
        <div class="alert">
            <?php _e('Your comment is awaiting moderation.', $theme_namespace); ?>
        </div>
    <?php endif; ?>

    <div itemprop="commentText">
        <?php comment_text(); ?>
    </div>
    <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
