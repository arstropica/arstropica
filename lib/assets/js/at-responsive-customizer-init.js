// ArsTropica  Responsive at-responsive-customizer-init-script

( function( $ ) {
    wp.customize(
    'at_responsive_reset_control',
    function( value ) {
        value.bind(
        function( to ) {
            jQuery.post( ajax_url, 
            { 
                action: 'at_theme_reset',
                reset_value: to
            },
            function( response ) {
                jQuery( '.at-reset-info' ).html( response );
            }
            );
        }
        );
    }
    );
} )( jQuery );
