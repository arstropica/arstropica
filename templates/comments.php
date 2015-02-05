<?php
/**
 * ArsTropica  Responsive Framework comments.php
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
$grid_values = at_responsive_get_content_grid_values();
$grid_classes = at_responsive_get_content_grid_classes();
$nested_columns = $grid_values['comments'];
?>

<?php
if (post_password_required()) {
    return;
}

if (have_comments()) :
    ?>
    <div class="comments-layout col-md-<?php echo $nested_columns; ?> <?php echo $grid_classes['comments']; ?>">
        <section id="comments">
            <h3><?php printf(_n('One Response to &ldquo;%2$s&rdquo;', '%1$s Responses to &ldquo;%2$s&rdquo;', get_comments_number(), $theme_namespace), number_format_i18n(get_comments_number()), get_the_title()); ?></h3>

            <ol class="media-list">
                <?php wp_list_comments(array('walker' => new AT_Responsive_Walker_Comment())); ?>
            </ol>

            <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
                <nav>
                    <ul class="pager">
                        <?php if (get_previous_comments_link()) : ?>
                            <li class="previous"><?php previous_comments_link(__('&larr; Older comments', $theme_namespace)); ?></li>
                        <?php endif; ?>
                        <?php if (get_next_comments_link()) : ?>
                            <li class="next"><?php next_comments_link(__('Newer comments &rarr;', $theme_namespace)); ?></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>

            <?php if (!comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments')) : ?>
                <div class="alert">
                    <?php _e('Comments are closed.', $theme_namespace); ?>
                </div>
            <?php endif; ?>
        </section><!-- /#comments -->
    </div>
<?php endif; ?>

<?php if (!have_comments() && !comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments')) : ?>
    <div class="comments-layout col-md-<?php echo $nested_columns; ?> <?php echo $grid_classes['comments']; ?>">
        <section id="comments">
            <div class="alert">
                <?php _e('Comments are closed.', $theme_namespace); ?>
            </div>
        </section><!-- /#comments -->
    </div>
<?php endif; ?>

<?php if (comments_open()) : ?>
    <div class="comments-layout col-md-<?php echo $nested_columns; ?> <?php echo $grid_classes['comments']; ?>">
        <section id="respond">
            <h3><?php comment_form_title(__('Leave a Reply', $theme_namespace), __('Leave a Reply to %s', $theme_namespace)); ?></h3>
            <p class="cancel-comment-reply"><?php cancel_comment_reply_link(); ?></p>
            <?php if (get_option('comment_registration') && !is_user_logged_in()) : ?>
                <p><?php printf(__('You must be <a href="%s">logged in</a> to post a comment.', $theme_namespace), wp_login_url(get_permalink())); ?></p>
            <?php else : ?>
                <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
                    <?php if (is_user_logged_in()) : ?>
                        <p>
                            <?php printf(__('Logged in as <a href="%s/wp-admin/profile.php">%s</a>.', $theme_namespace), get_option('siteurl'), $user_identity); ?>
                            <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php __('Log out of this account', $theme_namespace); ?>"><?php _e('Log out &raquo;', $theme_namespace); ?></a>
                        </p>
                    <?php else : ?>
                        <div class="form-group">
                            <label for="author"><?php
                                _e('Name', $theme_namespace);
                                if ($req)
                                    _e(' (required)', $theme_namespace);
                                ?></label>
                            <input type="text" class="form-control" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" size="22" <?php if ($req) echo 'aria-required="true"'; ?>>
                        </div>
                        <div class="form-group">
                            <label for="email"><?php
                                _e('Email (will not be published)', $theme_namespace);
                                if ($req)
                                    _e(' (required)', $theme_namespace);
                                ?></label>
                            <input type="email" class="form-control" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" size="22" <?php if ($req) echo 'aria-required="true"'; ?>>
                        </div>
                        <div class="form-group">
                            <label for="url"><?php _e('Website', $theme_namespace); ?></label>
                            <input type="url" class="form-control" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" size="22">
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="comment"><?php _e('Comment', $theme_namespace); ?></label>
                        <textarea name="comment" id="comment" class="form-control" rows="5" aria-required="true"></textarea>
                    </div>
                    <p><input name="submit" class="btn btn-primary" type="submit" id="submit" value="<?php _e('Submit Comment', $theme_namespace); ?>"></p>
                        <?php comment_id_fields(); ?>
                        <?php do_action('comment_form', $post->ID); ?>
                </form>
            <?php endif; ?>
        </section><!-- /#respond -->
    </div>
<?php endif; ?>
