<?php

/**
 * Plugin Name: WP Twitter Mega Fan Box Widget
 * Plugin URI: http://jobyj.in/twitter-follow-box-widget/
 * Description: Twitter Fan box similar to Facebook Like box. The plugin allows easy customization through options page.
 * Version: 1.0
 * Author: Joby Joseph
 * Author URI: http://jobyj.in
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class WP_Twitter_Mega_Fan_Box extends WP_Widget {

    public function __construct() {
        /* Widget settings. */
        $widget_ops = array('classname' => 'twitter-follow', 'description' => 'Facebook Like box style fan box for twitter with lot of features');

        /* Widget control settings. */
        $control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'wp-twitter-follow-widget');

        /* Create the widget. */
        $this->WP_Widget('wp-twitter-follow-widget', 'WP Twitter Mega Fan Box', $widget_ops, $control_ops);
    }

    public function form($instance) {
        /* Set up some default widget settings. */
        $defaults = array(
            'title' => 'Twitter Fan Box',
        );
        $instance = wp_parse_args((array) $instance, $defaults);
?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
        </p>

        <p>
            Go to <a href="<?php echo get_site_url() . '/wp-admin/options-general.php?page=wp_twitter_mega_fanbox' ?>">WP Twitter Mega Fan Box Settings</a> to customize the widget
        </p>

<?php
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;

        /* Strip tags (if needed) and update the widget settings. */
        $instance['title'] = strip_tags($new_instance['title']);

        return $instance;
    }

    public function widget($args, $instance) {
        extract($args);

        /* User-selected settings. */
        $title = apply_filters('widget_title', $instance['title']);
        $user = get_option('twitter_user_name');
        $width = get_option('twitter_widget_width');
        $height = get_option('twitter_widget_height');
        $theme = get_option('twitter_widget_theme');
        if (get_option('twitter_widget_border_color')) {
            $border_color = '#' . get_option('twitter_widget_border_color');
        }
        if (get_option('twitter_widget_bg_color')) {
            $bg_color = '#' . get_option('twitter_widget_bg_color');
        }
        $bg_image = get_option('twitter_widget_bg_image');
        if (get_option('twitter_widget_title_color')) {
            $title_color = '#' . get_option('twitter_widget_title_color');
        }
        if (get_option('twitter_widget_total_count_color')) {
            $count_color = '#' . get_option('twitter_widget_total_count_color');
        }
        if (get_option('twitter_widget_follower_name_color')) {
            $name_color = '#' . get_option('twitter_widget_follower_name_color');
        }
        if (get_option('twitter_widget_container_bg_color')) {
            $container_bg = '#' . get_option('twitter_widget_container_bg_color');
        }

        /* Before widget (defined by themes). */
        echo $before_widget;

        /* Title of widget (before and after defined by themes). */
        if ($title)
            echo $before_title . $title . $after_title;

        /* Display name from widget settings. */
        if ($user) {
            $bg = '';
            if ($container_bg) {
                $bg = "background:$container_bg;";
            }
            echo '<div style="text-align:center; ' . $bg . '" id="twitter-follow-container"></div>';
            echo '<script type="text/javascript">';
            echo 'twitter_logo_path="' . plugins_url('followbox/icon_twitter.png', __FILE__) . '";';
            if (get_option('twitter_widget_footer_link')) {
                echo 'footer_link_url="' . get_option('twitter_widget_footer_link') . '";';
            } else {
                echo 'footer_link_url="http://jobyj.in/twitter-follow-box-widget/";';
            }
            echo 'jQuery(document).ready(function(){
                    jQuery("#twitter-follow-container").followbox({';
            echo '            "user":"' . $user . '"';
            if ($width)
                echo '            ,"width":' . $width;
            if ($height)
                echo '            ,"height":' . $height;
            if ($theme)
                echo '            ,"theme":"' . $theme . '"';
            if ($border_color)
                echo '            ,"border_color":"' . $border_color . '"';
            if ($theme == 'custom') {
                if ($bg_color)
                    echo '            ,"bg_color":"' . $bg_color . '"';
                if ($bg_image)
                    echo '            ,"bg_image":"' . $bg_image . '"';
                if ($title_color)
                    echo '            ,"title_color":"' . $title_color . '"';
                if ($count_color)
                    echo '            ,"total_count_color":"' . $count_color . '"';
                if ($name_color)
                    echo '            ,"follower_name_color":"' . $name_color . '"';
            }
            echo '        });
                 });';
            echo '</script>';
        }
        /* After widget (defined by themes). */
        echo $after_widget;
    }

}

