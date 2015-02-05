<?php
/**
 * ArsTropica  Responsive Framework author.php
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
$gplus_override = at_responsive_get_theme_option('seo/googleauthor', false);
?>
<div class="author-box-layout col-md-12">
    <div id="author-box" class="clearfix">
        <div class="author-box-image">
            <?php echo get_avatar(get_the_author_meta('ID'), 72); ?>
        </div><!-- /.author-box-image -->

        <h4 class="author-box-name">
            <?php
            if (get_the_author_meta('description'))
                echo "About ";
            if (is_author())
                the_author_meta('display_name');
            else
                the_author_posts_link();
            ?>
        </h4>
        <?php //if( strlen( trim( the_author_meta( 'description' ) ) > 0 ) ) { ?>
        <div class="author-box-description">
            <p><?php the_author_meta('description'); ?></p>
        </div><!-- /.author-box-description -->
        <?php //} // end if  ?>
        <p class="author-links">
            <i class="glyphicon glyphicon-user"></i> <a class="author-link author-posts-url" href="<?php echo trailingslashit(get_author_posts_url(get_the_author_meta('ID'))); ?>" title="<?php echo get_the_author_meta('display_name'); ?> <?php _e('Posts', $theme_namespace); ?>"><?php _e('Posts', $theme_namespace); ?></a>

            <?php if (strlen(trim(get_the_author_meta('user_url'))) > 0) { ?>
                &nbsp;<i class="iconf icon-globe-open"></i> <a class="author-link author-url" href="<?php echo trailingslashit(the_author_meta('user_url')); ?>" title="<?php _e('Website', $theme_namespace); ?>" target="_blank"><?php _e('Website', $theme_namespace); ?></a>
            <?php } // end if  ?>

            <?php if (strlen(trim(get_user_meta(get_the_author_meta('ID'), 'user_tw', true))) > 0) { ?>
                &nbsp;<i class="iconf icon-twitter-open"></i> <a class="author-link icn-twitter" href="<?php echo trailingslashit(get_user_meta(get_the_author_meta('ID'), 'user_tw', true)); ?>" title="<?php _e('Twitter', $theme_namespace); ?>" target="_blank"><?php _e('Twitter', $theme_namespace); ?></a>
            <?php } // end if  ?>

            <?php if (strlen(trim(get_user_meta(get_the_author_meta('ID'), 'user_fb', true))) > 0) { ?>
                &nbsp;<i class="iconf icon-facebook-open"></i> <a class="author-link icn-facebook" href="<?php echo trailingslashit(get_user_meta(get_the_author_meta('ID'), 'user_fb', true)); ?>" title="<?php _e('Facebook', $theme_namespace); ?>" target="_blank"><?php _e('Facebook', $theme_namespace); ?></a>
            <?php } // end if  ?>

            <?php if (strlen(trim(get_user_meta(get_the_author_meta('ID'), 'google_profile', true))) > 0) { ?>
                &nbsp;<i class="iconf icon-gplus-open"></i> <a class="author-link icn-gplus" href="<?php echo trailingslashit(($gplus_override ? $gplus_override : get_user_meta(get_the_author_meta('ID'), 'google_profile', true))); ?>" title="<?php _e('Google+', $theme_namespace); ?>" target="_blank" rel="<?php echo apply_filters("at_google_authorship_rel", __("author", $theme_namespace)); ?>"><?php _e('Google+', $theme_namespace); ?></a>
            <?php } // end if  ?>

        </p>
    </div><!-- /.author-box -->		
</div>
