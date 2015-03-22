(function( $ ) {
    'use strict';

    $.redux_welcome = $.redux_welcome || {};

    $( document ).ready(
        function() {
            $.redux_welcome.initQtip();
        }
    );
    
    $.redux_welcome.initQtip = function() {
        if ( $().qtip ) {
            var shadow = 'qtip-shadow';
            var color = 'qtip-dark';
            var rounded = '';
            var style = ''; //qtip-bootstrap';

            var classes = shadow + ',' + color + ',' + rounded + ',' + style;
            classes = classes.replace( /,/g, ' ' );

            // Get position data
            var myPos = 'top center';
            var atPos = 'bottom center';

            // Tooltip trigger action
            var showEvent = 'click';
            var hideEvent = 'click mouseleave';

            // Tip show effect
            var tipShowEffect = 'slide';
            var tipShowDuration = '500';

            // Tip hide effect
            var tipHideEffect = 'slide';
            var tipHideDuration = '500';

            $( '.redux-hint-qtip' ).each(
                function() {
                    $( this ).qtip(
                        {
                            content: {
                                text: $( this ).attr( 'qtip-content' ),
                                title: $( this ).attr( 'qtip-title' )
                            },
                            show: {
                                effect: function() {
                                    switch ( tipShowEffect ) {
                                        case 'slide':
                                            $( this ).slideDown( tipShowDuration );
                                            break;
                                        case 'fade':
                                            $( this ).fadeIn( tipShowDuration );
                                            break;
                                        default:
                                            $( this ).show();
                                            break;
                                    }
                                },
                                event: showEvent,
                            },
                            hide: {
                                effect: function() {
                                    switch ( tipHideEffect ) {
                                        case 'slide':
                                            $( this ).slideUp( tipHideDuration );
                                            break;
                                        case 'fade':
                                            $( this ).fadeOut( tipHideDuration );
                                            break;
                                        default:
                                            $( this ).show( tipHideDuration );
                                            break;
                                    }
                                },
                                event: hideEvent,
                            },
                            style: {
                                classes: classes,
                            },
                            position: {
                                my: myPos,
                                at: atPos,
                            },
                        }
                    );
                }
            );
        }
    };
})( jQuery );