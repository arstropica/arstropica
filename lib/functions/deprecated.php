<?php

/**
 * ArsTropica  Reponsive Framework Deprecated Functions
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
/* Format Author Box */
add_filter('at_responsive_author_box', 'at_responsive_author_box_width', 10, 6);

/**
 * Deprecated. Resize Author Box
 * 
 * @param string $markup      Parameter 
 * @param string  $context     Parameter 
 * @param string  $pattern     Parameter 
 * @param string  $gravatar    Parameter 
 * @param string  $title       Parameter 
 * @param string  $description Parameter 
 * @since 1.0
 * @return unknown Return 
 */
function at_responsive_author_box_width($markup, $context = '', $pattern = '', $gravatar = '', $title = '', $description = '') {
    global $post;
    if (comments_open($post->ID)) {
        $pattern = sprintf('<div class="%s col-md-%s">', '6', 'author-box') . $pattern . '</div>';
    } else {
        $pattern = sprintf('<div class="col-md-%s">', '12') . $pattern . '</div>';
    }
    $output = sprintf($pattern, $gravatar, $title, $description);

    return $output;
}

/* Format Comment Form */
add_filter('comment_form_defaults', 'at_responsive_format_comments', 10, 1);

/**
 * Deprecated. Format Comment Tags / Structure
 * 
 * @param array $defaults Parameter 
 * @since 1.0
 * @return array Return 
 */
function at_responsive_format_comments($defaults) {
    $user = wp_get_current_user();
    $commenter = wp_get_current_commenter();
    $commenter_registered = array_filter($commenter) ? true : false;
    if (!$commenter_registered && $user->exists()) {
        $commenter['comment_author'] = $user->display_name;
        $commenter['comment_author_email'] = $user->user_email;
        $commenter['comment_author_url'] = $user->user_url;
        $commenter_registered = true;
    }
    $user_identity = $user->exists() ? $user->display_name : $commenter['comment_author'];

    foreach ($defaults as $field => &$default) {
        switch ($field) {
            case 'comment_field' : {
                    if ($commenter_registered)
                        $default = at_responsive_comment_field($default, $commenter_registered);
                    break;
                }
            case 'logged_in_as' : {
                    $default = at_responsive_comment_author($default, $commenter, $user_identity);
                    break;
                }
            case 'comment_notes_after' : {
                    $default = at_responsive_column_wrap($default);
                    break;
                }
        }
    }
    return $defaults;
}

/**
 * Deprecated. Add Avatar to Comment Form
 * 
 * @param string|null $logged_in_as  Parameter 
 * @param array   $commenter     Parameter 
 * @param string|null $user_identity Parameter 
 * @since 1.0
 * @return string  Return 
 */
function at_responsive_comment_author($logged_in_as = null, $commenter = null, $user_identity = null) {
    $commenter_email = isset($commenter['comment_author_email']) ? $commenter['comment_author_email'] : '';
    $avatar = get_avatar($commenter_email, 48, '', $user_identity);
    $output = "";
    $output .= ($logged_in_as ? '<p class="col-md-12">' . at_responsive_strip_selected_tags($logged_in_as, 'P') . '</p>' : '');
    $output .= '<p class="col-md-1 users-feedback">' . $avatar . '</p>';
    return $output;
}

/**
 * Deprecated. Format Comment Field
 * 
 * @param string  $comment_field Parameter 
 * @param boolean $registered    Parameter 
 * @since 1.0
 * @return string  Return 
 */
function at_responsive_comment_field($comment_field = null, $registered = false) {
    if ($comment_field) {
        $comment_field = sprintf('<%1$s class="col-md-10 %2$s">' . $comment_field . '</%1$s>', 'div', ($registered ? 'registered' : 'not-registered'));
    }
    return $comment_field;
}

/* Page Layout */
add_action('add_meta_boxes', 'at_responsive_metabox_add_page_layout');

/**
 * Deprecated. Adds Layout Metabox to Page edit screen
 * 
 * @since 1.0
 * @return void 
 */