/* Add our function to the widgets_init hook. */
add_action('widgets_init', 'twitter_load_widget');

/* Function that registers our widget. */

function twitter_load_widget() {
    register_widget('WP_Twitter_Mega_Fan_Box');
}

add_action('init', 'initialize_script');

function initialize_script() {
    wp_enqueue_script('jquery');
    wp_register_script('twitter-follow-widget', plugins_url('followbox/jquery.followbox.js', __FILE__));
    wp_enqueue_script('twitter-follow-widget', array('jquery'));
    wp_register_script('twitter-follow-color-picker', plugins_url('color-picker/js/colorpicker.js', __FILE__));
    wp_enqueue_script('twitter-follow-color-picker', array('jquery'));
    wp_register_script('twitter-follow-color-picker-eye', plugins_url('color-picker/js/colorpicker.js', __FILE__));
    wp_enqueue_script('twitter-follow-color-picker-eye', array('twitter-follow-color-picker'));
    wp_register_script('twitter-follow-color-picker-utils', plugins_url('color-picker/js/colorpicker.js', __FILE__));
    wp_enqueue_script('twitter-follow-color-picker-utils', array('twitter-follow-color-picker-eye'));
    wp_register_script('twitter-follow-color-picker-layout', plugins_url('color-picker/js/colorpicker.js', __FILE__));
    wp_enqueue_script('twitter-follow-color-picker-layout', array('twitter-follow-color-picker-utils'));
    wp_register_style('twitter-follow-widget-style', plugins_url('followbox/followbox.css', __FILE__));
    wp_enqueue_style('twitter-follow-widget-style');
    wp_register_style('twitter-follow-color-style', plugins_url('color-picker/css/colorpicker.css', __FILE__));
    wp_enqueue_style('twitter-follow-color-style');
}
?>
<?php
// create custom plugin settings menu
add_action('admin_menu', 'twitter_create_menu');

function twitter_create_menu() {

    //create new top-level menu
    add_submenu_page('options-general.php', 'WP Twitter Mega Fan Box', 'WP Twitter Mega Fan Box', 'administrator', 'wp_twitter_mega_fanbox', 'wp_twitter_mega_fanbox');

    //call register settings function
    add_action('admin_init', 'register_twitter_settings');
}

function register_twitter_settings() {
    //register our settings
    register_setting('wp-twitter-mega-fanbox-group', 'twitter_user_name');
    register_setting('wp-twitter-mega-fanbox-group', 'twitter_widget_width');
    register_setting('wp-twitter-mega-fanbox-group', 'twitter_widget_height');
    register_setting('wp-twitter-mega-fanbox-group', 'twitter_widget_theme');
    register_setting('wp-twitter-mega-fanbox-group', 'twitter_widget_border_color');
    register_setting('wp-twitter-mega-fanbox-group', 'twitter_widget_bg_color');
    register_setting('wp-twitter-mega-fanbox-group', 'twitter_widget_bg_image');
    register_setting('wp-twitter-mega-fanbox-group', 'twitter_widget_title_color');
    register_setting('wp-twitter-mega-fanbox-group', 'twitter_widget_total_count_color');
    register_setting('wp-twitter-mega-fanbox-group', 'twitter_widget_follower_name_color');
    register_setting('wp-twitter-mega-fanbox-group', 'twitter_widget_container_bg_color');
    register_setting('wp-twitter-mega-fanbox-group', 'twitter_widget_footer_link');
}

