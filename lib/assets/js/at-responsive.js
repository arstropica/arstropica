// ArsTropica  Responsive Custom JS

(function($){
    /*Equal Heights Plugin*/
    $.fn.equalHeight = function(options) {

        var defaults = {  
            child: false,
            auto : false
        };  
        var options = $.extend(defaults, options); 

        var el = $(this);
        if (el.length > 0 && !el.data('equalHeight')) {
            $(window).bind('resize.equalHeight', function() {
                el.equalHeight();
            });
            el.data('equalHeight', true);
        }

        if( options.child && options.child.length > 0 ){
            var elmtns = $(options.child, this);
        } else {
            var elmtns = $(this).children();
        }

        var prevTop = 0;
        var max_height = 0;
        var elements = [];
        elmtns.height('auto').each(function() {

            var thisTop = this.offsetTop;

            if (prevTop > 0 && prevTop != thisTop) {
                if (options.auto === false) {
                    $(elements).height(max_height);
                }
                max_height = $(this).height();
                elements = [];
            }
            max_height = Math.max(max_height, $(this).height());

            prevTop = this.offsetTop;
            elements.push(this);
        });
        if (options.auto) {
            $(elements).height('auto');
        } else {
            $(elements).height(max_height);
        }
    };

    /*On Show / Hide Listener*/
    $.each(['show', 'hide'], function (i, ev) {
        var el = $.fn[ev];
        $.fn[ev] = function () {
            this.trigger(ev);
            return el.apply(this, arguments);
        };
    });

    /*Add Media Query Classes to Doc*/
    if (typeof syze == 'function' || typeof syze == 'object') {
        // set sizes and size names
        syze.sizes(320, 480, 768, 992, 1200, 1310, 1920).names({ 320:'MobileTall', 480:'MobileWide', 768:'TabletTall', 992:'DeskTop', 1200:'DeskTopLg', 1310:'DeskTopXL', 1920:'HdSizeWide' });

        // How it might work:
        // a browser window of 320x480 (eg. an iPhone upright) gets: "isMobileTall ltMobileWide ltTabletTall ltTabletWide ltHdSizeWide"
        // a browser window of 1920x1080 (eg. a Desktop or TV) gets: "gtMobileTall gtMobileWide gtTabletTall gtTabletWide isHdSizeWide"

        // To clarify:
        // a browser window of 0-479px is "MobileTall"
        // a browser window of 480-767px is "MobileWide"
        // a browser window of 768-1023px is "TabletTall"
        // a browser window of 1024-1079px is "TabletWide"
        // a browser window of 1920px+ is "HdSizeWide"
    }

    /*WP Search AutoSuggest*/
    var substringMatcher = function(strs) {
        return function findMatches(q, cb) {
            var matches, substrRegex;

            // an array that will be populated with substring matches
            matches = [];

            // regex used to determine if a string contains the substring `q`
            substrRegex = new RegExp(q, 'i');

            // iterate through the pool of strings and for any string that
            // contains the substring `q`, add it to the `matches` array
            $.each(strs, function(i, str) {
                if (substrRegex.test(str)) {
                    // the typeahead jQuery plugin expects suggestions to a
                    // JavaScript object, refer to typeahead docs for more info
                    matches.push({ value: str });
                }
            });

            cb(matches);
        };
    };
    $(':input[data-provide="typeahead"]')
    .each(function(){
        try {
            var input = $(this);
            var data = $.map(input.data('source'), function(el) { return el; });
            input.suggest(data, {
                suggestionColor   : '#888888',
                moreIndicatorClass: 'suggest-more',
                moreIndicatorText : '&hellip;',
            });
        } catch (err) {
            // silence
            // console.log(err.message);
        }
    });

    /*Navbar Height Class*/
    $(window).on('load resize', function(e){
        if ($('nav.navbar').height() > 100) {
            $('BODY').addClass('tall-nav');
            if ($('A.navbar-brand.desktop.hidden-xs').length > 0) {
                $('.navbar-layout.layout-top.navbar-layout').removeClass('col-md-8').addClass('col-md-12');
                $('.navbar-icons.navbar-layout.navbar-right').removeClass('col-md-4').addClass('col-md-12');
            }
        } else {
            $('BODY').removeClass('tall-nav');
            if ($('A.navbar-brand.desktop.hidden-xs').length > 0) {
                $('.navbar-layout.layout-top.navbar-layout').removeClass('col-md-12').addClass('col-md-8');
                $('.navbar-icons.navbar-layout.navbar-right').removeClass('col-md-12').addClass('col-md-4');
            }
        }
    });

    /*Navbar Hover Accordion*/
    /*$('.navbar LI.dropdown')
    .hover(
    function(e){
    $(this).addClass('open');
    },
    function(e){
    $(this).removeClass('open');
    }
    )
    .focus(
    function(e){
    $(this).addClass('open');
    },
    function(e){
    $(this).removeClass('open');
    }
    );*/

    /*Center Fancy Dropdown*/
    $(window).on('load resize', function(e){
        $('.caption .navbar .navbar-nav.fancy .dropdown').on('show mouseenter touchstart', function(e){
            var _$dropdown = $(this).find('.dropdown-menu');
            if (_$dropdown.length > 0) {
                if (($(this).is('.open') || e.type == 'mouseenter' || e.type == 'touchstart') && ($(this).parents('.gtMobileWide').length)) {
                    var _width = _$dropdown.width();
                    var _pwidth = $(this).width();
                    var _left = (((_width /2) * -1) + (_pwidth / 2));
                    _$dropdown.css({marginLeft: _left + 'px'});
                    var _offset = _$dropdown.offset();
                    var _offsetL = Math.min(_offset.left, 0);
                    var _left = (((_width /2) * -1) + (_pwidth / 2) - _offsetL);
                    _$dropdown.css({marginLeft: _left + 'px'});
                } else {
                    _$dropdown.css({marginLeft: ''});            
                }                          
            }
        });
    });

    /*Close Touch Menu(s) on outside event*/
    $(document).on('mouseup touchstart', function (e)
    {
        var touch_menu = $('.gtMobileWide .navbar .navbar-nav.fancy .dropdown');

        if (!touch_menu.is(e.target) // if the target of the click isn't the container...
        && touch_menu.has(e.target).length === 0) // ... nor a descendant of the container
            {
            // touch_menu.hide();
            touch_menu.dropdown('toggle');
        }
    });    

    /*Detectizr*/
    Detectizr.detect();

    /*Equal Heights*/
    $(".eq-parent").eqHeight(".eq-height", {min_height: true});

    // Bootstrap Pop-overs
    // Check if Bootstrap is loaded
    if (typeof $().popover == 'function') {
        var poVisible, poClicked, delay = false;
        $('.like-action[rel=popover]').popover({
            container: 'body',
            html:true,
            placement: function(context, src) {
                $(context).addClass('fblike');
                return 'top'; // - 'top' placement in my case
            },
            title:function(){
                if ($(this).attr('title'))
                    return $(this).attr('title');
            },
            content:function(){
                if ($(this).attr('data-source')) {
                    // var _content = $($(this).data('source')).html();
                    // $($(this).data('source')).remove();
                    return $($(this).data('source')).html();
                }
                return false;
            },
            trigger: 'manual',
        }).on("mouseenter click touchstart focus", function (f) {
            f.stopPropagation();
            f.preventDefault(); 
            var _this = this;
            if (["click", "touchstart"].indexOf(f.type) >= 0) {
                if (! poClicked && ! poVisible) {
                    $(this).popover("show");
                    poClicked = true;
                    poVisible = true;
                } else if (! poClicked && poVisible) {
                    poClicked = true;
                } else if (poVisible) {
                    $(this).popover("hide");
                    poClicked = false;
                    poVisible = true;
                }
            } else if (! poClicked) {
                if (! poVisible) {
                    poVisible = true;
                }
                $(this).popover("show");
                $(this).siblings(".popover").on("mouseleave", function () {
                    $(_this).popover('hide');
                });    
            }
            return false;                     
        }).on("mouseleave blur", function (f) {
            if (poClicked) {
                return;
            }
            var _this = this;
            setTimeout(function () {
                if (!$(".popover:hover").length) {
                    $(_this).popover("hide")
                }
            }, 100);
        });
        $(document).bind("click touchstart", function(f) {
            if(($(f.target).not('.like-action[rel=popover]')) && ($(f.target).parents('.like-action[rel=popover]').length == 0)){
                if(poVisible) {
                    $('.like-action[rel=popover]').popover('hide');
                    poVisible = false;
                    poClicked = false;
                }
            }  
        });
    }

})(jQuery);