function at_responsive_metabox_add_page_layout() {
    global $theme_namespace;
    $screen = "page";
    $id = 'at-responsive-theme-page-layout';
    add_meta_box(
            $id, __(ucwords('Page Layout'), $theme_namespace), 'at_responsive_metabox_do_page_layout', $screen, 'normal', 'default'
    );
    add_filter("postbox_classes_{$screen}_{$id}", 'at_responsive_metabox_add_page_layout_classes');
}

/**
 * Deprecated. Displays Metabox content
 * 
 * @param object  $post    Parameter 
 * @param unknown $metabox Parameter 
 * @since 1.0
 * @return void    
 */
function at_responsive_metabox_do_page_layout($post, $metabox) {

    global $theme_namespace;

    wp_nonce_field('at-responsive-theme-page-layout', 'at-responsive-theme-page-layout-nonce');

    $layout_metabox_data = get_post_meta($post->ID, 'at-responsive-theme-page-layout', true);

    if (!$layout_metabox_data)
        $layout_metabox_data = at_responsive_get_theme_option('settings/layout', 'fullwidth');

    echo '<style type="text/css">';
    echo "UL.layout-metabox {\n";
    echo "display: table;\n";
    // echo "height: 125px;\n";
    echo "border-collapse: separate;\n";
    echo "border-spacing: 1em 0;\n";
    echo "width: 100%;\n";
    echo "min-width: 350px;\n";
    echo "max-width: 600px;\n";
    echo "padding: 0px;\n";
    echo "margin: 0px;\n";
    echo "}\n";
    echo "UL.layout-metabox LI {\n";
    echo "display: table-cell;\n";
    // echo "float: left;\n";
    echo "width: 25%;\n";
    // echo "margin: 0px 2.5%;\n";
    echo "-webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */\n";
    echo "-moz-box-sizing: border-box;    /* Firefox, other Gecko */\n";
    echo "box-sizing: border-box;         /* Opera/IE 8+ */\n";
    echo "padding: 10px 0px;\n";
    echo "text-align: center;\n";
    echo "background: #CCC;\n";
    echo "min-height: 125px;\n";
    echo "}\n";
    echo "UL.layout-metabox LI LABEL {\n";
    echo "font-size: 8pt;\n";
    echo "font-style: italic;\n";
    echo "}\n";
    echo "UL.layout-metabox LI INPUT[type=radio] {\n";
    echo "clear: both;\n";
    echo "display: block;\n";
    echo "margin: 0 auto;\n";
    echo "}\n";
    echo "UL.layout-metabox .diagram {\n";
    echo "display: table;\n";
    echo "border-collapse: separate;\n";
    echo "border-spacing: 5px 2px;\n";
    echo "height: 50px;\n";
    echo "width: 100%;\n";
    echo "-webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */\n";
    echo "-moz-box-sizing: border-box;    /* Firefox, other Gecko */\n";
    echo "box-sizing: border-box;         /* Opera/IE 8+ */\n";
    echo "padding: 0px 5px;\n";
    echo "}\n";
    echo "UL.layout-metabox .diagram SPAN {\n";
    echo "display: table-cell;\n";
    echo "background: #eee;\n";
    echo "border: 1px solid #FFF;\n";
    echo "}\n";
    echo "UL.layout-metabox .diagram SPAN.sidebar {\n";
    echo "width: 25%;\n";
    echo "}\n";
    echo '</style>';

    echo '<p><strong>';
    _e('Choose Layout :', $theme_namespace);
    echo '</strong></p> ';
    echo "<ul class=\"layout-metabox\">\n";
    echo "<li class=\"no-sidebar\">\n";
    echo "<div class=\"diagram\"> <span class=\"content\"></span>\n";
    echo "</div>\n";
    echo "<label for=\"at-responsive-theme-page-layout\" class=\"screen-reader-text\">No Sidebars\n";
    _e('No Sidebars', $theme_namespace);
    echo "</label>\n";
    echo "<p><strong>No Sidebar</strong></p>\n";
    echo "<input type=\"radio\" name=\"at-responsive-theme-page-layout\" value=\"nosidebar\" " . checked($layout_metabox_data, "nosidebar", false) . "/>\n";
    echo "<div class=\"width: 100%; height: 0px; clear: both;\"></div>\n";
    echo "</li>\n";
    echo "<li class=\"sidebar-right\">\n";
    echo "<div class=\"diagram\">\n";
    echo "<span class=\"sidebar\"></span>\n";
    echo "<span class=\"content\"></span>\n";
    echo "</div>\n";
    echo "<label for=\"at-responsive-theme-page-layout\" class=\"screen-reader-text\">\n";
    _e('Sidebar - Content', $theme_namespace);
    echo "</label>\n";
    echo "<p><strong>Sidebar - Content</strong></p>\n";
    echo "<input type=\"radio\" name=\"at-responsive-theme-page-layout\" value=\"right\" " . checked($layout_metabox_data, "right", false) . "/>\n";
    echo "<div class=\"width: 100%; height: 0px; clear: both;\"></div>\n";
    echo "</li>\n";
    echo "<li class=\"fullwidth\">\n";
    echo "<div class=\"diagram\" style=\"display: block;\">\n";
    echo "<div style=\"display: table; height: 50%; width: 100%\">\n";
    echo "<span class=\"content\"></span>\n";
    echo "</div>\n";
    echo "<div style=\"display: table; height: 50%; width: 100%\">\n";
    echo "<span class=\"sidebar\"></span>\n";
    echo "<span class=\"sidebar\"></span>\n";
    echo "</div>\n";
    echo "</div>\n";
    echo "<label for=\"at-responsive-theme-page-layout\" class=\"screen-reader-text\">Full Width\n";
    _e('Full Width', $theme_namespace);
    echo "</label>\n";
    echo "<p><strong>Full Width</strong></p>\n";
    echo "<input type=\"radio\" name=\"at-responsive-theme-page-layout\" value=\"fullwidth\" " . checked($layout_metabox_data, "fullwidth", false) . "/>\n";
    echo "<div class=\"width: 100%; height: 0px; clear: both;\"></div>\n";
    echo "</li>\n";
    echo "<li class=\"sidebar-left\">\n";
    echo "<div class=\"diagram\">\n";
    echo "<span class=\"content\"></span>\n";
    echo "<span class=\"sidebar\"></span>\n";
    echo "</div>\n";
    echo "<label for=\"at-responsive-theme-page-layout\" class=\"screen-reader-text\">\n";
    _e('Content - Sidebar', $theme_namespace);
    echo "</label>\n";
    echo "<p><strong>Content - Sidebar</strong></p>\n";
    echo "<input type=\"radio\" name=\"at-responsive-theme-page-layout\" value=\"left\" " . checked($layout_metabox_data, "left", false) . "/>\n";
    echo "<div class=\"width: 100%; height: 0px; clear: both;\"></div>\n";
    echo "</li>\n";
    echo "</ul>\n";
}

add_action('save_post', 'at_responsive_metabox_save_page_layout');

/**
 * Deprecated. Save Metabox Data.
 * 
 * @param number $post_id Parameter 
 * @since 1.0
 * @return unknown Return 
 */
function at_responsive_metabox_save_page_layout($post_id) {

    if (!isset($_POST['at-responsive-theme-page-layout-nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['at-responsive-theme-page-layout-nonce'], 'at-responsive-theme-page-layout')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {

        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    }

    /* OK, its safe for us to save the data now. */

    if (!isset($_POST['at-responsive-theme-page-layout'])) {
        return;
    }

    $layout_data = $_POST['at-responsive-theme-page-layout'];
    update_post_meta($post_id, 'at-responsive-theme-page-layout', $layout_data);
}

/**
 * Deprecated. Add metabox classes
 * 
 * @param array $classes Parameter 
 * @since 1.0
 * @return unknown Return 
 */
function at_responsive_metabox_add_page_layout_classes($classes) {
    array_push($classes, 'layout-metabox');
    return $classes;
}

?>
