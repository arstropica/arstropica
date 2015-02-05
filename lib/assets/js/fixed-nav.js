// ArsTropica  Responsive Sticky Nav

jQuery( function($) {

    var nav = $( '#nav' );
    var navpos = nav.position();

    var admin_bar = 0;

    if ( $('body').hasClass('admin-bar') )
        admin_bar = $('#wpadminbar').height();

    $(".at_responsive_hero").css({
        "margin-top" : nav.height() + "px",
    });

    if ( $(this).scrollTop() >= nav.offset().top ) {

        // nav.addClass( 'fixed' );

        console.log('scroll is greater than nav');
        $(".at_responsive_hero").css({
            "margin-top" : 0 + "px",
        });

    }

    $( window ).scroll( function() {

        if ( $(this).scrollTop() >= nav.offset().top ) {

            // nav.addClass( 'fixed' );
            console.log('scroll is greater than nav');
            
            $(".at_responsive_hero").css({
                "margin-top" : nav.height() + "px",
            });

        } else {

            // nav.removeClass( 'fixed' );
            console.log('scroll is less than nav');
            $(".at_responsive_hero").css({
                "margin-top" : 0 + "px",
            });

        }


    });

} );
