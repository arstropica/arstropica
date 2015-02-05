/*!
* Suggest jQuery plugin
*
* Copyright (c) 2013 Florian Plank (http://www.polarblau.com/)
* Dual licensed under the MIT (MIT-LICENSE.txt)
* and GPL (GPL-LICENSE.txt) licenses.
*
* Modified for ArsTropica  Responsive Theme
* by Akin Williams
* 
* USAGE:
*
* $('#container').suggest(haystack, {
*   suggestionColor   : '#cccccc',
*   moreIndicatorClass: 'suggest-more',
*   moreIndicatorText : '&hellip;'
* });
*
*/

(function($) {

    $.fn.suggest = function(source, options) {

        var settings = $.extend({
            suggestionColor       : '#ccc',
            moreIndicatorClass    : 'suggest-more',
            moreIndicatorText     : '&hellip;'
        }, options);

        return this.each(function() {

            $this = $(this);

            // this helper will show possible suggestions
            // and needs to match the input field in style
            var inputStyleObj = {
                'position'        : 'absolute',
                'height'          : $this.trueHeight() + 'px',
                'line-height'     : $this.css('line-height'),
                // 'width'           : $this.width(),
                'width'           : '100%',
                'top'             : $this.css('borderTopWidth'),
                'left'            : $this.css('borderLeftWidth'),
                'padding'         : $this.cssShortForAllSides('padding'),
                'margin'          : $this.cssShortForAllSides('margin'),
                'fontFamily'      : $this.css('fontFamily'),
                'fontSize'        : $this.css('fontSize'),
                'fontStyle'       : $this.css('fontStyle'),
                'lineHeight'      : $this.css('lineHeight'),
                'fontWeight'      : $this.css('fontWeight'),
                'letterSpacing'   : $this.css('letterSpacing'),
                'text-align'      : $this.css('text-align'),
                'backgroundColor' : $this.css('backgroundColor'),
                '-webkit-border-radius': $this.css('-webkit-border-radius'),
                '-ms-border-radius': $this.css('-ms-border-radius'),
                '-o-border-radius': $this.css('-o-border-radius'),
                'border-radius'   : $this.css('border-radius'),
                'color'           : settings.suggestionColor
            };

            var bRadius = $this.css('border-radius');

            if (typeof bRadius != 'undefined' && bRadius.length > 0) {
                inputStyleObj['border-radius'] = bRadius;
            } else {
                inputStyleObj['borderTopLeftRadius'] = $this.css('borderTopLeftRadius');
                inputStyleObj['borderTopRightRadius'] = $this.css('borderTopRightRadius');
                inputStyleObj['borderBottomLeftRadius'] = $this.css('borderBottomLeftRadius');
                inputStyleObj['borderBottomRightRadius'] = $this.css('borderBottomRightRadius');
            }

            var $suggest = $('<div/>', {
                'css' : inputStyleObj
            });
            $suggest.addClass('suggest-bg');

            var $more = $('<span/>', {
                'css' : {
                    'position'        : 'absolute',
                    'top'             : $suggest.height() + parseInt($this.css('fontSize'), 10) / 2,
                    'left'            : $suggest.width(),
                    'display'         : 'block',
                    'fontSize'        : $this.css('fontSize'),
                    'fontFamily'      : $this.css('fontFamily'),
                    'color'           : settings.suggestionColor
                },
                'class'             : settings.moreIndicatorClass
            })
            .html(settings.moreIndicatorText)
            .hide();

            $this
            .attr({
                'autocomplete'    : "off",
                'spellcheck'      : "false",
                'dir'             : "ltr"
            })
            // by setting the background to transparent, we will
            // be able to "see through" to the suggestion helper
            .css({
                'background'      : 'transparent'
            })
            .wrap($('<div/>', {
                'css': {
                    'position'      : 'relative',
                    'paddingBottom' : '1em'
                }
            }))

            .bind('keydown.suggest', function(e){
                var code = (e.keyCode ? e.keyCode : e.which);

                // the tab key will force the focus to the next input
                // already on keydown, let's prevent that
                // unless the alt key is pressed for convenience
                if (code == 9 && !e.altKey) {
                    e.preventDefault();

                    // let's prevent default enter behavior while a suggestion
                    // is being accepted (e.g. while submitting a form)
                } else if (code == 13) {
                    if (!$suggest.is(':empty')) {
                        e.preventDefault();
                    }

                    // use arrow keys to cycle through suggestions
                } else if (code == 38 || code == 40) {
                    e.preventDefault();
                    var suggestions = $(this).data('suggestions');

                    if (suggestions.all.length > 1) {
                        // arrow down:
                        if (code == 40 && suggestions.index < suggestions.all.length - 1) {
                            suggestions.suggest.html(suggestions.all[++suggestions.index]);
                            // arrow up:
                        } else if (code == 38 && suggestions.index > 0) {
                            suggestions.suggest.html(suggestions.all[--suggestions.index]);
                        }
                        $(this).data('suggestions').index = suggestions.index;
                    }
                }
            })

            .bind('keyup.suggest', function(e) {
                var code = (e.keyCode ? e.keyCode : e.which);

                // Have the arrow keys been pressed?
                if (code == 38 || code == 40) {
                    return false;
                }

                // be default we hide the "more suggestions" indicator
                $more.hide();

                // what has been input?
                var needle = $(this).val();

                // convert spaces to make them visible
                var needleWithWhiteSpace = needle.replace(' ', '&nbsp;');

                // accept suggestion with 'enter' or 'tab'
                // if the suggestion hasn't been accepted yet
                if (code == 9 || code == 13) {
                    // only accept if there's anything suggested
                    if ($suggest.text().length > 0) {
                        e.preventDefault();
                        var suggestions = $(this).data('suggestions');
                        $(this).val(suggestions.terms[suggestions.index]);
                        // clean the suggestion for the looks
                        $suggest.empty();
                        return false;
                    }
                }

                // make sure the helper is empty
                $suggest.empty();

                // if nothing has been input, leave it with that
                if (!$.trim(needle).length) {
                    return false;
                }

                // see if anything in source matches the input
                // by escaping the input' string for use with regex
                // we allow to search for terms containing specials chars as well
                var regex = new RegExp('^' + needle.replace(/[-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&"), 'i');
                var suggestions = [], terms = [];
                for (var i = 0, l = source; i < l.length; i++) {
                    if (regex.test(l[i])) {
                        terms.push(l[i]);
                        suggestions.push(needleWithWhiteSpace + l[i].slice(needle.length));
                    }
                }
                if (suggestions.length > 0) {
                    // if there's any suggestions found, use the first
                    // don't show the suggestion if it's identical with the current input
                    if (suggestions[0] !== needle) {
                        $suggest.html(suggestions[0]);
                    }
                    // store found suggestions in data for use with arrow keys
                    $(this).data('suggestions', {
                        'all'    : suggestions,
                        'terms'  : terms,
                        'index'  : 0,
                        'suggest': $suggest
                    });

                    // show the indicator that there's more suggestions available
                    // only for more than one suggestion
                    if (suggestions.length > 1) {
                        $more.show();
                    }
                }
            })

            // clear suggestion on blur
            .bind('blur.suggest', function(){
                $suggest.empty();
            });

            // insert the suggestion helpers within the wrapper
            $suggest.insertAfter($this);
            $more.insertAfter($suggest);

        });

    };

    /* A little helper to calculate the sum of different
    * CSS properties around all four sides
    *
    * EXAMPLE:
    * $('#my-div').cssSum('padding');
    */
    $.fn.cssShortForAllSides = function(property) {
        var $self = $(this), sum = [];
        var properties = $.map(['Top', 'Right', 'Bottom', 'Left'], function(side){
            return property + side;
        });
        $.each(properties, function(i, e) {
            sum.push($self.css(e) || "0");
        });
        return sum.join(' ');
    };

    /*Get True Height of Element*/
    $.fn.trueHeight = function() {
        var $self = $(this), height = 0, border = 0;
        var D = $self.get()[0];
        height = Math.max(
        D.scrollHeight,
        D.offsetHeight,
        D.clientHeight,
        $self.height(),
        parseInt($self.css('height').replace('px', ''))
        );
        border = parseInt($self.css('borderWidth').replace("px", "")) ? parseInt($self.css('borderWidth').replace("px", "") * 2) : (parseInt($self.css('border-top-width').replace("px", "")) + parseInt($self.css('border-bottom-width').replace("px", "")));
        if (border > 0) {
            height = height - border;
        }
        return height;
    }
})(jQuery);