function wp_twitter_mega_fanbox() {
?>
    <div class="wrap">
        <div class="icon32" style="background:url('<?php echo plugins_url('twitter_icon_medium.png', __FILE__); ?>') no-repeat;"><br/></div><h2>WP Twitter Mega Fan Box Widget Settings</h2>
        <table>
            <tr>
                <td style="vertical-align: top;">
                    <form method="post" action="options.php">
<?php settings_fields('wp-twitter-mega-fanbox-group'); ?>
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row">Twitter User Name</th>
                                <td><input type="text" class="regular-text" name="twitter_user_name" style="height:25px;" value="<?php
    if (get_option('twitter_user_name')) {
        echo get_option('twitter_user_name');
    } else {
        echo 'jobysblog';
    }
?>" /></td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Widget Width(px)</th>
                            <td><input type="text" class="regular-text" name="twitter_widget_width" style="height:25px;" value="<?php
    if (get_option('twitter_widget_width')) {
        echo get_option('twitter_widget_width');
    } else {
        echo '292';
    }
?>" /></td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Widget Height(px)</th>
                            <td><input type="text" class="regular-text" name="twitter_widget_height" style="height:25px;" value="<?php
    if (get_option('twitter_widget_height')) {
        echo get_option('twitter_widget_height');
    } else {
        echo '252';
    }
?>" /></td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Widget Theme</th>
                            <td style="text-align: left;"><select name="twitter_widget_theme">
                                    <option <?php if ('light' == get_option('twitter_widget_theme'))
        echo 'selected="selected"'; ?>>light</option>
                                    <option <?php if ('dark' == get_option('twitter_widget_theme'))
                                    echo 'selected="selected"'; ?>>dark</option>
                                    <option <?php if ('custom' == get_option('twitter_widget_theme'))
                                           echo 'selected="selected"'; ?>>custom</option>
                                </select>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Widget Border Color</th>
                            <td><input type="text" class="regular-text color-picker" style="height:25px;" name="twitter_widget_border_color" value="<?php
                                       if (get_option('twitter_widget_border_color')) {
                                           echo get_option('twitter_widget_border_color');
                                       } else {
                                           echo 'AAA';
                                       }
?>" /></td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Widget Background Color</th>
                            <td><input type="text" class="regular-text color-picker" style="height:25px;" name="twitter_widget_bg_color" value="<?php echo get_option('twitter_widget_bg_color'); ?>" /></td>
                               </tr>

                               <tr valign="top">
                                   <th scope="row">Widget Background Image URL</th>
                                   <td><input type="text" class="regular-text" style="height:25px;" name="twitter_widget_bg_image" value="<?php echo get_option('twitter_widget_bg_image'); ?>" /></td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Widget Title Color</th>
                            <td><input type="text" class="regular-text color-picker" style="height:25px;" name="twitter_widget_title_color" value="<?php echo get_option('twitter_widget_title_color'); ?>" /></td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Widget Total Count Color</th>
                            <td><input type="text" class="regular-text color-picker" style="height:25px;" name="twitter_widget_total_count_color" value="<?php echo get_option('twitter_widget_total_count_color'); ?>" /></td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">Widget Follower Name Color</th>
                            <td><input type="text" class="regular-text color-picker" style="height:25px;" name="twitter_widget_follower_name_color" value="<?php echo get_option('twitter_widget_follower_name_color'); ?>" /></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Widget Container Background Color</th>
                            <td><input type="text" class="regular-text color-picker" style="height:25px;" name="twitter_widget_container_bg_color" value="<?php echo get_option('twitter_widget_container_bg_color'); ?>" /></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">Footer Link URL</th>
                            <td><input type="text" class="regular-text" style="height:25px;" name="twitter_widget_footer_link" value="<?php if (get_option('twitter_widget_footer_link')) {
                                           echo get_option('twitter_widget_footer_link');
                                       } else {
                                           echo 'http://jobyj.in/twitter-follow-box-widget/';
                                       } ?>" /></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"></th>
                            <td>
                                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
                            </td>
                        </tr>


                    </table>



                </form>
            </td>
            <td style="vertical-align: top; text-align: left;" id="twitter-iframe">
                <iframe src="//www.jobyj.in/twitter-follow-box-widget/wordpress.html" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:500px; height:700px;" allowTransparency="true"></iframe>
            </td>
        </tr>
    </table>
    <script type="text/javascript">
        jQuery(document).ready(function(){
            jQuery('.color-picker').click(function(){
                var ele=jQuery(this);
                ele.ColorPicker({
                    onSubmit: function(hsb, hex, rgb, el) {
                        jQuery(el).val(hex);
                        jQuery(el).ColorPickerHide();
                    },
                    onBeforeShow: function () {
                        jQuery(this).ColorPickerSetColor(this.value);
                    },
                    onChange: function (hsb, hex, rgb) {
                        ele.val(hex);
                    }
                });
            });
        });
    </script>
</div>
<?php } ?>