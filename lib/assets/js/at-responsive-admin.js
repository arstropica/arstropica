// ArsTropica  Responsive Admin JS
var setFonts = function(fonts) {
	if (typeof fonts == 'object' && typeof fonts.error == 'undefined'){
	    var font_choosers = $('.wp-custom-font-family-chooser');
	    font_choosers.each(function(){
	        var _chooser = $(this);
	        var current_value = _chooser.data('selected-option');
	        for (var i = 0; i < fonts.items.length; i++) {      
	            _chooser
	            .append($("<option></option>")
	            .attr("value", fonts.items[i].family)
	            .attr("selected", fonts.items[i].family === current_value)
	            .text(fonts.items[i].family));
	        }    
	    });                             
	}
};

(function($) {

    function assign(obj, path, value) {
        var _path = path.replace(/\]/g,"");
        var keyPath = _path.split("[");
        lastKeyIndex = keyPath.length-1;
        for (var i = 1; i < lastKeyIndex; ++ i) {
            key = keyPath[i];
            if (!(key in obj))
                obj[key] = {}
            obj = obj[key];
        }
        obj[keyPath[lastKeyIndex]] = value;
    }

    String.prototype.escapeHTML = function() {
        return this.replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
    }

    String.prototype.unEscapeHTML = function() {
        return this.replace(/&amp;/g, "&")
        .replace(/&lt;/g, "<")
        .replace(/&gt;/g, ">")
        .replace(/&quot;/g, "\"")
        .replace(/&#039;/g, "'");
    }

    // Object for creating WordPress 3.5 media upload menu 
    // for selecting theme images.
    wp.media.dsMediaManager = {
        init: function() {

            var controllerName = '';

            // Create the media frame.
            this.frame = wp.media.frames.dsMediaManager = wp.media({
                title: 'Choose Image',
                library: {
                    type: 'image'
                },
                button: {
                    text: 'Select Image',
                }
            });

            // When an image is selected, run a callback.
            this.frame.on( 'select', function() {
                // Grab the selected attachment.
                var attachment = wp.media.dsMediaManager.frame.state().get('selection').first(),
                controllerName = wp.media.dsMediaManager.$el.data('controller');

                controller = wp.customize.control.instance(controllerName);
                controller.thumbnailSrc(attachment.attributes.url);
                controller.setting.set(attachment.attributes.url);
            });

            $('.choose-from-library-link').click( function( event ) {
                wp.media.dsMediaManager.$el = $(this);
                controllerName = $(this).data('controller');
                event.preventDefault();

                wp.media.dsMediaManager.frame.open();
            });

            $('.uploaded-favicons .thumbnail').click( function( event ) {
                console.log('fired');
                var attachment = $(this).find('IMG').attr('src');
                controllerName = $(this).closest('.uploaded-favicons').data('controller');
                controller = wp.customize.control.instance(controllerName);
                controller.thumbnailSrc(attachment);
                controller.setting.set(attachment);
                // event.preventDefault();
            });

        } // end init
    }; // end dsMediaManager

    wp.media.dsMediaManager.init();

    // Reset Theme Options
    wp.customize( 'at_responsive_reset_control', function( value ) {
        value.bind(
        function( to ) {
            if (to == 1) {
                $.post( ajax_url, 
                { 
                    action: 'at_theme_reset',
                    reset_value: to
                },
                function( response ) {
                    $( '.at-reset-info' ).html( response );
                    location.reload(true);
                }                                                                              
                );
            }
        }
        );
    } );

    $('.at-reset-settings').click( function( e ) {

        e.preventDefault();

        if (confirm('You are about to reset all the custom Styling options for this theme.  Are you sure ?')) {

            wp.customize(
            'at_responsive_reset_control',
            function( obj ) {
                obj.set('1');
            }
            );                                                                                     

        }

        return false;

    });

    // Import Theme Options
    wp.customize( 'at_responsive_transfer_control', function( value ) {
        value.bind(
        function( to ) {
            console.log(to);
            if (to > 1) {
                $.post( ajax_url, 
                { 
                    action: 'at_theme_transfer',
                    import_value: to
                },
                function( response ) {
                    $( '.at-transfer-info' ).html( response );
                    setTimeout(function(){location.reload(true);}, 1000);
                }                                                                              
                );
            }
        }
        );
    } );

    $('.at-import-settings').click( function( e ) {

        e.preventDefault();

        if (confirm('You are about to import options and override all existing options for this theme.  Are you sure ?')) {

            wp.customize(
            'at_responsive_transfer_control',
            function( obj ) {
                obj.set('1');
            }
            );                                                                                     

        }

        return false;

    });

    wp.customize.PresetControl = wp.customize.Control.extend({
        ready: function() {
            var control = this,
            panels;

            this.library = this.container.find('.library');

            this.controllerName = 'at_responsive[settings][schemes]';

            this.addPreset = $.proxy( this.addPreset, this );

            this.clearPreset = $.proxy( this.clearPreset, this );

            this.savePreset = $.proxy( this.savePreset, this );

            this.reload = $.proxy( this.reload, this );

            this.Preset = $.extend({
                library:        this.library,
                container:      this.container,
                addPreset:      this.addPreset,
                clearPreset:    this.clearPreset,
                savePreset:     this.savePreset,
                reload:         this.reload,
                controllerName: this.controllerName,
            }, this.Preset || {} );

            // Generate tab objects
            this.tabs = {};
            panels    = this.library.find('.library-content');

            this.library.children('ul').children('li').each( function() {
                var link  = $(this),
                id    = link.data('customizeTab'),
                panel = panels.filter('[data-customize-tab="' + id + '"]');

                control.tabs[ id ] = {
                    both:  link.add( panel ),
                    link:  link,
                    panel: panel
                };
            });

            if (this.setting() != '') {
                control.container.addClass('edit');
            }

            // Bind tab switch events
            this.library.children('ul').on( 'click keydown', 'li', function( event ) {
                if ( event.type === 'keydown' &&  13 !== event.which ) // enter
                    return;

                var id  = $(this).data('customizeTab'),
                tab = control.tabs[ id ];

                event.preventDefault();

                if ( tab.link.hasClass('library-selected') )
                    return;

                control.selected.both.removeClass('library-selected');
                control.selected = tab;
                control.selected.both.addClass('library-selected');
            });

            // Bind events.

            // Select a tab
            panels.each( function() {
                var tab = control.tabs[ $(this).data('customizeTab') ];

                // Select the first visible tab.
                if ( ! tab.link.hasClass('hidden') ) {
                    control.selected = tab;
                    tab.both.addClass('library-selected');
                    return false;
                }
            });

            this.container.on( 'click', '.at-new-preset', function(e){
                e.preventDefault();
                control.container.find('.at-preset-name').val('').attr('readonly', false);
                control.container.find('.at-preset-css-code').val('');
                control.container.toggleClass('open');
            });

            this.container.on( 'change', '.dropdown', function(e) {
                e.preventDefault();
                if (this.value == "") {
                    control.container.removeClass('edit');
                    control.container.find('.at-preset-name').attr('readonly', false);                    
                    return;
                }
                var label = $(this).find('OPTION:selected').text();
                control.container.addClass('edit');
                control.setting.set( label );
                return false;
            });

            this.container.on( 'click', '.at-edit-preset', function(e){
                e.preventDefault();
                var id = control.setting();
                $.post( ajax_url, 
                { 
                    action:             'at_theme_preset',
                    at_preset_name:     id
                },
                function( response ) {
                    if (response) {
                        control.Preset.container.addClass('open');
                        control.Preset.container.find('.at-preset-name').val(id).attr('readonly', true);
                        control.Preset.container.find('.at-preset-css-code').val(response);
                    }
                }                                                                              
                );
                return false;
            });

            this.container.on( 'click', '.at-merge-preset', function(e){
                e.preventDefault();
                var id = control.setting();
                $.post( ajax_url, 
                { 
                    action:             'at_preset_merge',
                    at_preset_name:     id
                },
                function( response ) {
                    if (response == 1) {
                        location.reload(true);
                    }
                }                                                                              
                );
                return false;
            });

            this.container.on( 'click', '.save-preset.button', function(e){
                e.preventDefault();
                var id = control.container.find('.at-preset-name').val();
                var css = control.container.find('.at-preset-css-code').val().escapeHTML();
                if (id && (css.length > 10)) {
                    control.container.find('.at-preset-name').css('border', '');
                    control.container.find('.at-preset-css-code').css('border', '');
                    control.savePreset( id, css );
                }
                if (css.length == 0) {
                    control.container.find('.at-preset-css-code').css('border', '1px solid red');
                }
                if (! id) {
                    control.container.find('.at-preset-name').css('border', '1px solid red');
                }
                return false;
            });

            this.container.on( 'click', '.remove-preset.button', function(e){
                e.preventDefault();
                var id = control.container.find('.at-preset-name').val();
                if (id) {
                    control.clearPreset( id );
                }
                return false;
            });

            this.container.on( 'click', '.load-preset.button', function(e){
                e.preventDefault();
                control.addPreset(e);
                return false;
            });


        },
        savePreset: function( id, css ) {
            var control = this,
            preset_name,
            theme_mod,
            elements,
            presets,
            at_responsive = {};
            try {
                presets = $.parseJSON(decodeURIComponent(wp.customize.control.instance('at_responsive[settings][presets]').setting.get()));
            } catch (e) {
                presets = {};
            }
            if (! presets || presets === null) presets = {};
            if (css && (css.length > 0)) {
                preset_name = id;
                theme_mod = wp.customize.get();
                for (key in theme_mod) {
                    if (key.indexOf('at_responsive[elements]') === 0) {
                        // eval(key.replace(/\[/g, "['").replace(/\]/g, "']") + " = theme_mod[key];");
                        assign(at_responsive, key, theme_mod[key]);
                    }
                }
                presets[id] = JSON.stringify(at_responsive['elements']);
            } else {
                preset_name = false;
                if (typeof presets[id] != 'undefined') {
                    delete presets[id];
                }
            }
            wp.customize.control.instance('at_responsive[settings][presets]').setting.set(encodeURIComponent(JSON.stringify(presets)));
            control.reload(presets, preset_name);
        },
        clearPreset: function( id ) {
            this.savePreset( id, '' );
            wp.customize.control.instance(this.controllerName).setting.set('');
        },
        addPreset: function(e) {
            var control = this;
            e.preventDefault();
            $.post( ajax_url, 
            { 
                action: 'at_theme_css',
                at_customize: 'on',
                customized: JSON.stringify(wp.customize.get())
            },
            function( response ) {
                control.Preset.container.find('.at-preset-css-code').val(response);
            }                                                                              
            );
            return false;
        },
        reload: function(presets, selected) {
            var control = this;
            presets = presets || false;
            selected = selected || false;
            if (! presets) {
                $.getJSON( ajax_url, 
                { 
                    action: 'at_theme_options',
                })
                .done(function( response ) {
                    if (response && typeof response == 'object') {
                        if (typeof response['settings'] == 'object') {
                            if (typeof response['settings']['presets'] == 'object') {
                                control.Preset.container.find('.dropdown').empty().append($('<option value="">Choose Preset</option>'));
                                $.each(response['settings']['presets'], function(key, value) {
                                    control.Preset.container.find('.dropdown') 
                                    .append($("<option></option>")
                                    .attr("value", key)
                                    .attr("selected", (key == selected))
                                    .text(key)); 
                                });
                            }
                        }
                    }
                });                                                                              
            } else {
                control.Preset.container.find('.dropdown').empty().append($('<option value="">Choose Preset</option>'));
                $.each(presets, function(key, value) {
                    control.Preset.container.find('.dropdown') 
                    .append($("<option></option>")
                    .attr("value", key)
                    .attr("selected", (key == selected))
                    .text(key)); 
                });
            }
            if (selected || selected != '') {
                control.container.addClass('edit');
                wp.customize.control.instance(this.controllerName).setting.set(selected);
            } else {
                control.container.removeClass('edit');
            }
            control.container.removeClass('open');
        },
    });
    wp.customize.TransferControl = wp.customize.UploadControl.extend({
        ready: function() {
            var control = this,
            panels;

            this.library = this.container.find('.library');

            this.controllerName = 'at_responsive[transfer][options]';

            this.Transfer = $.extend({
                library:        this.library,
                container:      this.container,
                controllerName: this.controllerName,
            }, this.Transfer || {} );

            // Generate tab objects
            this.tabs = {};
            panels    = this.library.find('.library-content');

            this.library.children('ul').children('li').each( function() {
                var link  = $(this),
                id    = link.data('customizeTab'),
                panel = panels.filter('[data-customize-tab="' + id + '"]');

                control.tabs[ id ] = {
                    both:  link.add( panel ),
                    link:  link,
                    panel: panel
                };
            });

            // Bind tab switch events
            this.library.children('ul').on( 'click keydown', 'li', function( event ) {
                if ( event.type === 'keydown' &&  13 !== event.which ) // enter
                    return;

                var id  = $(this).data('customizeTab'),
                tab = control.tabs[ id ];

                event.preventDefault();

                if ( tab.link.hasClass('library-selected') )
                    return;

                control.selected.both.removeClass('library-selected');
                control.selected = tab;
                control.selected.both.addClass('library-selected');
            });

            // Bind events.

            // Select a tab
            panels.each( function() {
                var tab = control.tabs[ $(this).data('customizeTab') ];

                // Select the first visible tab.
                if ( ! tab.link.hasClass('hidden') ) {
                    control.selected = tab;
                    tab.both.addClass('library-selected');
                    return false;
                }
            });

            this.init_uploader();

        },
        init_uploader : function(){
            var api = wp.customize;
            var control = this;
            this.params.removed = this.params.removed || '';

            this.success = $.proxy( this.success, this );

            this.uploader = $.extend({
                container: this.container,
                browser:   this.container.find('.upload'),
                dropzone:  this.container.find('.upload-dropzone'),
                success:   this.success,
                plupload:  {},
                params:    {}
            }, this.uploader || {} );

            if ( control.params.extensions ) {
                control.uploader.plupload.filters = [{
                    title:      api.l10n.allowedFiles,
                    extensions: control.params.extensions
                }];
            }

            if ( control.params.context )
                control.uploader.params['post_data[context]'] = this.params.context;

            if ( api.settings.theme.stylesheet )
                control.uploader.params['post_data[theme]'] = api.settings.theme.stylesheet;

            this.uploader = new wp.Uploader( this.uploader );

            this.remover = this.container.find('.remove');
            this.remover.on( 'click keydown', function( event ) {
                if ( event.type === 'keydown' &&  13 !== event.which ) // enter
                    return;

                control.setting.set( control.params.removed );
                event.preventDefault();
            });

            this.removerVisibility = $.proxy( this.removerVisibility, this );
            this.setting.bind( this.removerVisibility );
            this.removerVisibility( this.setting.get() );
        },
        success: function( attachment ) {
            this.setting.set( attachment.get('id') );
        },
    });

    wp.customize.controlConstructor['presets'] = wp.customize.PresetControl;
    wp.customize.controlConstructor['styles'] = wp.customize.Control;
    wp.customize.controlConstructor['transfer'] = wp.customize.TransferControl;


}(jQuery));