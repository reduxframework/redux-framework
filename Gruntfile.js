/* jshint node:true */
var shell = require( 'shelljs' );

module.exports = function( grunt ) {

    // Project configuration.
    grunt.initConfig(
        {
            pkg: grunt.file.readJSON( 'package.json' ),

            concat: {
                options: {
                    separator: ';'
                },
                core: {
                    src: [
                        'ReduxCore/assets/js/vendor/cookie.js',
                        'ReduxCore/assets/js/vendor/jquery.typewatch.js',
                        'ReduxCore/assets/js/vendor/jquery.serializeForm.js',
                        'ReduxCore/assets/js/vendor/jquery.alphanum.js',
                        'ReduxCore/assets/js/redux.js'
                    ],
                    dest: 'ReduxCore/assets/js/redux.min.js'
                },
                vendor: {
                    src: [
                        'ReduxCore/assets/js/vendor/cookie.js',
                        'ReduxCore/assets/js/vendor/jquery.serializeForm.js',
                        'ReduxCore/assets/js/vendor/jquery.typewatch.js',
                        'ReduxCore/assets/js/vendor/jquery.alphanum.js'
                    ],
                    dest: 'ReduxCore/assets/js/vendor.min.js'
                }
            },
            'gh-pages': {
                options: {
                    base: 'docs',
                    message: 'Update docs and files to distribute'
                },
                dev: {
                    src: ['docs/**/*', 'bin/CNAME']
                },
                travis: {
                    options: {
                        repo: 'https://' + process.env.GH_TOKEN + '@github.com/ReduxFramework/docs.reduxframework.com.git',
                        user: {
                            name: 'Travis',
                            email: 'travis@travis-ci.org'
                        },
                        silent: false
                    },
                    src: ['**/*']
                }
            },
            uglify: {
                fields: {
                    files: [
                        {
                            expand: true,
                            cwd: 'ReduxCore/inc/fields',
                            src: ['**/*.js', '!**/*.min.js', '!ace_editor/vendor/*.js', '!ace_editor/vendor/snippets/*.js', '!slider/vendor/nouislider/*.*', '!spinner/vendor/*.*'],
                            ext: '.min.js',
                            dest: 'ReduxCore/inc/fields'
                        }
                    ]
                },
                extensions: {
                    files: [
                        {
                            expand: true,
                            cwd: 'ReduxCore/inc/extensions',
                            src: ['**/*.js', '!**/*.min.js'],
                            ext: '.min.js',
                            dest: 'ReduxCore/inc/extensions'
                        }
                    ]
                },
                core: {
                    files: {
                        'ReduxCore/assets/js/redux.min.js': [
                            'ReduxCore/assets/js/redux.min.js'
                        ],
                        'ReduxCore/assets/js/vendor/spectrum/redux-spectrum.min.js': [
                            'ReduxCore/assets/js/vendor/spectrum/redux-spectrum.js'
                        ],
                        'ReduxCore/assets/js/vendor/redux.select2.sortable.min.js': [
                            'ReduxCore/assets/js/vendor/redux.select2.sortable.js'
                        ],
                        'ReduxCore/assets/js/media/media.min.js': [
                            'ReduxCore/assets/js/media/media.js'
                        ]
                    }

                },
                vendor: {
                    files: {
                        'ReduxCore/assets/js/vendor.min.js': [
                            'ReduxCore/assets/js/vendor.min.js'
                        ]
                    }
                }
            },
            qunit: {
                files: ['test/qunit/**/*.html']
            },

            // JavaScript linting with JSHint.
            jshint: {
                options: {
                    jshintrc: '.jshintrc'
                },
                files: [
                    //'Gruntfile.js',
                    //'ReduxCore/assecd ..
                    //ts/js/import_export/import_export.js',
                    'ReduxCore/assets/js/media/media.js',
                    'ReduxCore/inc/fields/ace_editor/field_ace_editor.js',
                    'ReduxCore/inc/fields/background/field_background.js',
                    'ReduxCore/inc/fields/border/field_border.js',
                    'ReduxCore/inc/fields/button_set/field_button_set.js',
                    'ReduxCore/inc/fields/checkbox/field_checkbox.js',
                    'ReduxCore/inc/fields/color/field_color.js',
                    'ReduxCore/inc/fields/color_rgba/field_color_rgba.js',
                    'ReduxCore/inc/fields/date/field_date.js',
                    'ReduxCore/inc/fields/dimensions/field_dimensions.js',
                    'ReduxCore/inc/fields/editor/field_editor.js',
                    'ReduxCore/inc/fields/gallery/field_gallery.js',
                    'ReduxCore/inc/fields/image_select/field_image_select.js',
                    'ReduxCore/inc/fields/multi_text/field_multitext.js',
                    'ReduxCore/inc/fields/palette/field_palette.js',
                    'ReduxCore/inc/fields/select/field_select.js',
                    'ReduxCore/inc/fields/select_image/field_select_image.js',
                    'ReduxCore/inc/fields/slider/field_slider.js',
                    'ReduxCore/inc/fields/slides/field_slides.js',
                    'ReduxCore/inc/fields/sortable/field_sortable.js',
                    'ReduxCore/inc/fields/sorter/field_sorter.js',
                    'ReduxCore/inc/fields/spacing/field_spacing.js',
                    'ReduxCore/inc/fields/spinner/field_spinner.js',
                    'ReduxCore/inc/fields/switch/field_switch.js',
                    'ReduxCore/inc/fields/typography/field_typography.js',
                    //'ReduxCore/inc/extensions/customizer/extension_customizer.js',
                    'ReduxCore/inc/extensions/import_export/import_export/field_import_export.js',
                    'ReduxCore/inc/extensions/options_object/options_object/field_options_object.js',
                    'ReduxCore/assets/js/redux.js'
                ]
            },

            // Watch changes for files.
            watch: {
                ui: {
                    files: ['<%= jshint.files %>'],
                    tasks: ['jshint']
                },
                php: {
                    files: ['ReduxCore/**/*.php'],
                    tasks: ['phplint:core']
                },
                css: {
                    files: ['ReduxCore/**/*.less'],
                    tasks: ['less:development']
                }
            },

            // Add textdomain.
            addtextdomain: {
                options: {
                    textdomain: 'redux-framework',    // Project text domain.
                    updateDomains: ['redux', 'redux-framework-demo', 'v']  // List of text domains to replace.
                },
                target: {
                    files: {
                        src: ['*.php', '**/*.php', '!node_modules/**', '!tests/**', '!sample/**']
                    }
                }
            },

            // Generate POT files.
            makepot: {
                redux: {
                    options: {
                        type: 'wp-plugin',
                        domainPath: 'ReduxCore/languages',
                        potFilename: 'redux-framework.pot',
                        include: [],
                        exclude: [
                            'sample/.*'
                        ],
                        potHeaders: {
                            poedit: true,
                            'report-msgid-bugs-to': 'https://github.com/ReduxFramework/ReduxFramework/issues',
                            'language-team': 'LANGUAGE <support@reduxframework.com>'
                        }
                    }
                }
            },

            // Check textdomain errors.
            checktextdomain: {
                options: {
                    keywords: [
                        '__:1,2d',
                        '_e:1,2d',
                        '_x:1,2c,3d',
                        'esc_html__:1,2d',
                        'esc_html_e:1,2d',
                        'esc_html_x:1,2c,3d',
                        'esc_attr__:1,2d',
                        'esc_attr_e:1,2d',
                        'esc_attr_x:1,2c,3d',
                        '_ex:1,2c,3d',
                        '_n:1,2,4d',
                        '_nx:1,2,4c,5d',
                        '_n_noop:1,2,3d',
                        '_nx_noop:1,2,3c,4d'
                    ]
                },
                redux: {
                    cwd: 'ReduxCore/',
                    options: {
                        text_domain: 'redux-framework',
                    },
                    src: ['**/*.php'],
                    expand: true
                },
                sample: {
                    cwd: 'sample',
                    options: {
                        text_domain: 'redux-framework-demo',
                    },
                    src: ['**/*.php'],
                    expand: true
                }
            },

            // Exec shell commands.
            shell: {
                options: {
                    stdout: true,
                    stderr: true
                },
                // Limited to Maintainers so
                // txpush: {
                //  command: 'tx push -s' // push the resources
                // },
                txpull: {
                    command: 'tx pull -a --minimum-perc=25' // pull the .po files
                }
            },

            // Generate MO files.
            potomo: {
                dist: {
                    options: {
                        poDel: true
                    },
                    files: [{
                        expand: true,
                        cwd: 'ReduxCore/languages/',
                        src: ['*.po'],
                        dest: 'ReduxCore/languages/',
                        ext: '.mo',
                        nonull: true
                    }]
                }
            },

            phpdocumentor: {
                options: {
                    directory: 'ReduxCore/',
                    target: 'docs/'
                },
                generate: {}
            },

            phplint: {
                options: {
                    swapPath: './'
                },
                core: ["ReduxCore/**/*.php"],
                plugin: ["class-redux-plugin.php", "index.php", "redux-framework.php"]
            },

            sass: {
                fields: {
                    options: {
                        sourcemap: 'none',
                        style: 'compressed',
                        noCache: true
                    },

                    files: [{
                        expand: true,                   // Enable dynamic expansion.
                        cwd: 'ReduxCore/inc/fields',    // Src matches are relative to this path.
                        src: ['**/*.scss'],             // Actual pattern(s) to match.
                        dest: 'ReduxCore/inc/fields',   // Destination path prefix.
                        ext: '.css'                     // Dest filepaths will have this extension.
                    }]
                },
                extensions: {
                    options: {
                        sourcemap: 'none',
                        style: 'compressed',
                        noCache: true,
                    },

                    files: [{
                        expand: true,                   // Enable dynamic expansion.
                        cwd: 'ReduxCore/inc/extensions',    // Src matches are relative to this path.
                        src: ['**/*.scss'],             // Actual pattern(s) to match.
                        dest: 'ReduxCore/inc/extensions',   // Destination path prefix.
                        ext: '.css'                     // Dest filepaths will have this extension.
                    }]
                },
                vendor: {
                    options: {
                        sourcemap: 'none',
                        style: 'compressed',
                        noCache: true
                    },

                    files: {
                        "ReduxCore/assets/css/vendor/jquery-ui-bootstrap/jquery-ui-1.10.0.custom.css": [
                            "ReduxCore/assets/css/vendor/jquery-ui-bootstrap/jquery-ui-1.10.0.custom.scss"
                        ],
                        "ReduxCore/assets/css/vendor/elusive-icons/elusive-icons.css": [
                            "ReduxCore/assets/css/vendor/elusive-icons/scss/elusive-icons.scss"
                        ],
                    }
                },

                admin: {
                    options: {
                        sourcemap: 'none',
                        style: 'compressed',
                        noCache: true
                    },

                    files: {
                        "ReduxCore/assets/css/color-picker/color-picker.css": [
                            "ReduxCore/assets/css/color-picker/color-picker.scss"
                        ],
                        "ReduxCore/assets/css/media/media.css": [
                            "ReduxCore/assets/css/media/media.scss"
                        ],
                        "ReduxCore/assets/css/redux-admin.css": [
                            "ReduxCore/assets/css/redux-admin.scss"
                        ],
                        "ReduxCore/assets/css/rtl.css": [
                            "ReduxCore/assets/css/rtl.scss"
                        ]
                    }
                },
                welcome: {
                    options: {
                        sourcemap: 'none',
                        style: 'compressed',
                        noCache: true
                    },

                    files: {
                        "ReduxCore/inc/welcome/css/redux-welcome.css": [
                            "ReduxCore/inc/welcome/css/redux-welcome.scss"
                        ]
                    }
                }
            },

            cssmin: {
                fields: {
                    files: {
                        'ReduxCore/assets/css/redux-fields.css': [
                            'ReduxCore/inc/fields/**/*.css',
                            "ReduxCore/assets/css/color-picker/color-picker.css",
                            "ReduxCore/assets/css/media/media.css"
                        ]
                    }
                },
            }
        }
    );

    // Load NPM tasks to be used here
    grunt.loadNpmTasks( 'grunt-shell' );
    grunt.loadNpmTasks( 'grunt-potomo' );
    grunt.loadNpmTasks( 'grunt-wp-i18n' );
    grunt.loadNpmTasks( 'grunt-checktextdomain' );
    grunt.loadNpmTasks( 'grunt-contrib-jshint' );
    grunt.loadNpmTasks( 'grunt-contrib-sass' );
    grunt.loadNpmTasks( 'grunt-contrib-uglify' );
    grunt.loadNpmTasks( 'grunt-contrib-watch' );
    grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
    grunt.loadNpmTasks( 'grunt-contrib-concat' );
    grunt.loadNpmTasks( 'grunt-phpdocumentor' );
    grunt.loadNpmTasks( 'grunt-gh-pages' );
    grunt.loadNpmTasks( "grunt-phplint" );
    //grunt.loadNpmTasks( 'grunt-recess' );

    grunt.registerTask(
        'langUpdate', [
            'addtextdomain',
            'makepot',
            'shell:txpull',
            'potomo'
        ]
    );

    // Default task(s).
    grunt.registerTask(
        'default', [
            'jshint',
            'concat:core',
            'uglify:core',
            'concat:vendor',
            'uglify:vendor',
            'uglify:fields',
            'uglify:extensions',
            "sass:admin",
            "sass:fields",
            "sass:extensions",
            "sass:vendor",
            'cssmin'
        ]
    );
    grunt.registerTask( 'travis', ['jshint', 'lintPHP'] );

    // this would be run by typing "grunt test" on the command line
    grunt.registerTask( 'testJS', ['jshint', 'concat:core', 'concat:vendor'] );

    grunt.registerTask( 'watchUI', ['watch:ui'] );
    grunt.registerTask( 'watchPHP', ['watch:php', 'phplint:core', 'phplint:plugin'] );

    grunt.registerTask( "lintPHP", ["phplint:plugin", "phplint:core"] );
    grunt.registerTask( "compileSCSS", ["sass:admin", "sass:fields", "sass:extensions", "sass:vendor", "sass:welcome"] );
    grunt.registerTask(
        'compileJS',
        ['jshint', 'concat:core', 'uglify:core', 'concat:vendor', 'uglify:vendor', 'uglify:fields', 'uglify:extensions']
    );
    grunt.registerTask( 'compileTestJS', ['jshint', 'concat:core', 'concat:vendor'] );
    grunt.registerTask( 'compileCSS', ['cssmin'] );
};
