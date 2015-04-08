/*global redux_change, redux*/

(function( $ ) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.import_export = redux.field_objects.import_export || {};

    $( document ).ready(
        function() {
            redux.field_objects.import_export.init();
        }
    );

    redux.field_objects.import_export.init = function( selector ) {

        if ( !selector ) {
            selector = $( document ).find( '.redux-container-import_export' );
        }

        var parent = selector;

        if ( !selector.hasClass( 'redux-field-container' ) ) {
            parent = selector.parents( '.redux-field-container:first' );
        }

        if ( parent.hasClass( 'redux-field-init' ) ) {
            parent.removeClass( 'redux-field-init' );
        } else {
            return;
        }

        $( '#redux-import' ).click(
            function( e ) {
                if ( $( '#import-code-value' ).val() === "" && $( '#import-link-value' ).val() === "" ) {
                    e.preventDefault();
                    return false;
                }
                window.onbeforeunload = null;
                redux.args.ajax_save = false;
            }
        );

        $( '#redux-import-code-button' ).click(
            function() {
                var $el = $( '#redux-import-code-wrapper' );
                if ( $( '#redux-import-link-wrapper' ).is( ':visible' ) ) {
                    $( '#import-link-value' ).text( '' );
                    $( '#redux-import-link-wrapper' ).slideUp(
                        'fast', function() {
                            $el.slideDown(
                                'fast', function() {
                                    $( '#import-code-value' ).focus();
                                }
                            );
                        }
                    );
                } else {
                    if ( $el.is( ':visible' ) ) {
                        $el.slideUp();
                    } else {
                        $el.slideDown(
                            'medium', function() {
                                $( '#import-code-value' ).focus();
                            }
                        );
                    }
                }
            }
        );

        $( '#redux-import-link-button' ).click(
            function() {
                var $el = $( '#redux-import-link-wrapper' );
                if ( $( '#redux-import-code-wrapper' ).is( ':visible' ) ) {
                    $( '#import-code-value' ).text( '' );
                    $( '#redux-import-code-wrapper' ).slideUp(
                        'fast', function() {
                            $el.slideDown(
                                'fast', function() {
                                    $( '#import-link-value' ).focus();
                                }
                            );
                        }
                    );
                } else {
                    if ( $el.is( ':visible' ) ) {
                        $el.slideUp();
                    } else {
                        $el.slideDown(
                            'medium', function() {
                                $( '#import-link-value' ).focus();
                            }
                        );
                    }
                }
            }
        );

        $( '#redux-export-code-copy' ).click(
            function() {
                var $el = $( '#redux-export-code' );
                if ( $( '#redux-export-link-value' ).is( ':visible' ) ) {
                    $( '#redux-export-link-value' ).slideUp(
                        'fast', function() {
                            $el.slideDown(
                                'medium', function() {
                                    var options = redux.options;
                                    options['redux-backup'] = 1;
                                    $( this ).text( JSON.stringify( options ) ).focus().select();
                                }
                            );
                        }
                    );
                } else {
                    if ( $el.is( ':visible' ) ) {
                        $el.slideUp().text( '' );
                    } else {
                        $el.slideDown(
                            'medium', function() {
                                var options = redux.options;
                                options['redux-backup'] = 1;
                                $( this ).text( JSON.stringify( options ) ).focus().select();
                            }
                        );
                    }
                }
            }
        );

        $( '.redux-container-import_export textarea' ).focusout(
            function() {
                var $id = $( this ).attr( 'id' );
                var $el = $( this );
                var $container = $el;
                if ( $id == "import-link-value" || $id == "import-code-value" ) {
                    $container = $( this ).parent();
                }
                $container.slideUp(
                    'medium', function() {
                        if ( $id != "redux-export-link-value" ) {
                            $el.text( '' );
                        }
                    }
                );
            }
        );


        $( '#redux-export-link' ).click(
            function() {
                var $el = $( '#redux-export-link-value' );
                if ( $( '#redux-export-code' ).is( ':visible' ) ) {
                    $( '#redux-export-code' ).slideUp(
                        'fast', function() {
                            $el.slideDown().focus().select();
                        }
                    );
                } else {
                    if ( $el.is( ':visible' ) ) {
                        $el.slideUp();
                    } else {
                        $el.slideDown(
                            'medium', function() {
                                $( this ).focus().select();
                            }
                        );
                    }

                }
            }
        );

        var textBox1 = document.getElementById( "redux-export-code" );
        textBox1.onfocus = function() {
            textBox1.select();
            // Work around Chrome's little problem
            textBox1.onmouseup = function() {
                // Prevent further mouseup intervention
                textBox1.onmouseup = null;
                return false;
            };
        };
        var textBox2 = document.getElementById( "import-code-value" );
        textBox2.onfocus = function() {
            textBox2.select();
            // Work around Chrome's little problem
            textBox2.onmouseup = function() {
                // Prevent further mouseup intervention
                textBox2.onmouseup = null;
                return false;
            };
        };
    };
})( jQuery );