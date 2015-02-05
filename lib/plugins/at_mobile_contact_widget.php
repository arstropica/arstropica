<?php
    /**
    * ArsTropica  Responsive Framework at_mobile_contact_widget.php
    * 
    * PHP version 5
    * 
    * @category   Theme WordPress Plugins 
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
    * Mobile Contact Widget
    * 
    * @category   Theme WordPress Plugins 
    * @package    WordPress
    * @author     ArsTropica <info@arstropica.com> 
    * @copyright  2014 ArsTropica 
    * @license    http://opensource.org/licenses/gpl-license.php GNU Public License 
    * @version    Release: @package_version@ 
    * @link       http://pear.php.net/package/ArsTropica  Reponsive Framework
    * @subpackage ArsTropica  Responsive Framework
    * @see        References to other sections (if any)...
    */
    class AT_Mobile_Contact_Widget extends WP_Widget {

        /**
        * Description for var
        * @var unknown 
        * @access public  
        */
        var $contact_page;

        /**
        * Widget Constructor.
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        function AT_Mobile_Contact_Widget() {
            global $at_theme_custom;

            // Ajax Form Submission Handler
            add_action( 'wp_ajax_at_mobile_contact_form', array($this, 'at_mobile_contact_form_wp_ajax') );
            add_action( 'wp_ajax_nopriv_at_mobile_contact_form', array($this, 'at_mobile_contact_form_wp_ajax') );        

            $this->contact_page = $at_theme_custom->get_option('plugins/contactform/contactformpage', false);
            $widget_ops = array('classname' => 'AT_Mobile_Contact_Widget', 'description' => 'Mobile Contact Form');
            $this->WP_Widget('AT_Mobile_Contact_Widget', 'Mobile Contact Form', $widget_ops);
            if ($this->contact_page) {
                add_filter('nav_menu_link_attributes', array(&$this, 'at_mobile_contact_form_menu'), 10, 3);
            }
        }

        /**
        * Echo the settings update form
        * 
        * @param array $instance Current settings
        * @since 1.0
        * @return void   
        * @access public 
        */
        function form($instance) {
            global $theme_namespace;
            $instance = wp_parse_args((array) $instance, array('cta' => 'Contact Us', 'thankyou' => 'Thank you for contacting us.  We will be in touch.', 'silent' => 0));
            $cta = $instance['cta'];
            $thankyou = $instance['thankyou'];
            $silent = $instance['silent'];
        ?>
        <p><label for="<?php echo $this->get_field_id('cta'); ?>"><?php _e('Call To Action: ', $theme_namespace); ?><input class="widefat" id="<?php echo $this->get_field_id('cta'); ?>" name="<?php echo $this->get_field_name('cta'); ?>" type="text" value="<?php echo esc_attr($cta); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('thankyou'); ?>"><?php _e('Thank You Message: ', $theme_namespace); ?><textarea class="widefat" id="<?php echo $this->get_field_id('thankyou'); ?>" name="<?php echo $this->get_field_name('thankyou'); ?>"><?php echo esc_textarea($thankyou); ?></textarea></label></p>
        <input id="<?php echo $this->get_field_id('silent'); ?>" name="<?php echo $this->get_field_name('silent'); ?>" type="hidden" value="<?php echo esc_attr($silent); ?>" />
        <?php
        }

        /**
        * Update a particular instance.
        * 
        * @param array $new_instance New settings for this instance as input by the user via form()
        * @param array $old_instance Old settings for this instance
        * @since 1.0
        * @return array Settings to save or bool false to cancel saving
        * @access public  
        */
        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['cta'] = isset($new_instance['cta']) ? $new_instance['cta'] : '';
            $instance['silent'] = isset($new_instance['silent']) ? $new_instance['silent'] : 0;
            $instance['thankyou'] = isset($new_instance['thankyou']) ? $new_instance['thankyou'] : 'Thank you for contacting us.  We will be in touch.';
            return $instance;
        }

        /**
        * Echo the widget content.
        * 
        * @param array $args Display arguments including before_title, after_title, before_widget, and after_widget.
        * @param array $instance The settings for the particular instance of the widget
        * @since 1.0
        * @return void    
        * @access public  
        */
        function widget($args, $instance) {
            global $at_theme_custom;
            extract($args, EXTR_SKIP);
            $cta = empty($instance['cta']) ? 'Contact Us' : $instance['cta'];
            $silent = empty($instance['silent']) ? 0 : $instance['silent'];
            $thankyou = empty($instance['thankyou']) ? false : $instance['thankyou'];


            if (!$silent)
                echo $before_widget;
            // front
            if (!$silent) :
            ?>
            <a class= "btn btn-oval contact-button switch" href="#"  data-toggle="modal" data-target="#contact-modal"><?php echo $cta; ?></a>
            <?php
                add_action('wp_footer', array(&$this, 'at_mobile_contact_form'));
                endif;

            if (!$silent)
                echo $after_widget;
        }

        /**
        * Get current page URI.
        * 
        * @since 1.0
        * @return string   Return 
        * @access public  
        */
        function get_uri() {
            $uri = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
            if ($_SERVER["SERVER_PORT"] != "80")
            {
                $uri .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
            } 
            else 
            {
                $uri .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
            }
            return $uri;
        }

        /**
        * Link modal form to menu item, if enabled in Theme Settings.
        * 
        * @param array   $atts Parameter 
        * @param object  $item Parameter 
        * @param array $args Parameter 
        * @since 1.0
        * @return array   Return 
        * @access public  
        */
        function at_mobile_contact_form_menu($atts, $item, $args) {
            if ($this->contact_page && ($this->contact_page == $item->object_id)) {
                $atts['href'] = '#';
                $atts['data-toggle'] = 'modal';
                $atts['data-target'] = '#contact-modal';
                if (!is_active_widget(false, false, $this->id_base, true)) {
                    add_action('wp_meta', array($this, 'at_mobile_contact_form_silent'), 0);
                }
            }
            return $atts;
        }

        /**
        * Output hidden modal form
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        function at_mobile_contact_form_silent() {
            the_widget('AT_Mobile_Contact_Widget', 'silent=1', array('before_widget' => '', 'after_widget' => '', 'before_title' => '', 'after_title' => ''));
        }

        /**
        * Output modal form
        * 
        * @since 1.0
        * @return void   
        * @access public 
        */
        function at_mobile_contact_form() {
            global $at_theme_custom;
            $settings = $this->get_settings();
            $instance = isset($settings[$this->number]) ? $settings[$this->number] : array();
            $cta = empty($instance['cta']) ? 'Contact Us' : $instance['cta'];
            $thankyou = empty($instance['thankyou']) ? false : $instance['thankyou'];
            $company_name = get_bloginfo('name');
            $company_address = $at_theme_custom->get_address();
            $company_logo = $at_theme_custom->get_option('images/companylogo');
        ?>
        <!-- Beginning of  contact form for CTA -->

        <div class="modal fade" id="contact-modal" tabindex="-1" role="dialog" aria-labelledby="contact-modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="contact-modal-title">
                                <?php echo $cta; ?>
                            </h4>
                        </div>
                        <div class="modal-body">

                            <div class="row modal-legend-row hidden-xs">
                                <?php if ($company_logo) : ?>
                                    <div class="modal-contact-legend modal-thumbnail modal-legend col-md-4 col-sm-4 col-xs-4">
                                        <div class="thumbnail">
                                            <img class="logo" src="<?php echo $company_logo; ?>" alt="<?php echo $company_name; ?>">
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                <div class="modal-contact-legend modal-address modal-legend col-md-8 col-sm-8 col-xs-8">
                                    <p class="contact-location"><strong><?php echo $company_name; ?></strong></p>
                                    <p class="contact-location"><?php echo $company_address; ?></p>
                                </div>
                            </div>

                            <div class="col-md-12 notifications">
                                <div class="alert alert-success hidden success"><strong><span class="glyphicon glyphicon-send"></span> <?php echo $thankyou; ?></strong></div>      
                                <div class="alert alert-danger hidden error"><span class="glyphicon glyphicon-alert"></span><strong class="msg"> Oops, something went wrong.  Please contact <?php echo get_option('admin_email'); ?> for further assistance.</strong></div>
                                <div class="alert alert-danger hidden invalid"><span class="glyphicon glyphicon-alert"></span><strong class="msg"></strong> Some fields are invalid.</div>
                            </div>
                            <div class="col-md-12 modal-inner-content">
                                <form id="modal-contact-form" role="form">

                                    <fieldset>

                                        <!-- Text input-->
                                        <div class="form-group">
                                            <label class="hidden-xs control-label" for="name">Name</label>  
                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
                                                <input id="name" name="name" type="text" placeholder="Enter Your Name (required)" class="form-control required name" required="">
                                            </div>
                                        </div>

                                        <!-- Text input-->
                                        <div class="form-group">
                                            <label class="hidden-xs control-label" for="email">Email</label>  
                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
                                                <input id="email" name="email" type="text" placeholder="Enter Your Email (required)" class="form-control required email" required="">
                                            </div>
                                        </div>

                                        <!-- Text input-->
                                        <div class="form-group">
                                            <label class="hidden-xs control-label" for="phone">Phone</label>  
                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-phone-alt"></span></div>
                                                <input id="phone" name="phone" type="text" placeholder="Enter Your Phone" class="form-control phone" required="">
                                            </div>
                                        </div>

                                        <!-- Textarea -->
                                        <div class="form-group">
                                            <div class="input-group">                     
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-comment"></span></div>
                                                <textarea class="form-control required" id="comment" name="comment" required="" rows="6" placeholder="Enter your comment or question (required)"></textarea>
                                            </div>
                                        </div>

                                    </fieldset>

                                    <!--Leave Blank for Anti-Spam-->
                                    <input type="hidden" class="antispam" name="url" value="" />
                                    <?php wp_nonce_field( 'at_mobile_contact_form', 'at_mobile_contact_nonce' ); ?>
                                    <input name="action" type="hidden" value="at_mobile_contact_form" />
                                    <input type="hidden" name="referrer" value="<?php echo $this->get_uri(); ?>" />
                                    <input type="hidden" name="sender_ip" value="<?php echo (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) ? array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])) : $_SERVER['REMOTE_ADDR']; ?>" />
                                </form>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" id="modal-contact-submit" class="btn btn-primary">Send</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- End of contact form for CTA -->
        <script type="text/javascript">
            (function($) {
                /* form validation plugin */
                $.fn.goValidate = function(options) {
                    var defaults = {
                        submit: false
                    };
                    var options = $.extend(defaults, options);

                    var success = false;

                    var $form = this,
                    $inputs = $form.find(':input');

                    var validators = {
                        name: {
                            regex: /^[A-Za-z -]{3,}$/
                        },
                        pass: {
                            regex: /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/
                        },
                        email: {
                            regex: /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/
                        },
                        phone: {
                            regex: /^[2-9]\d{2}-\d{3}-\d{4}$/,
                        },
                        antispam: {
                            regex: /^$/,
                        }
                    };
                    var validate = function(klass, value) {
                        var isValid = true,
                        error = '';

                        if (!value && /required/.test(klass)) {
                            error = 'This field is required';
                            isValid = false;
                        } else {
                            klass = klass.split(/\s/);
                            $.each(klass, function(i, k) {
                                if (validators[k]) {
                                    if (value && !validators[k].regex.test(value)) {
                                        isValid = false;
                                        error = validators[k].error;
                                    }
                                }
                            });
                        }
                        return {
                            isValid: isValid,
                            error: error
                        }
                    };
                    var showError = function($input) {
                        var klass = $input.attr('class'),
                        value = $input.val(),
                        test = validate(klass, value);

                        $input.removeClass('invalid');
                        $input.closest('.form-group').removeClass('has-error');

                        $('#form-error').addClass('hide');

                        if (!test.isValid) {
                            $input.addClass('invalid');
                            $input.closest('.form-group').addClass('has-error');

                            if (typeof $input.data("shown") == "undefined" || $input.data("shown") == false) {
                                $input.popover('show');
                            }

                        }
                        else {
                            $input.popover('hide');
                        }
                    };

                    $inputs.keyup(function() {
                        showError($(this));
                    });

                    $inputs.on('shown.bs.popover', function() {
                        $(this).data("shown", true);
                    });

                    $inputs.on('hidden.bs.popover', function() {
                        $(this).data("shown", false);
                    });

                    if (options.submit) {
                        $form.submit(function(e) {
                            $inputs.each(function() { /* test each input */
                                if ($(this).is('.required') || $(this).hasClass('invalid')) {
                                    showError($(this));
                                }
                            });
                            if ($form.find(':input.invalid').length) { /* form is not valid */
                                e.preventDefault();
                                $('#contact-modal .notifications .invalid').removeClass('hidden');
                                $('#form-error').toggleClass('hide');
                                success = false;
                            } else {
                                $('#contact-modal .notifications .invalid').addClass('hidden');
                                success = true;
                            }
                        });
                        return success;
                    } else {
                        $inputs.each(function() { /* test each input */
                            if ($(this).is('.required') || $(this).hasClass('invalid')) {
                                showError($(this));
                            }
                        });
                        if ($form.find(':input.invalid').length) { /* form is not valid */
                            $('#contact-modal .notifications .invalid').removeClass('hidden');
                            $('#form-error').toggleClass('hide');
                        } else {
                            $('#contact-modal .notifications .invalid').addClass('hidden');
                            success = true;
                        }
                        return success;
                    }
                    return this;
                };

                $('#modal-contact-submit').on('click', function(e) {
                    e.preventDefault();
                    $('#modal-contact-form').submit();
                    return false;
                });

                $('#modal-contact-form').on('submit', function(e) {
                    e.preventDefault();
                    var validated = $(this).goValidate({submit: false});

                    if (validated) {
                        var frmSerialized = $(this).serialize();
                        <?php echo "var submit_url = '" . admin_url( 'admin-ajax.php' ) . "';\n"; ?>
                        $.ajax({
                            type: 'POST',
                            url: submit_url,
                            data: frmSerialized,
                            success: function(data) {
                                var success = false;
                                try {
                                    if (data && typeof data == 'object') {
                                        if ((typeof data['outcome'] != 'undefined') && (data['outcome'] == 1)) {
                                            success = true;
                                            $('#contact-modal .notifications .success').removeClass('hidden');
                                        }
                                    }
                                    if (!success)
                                    $('#contact-modal .notifications .error').removeClass('hidden');
                                } catch (e) {
                                    $('#contact-modal .notifications .error').removeClass('hidden');
                                }
                            },
                            complete: function() {
                                $('#modal-contact-form').hide();
                                $('#modal-contact-submit').hide();
                            },
                            dataType: 'json',
                            async: true
                        });
                    }
                });
            })(jQuery);
        </script>
        <?php
        }

        /** 
        * Ajax Form Submission Email Handler
        *
        * @since 1.0
        * @return void   
        * @access public 
        */
        function at_mobile_contact_form_wp_ajax() {

            if ( isset( $_POST['at_mobile_contact_nonce'] ) && wp_verify_nonce( $_POST['at_mobile_contact_nonce'], 'at_mobile_contact_form' ) ) {
                $name = sanitize_text_field($_POST['phone']);
                $email = sanitize_email($_POST['email']);
                $phone = sanitize_text_field($_POST['phone']);
                $comment = wp_kses_data($_POST['comment']);

                $headers[] = 'From: ' . $name . ' <' . $email . '>' . "\r\n";
                $headers[] = 'Content-type: text/html' . "\r\n"; //Enables HTML ContentType. Remove it for Plain Text Messages
                $to = get_option( 'admin_email' );

                wp_mail( $to, $subject, $message, $headers );
            }
            die(); // Important
        }

    }

    /**
    * Register and load the widget, if enabled in Theme Settings.
    * 
    * @since 1.0
    * @return void 
    */
    function at_mobile_contact_load_widget() {
        global $at_theme_custom;
        if (class_exists('at_responsive_theme_mod')) {
            if (!is_object($at_theme_custom)) {
                $at_theme_custom = new at_responsive_theme_mod();
            }
            $enable_widget = $at_theme_custom->get_option('plugins/contactform/enablecontactform', false);
            if ($enable_widget) {
                register_widget('AT_Mobile_Contact_Widget');
            }
        }
    }

    add_action('widgets_init', 'at_mobile_contact_load_widget', 10);
?>