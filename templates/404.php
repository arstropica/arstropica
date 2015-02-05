<?php
/**
 * ArsTropica  Responsive Framework 404.php
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

if (at_responsive_html5()) :

    echo '<p>' . sprintf(__('The page you are looking for no longer exists. Perhaps you can return back to the site\'s <a href="%s">homepage</a> and see if you can find what you are looking for. Or, you can try finding it by using the search form below.', $theme_namespace), home_url()) . '</p>';

    echo '<p>' . get_search_form() . '</p>';

else :
    ?>
    <blockquote><?php printf(__('The page you are looking for no longer exists. Perhaps you can return back to the site\'s <a href="%s">homepage</a> and see if you can find what you are looking for. Or, you can try finding it with the information below.', $theme_namespace), home_url()); ?></blockquote>

    <div class="archive-page">

        <h4><?php _e('Pages:', $theme_namespace); ?></h4>
        <ul>
            <?php wp_list_pages('title_li='); ?>
        </ul>

        <h4><?php _e('Categories:', $theme_namespace); ?></h4>
        <ul>
            <?php wp_list_categories('sort_column=name&title_li='); ?>
        </ul>

    </div>

    <div class="archive-page">

        <h4><?php _e('Authors:', $theme_namespace); ?></h4>
        <ul>
            <?php wp_list_authors('exclude_admin=0&optioncount=1'); ?>
        </ul>

        <h4><?php _e('Monthly:', $theme_namespace); ?></h4>
        <ul>
            <?php wp_get_archives('type=monthly'); ?>
        </ul>

        <h4><?php _e('Recent Posts:', $theme_namespace); ?></h4>
        <ul>
            <?php wp_get_archives('type=postbypost&limit=100'); ?>
        </ul>
    </div>
<?php endif; ?>
