module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    concat: {
      options: {
        separator: ';'
      },
      dest: {
        src: [ 
          'ReduxCore/assets/js/vendor/cookie.js',
          'ReduxCore/assets/js/vendor/jquery.DragSort.min.js',
          'ReduxCore/assets/js/vendor/jquery.numeric.min.js',
          'ReduxCore/assets/js/vendor/jquery.tipsy.js',
          'ReduxCore/assets/js/vendor/jquery.typewatch.min.js',
          'ReduxCore/assets/js/vendor/spinner_custom.js',
          'ReduxCore/inc/fields/**/*.js', 
          'ReduxCore/assets/js/admin.js', 
        ],
        dest: 'ReduxCore/assets/js/redux.min.js'
      }
    },
    'gh-pages': {
      options: {
        base: 'ReduxCore/',
        message: 'Update docs and files to distribute'
      },
      dev: {
        src: ['**/*']
      },
      travis: {
        options: {
          repo: 'https://' + process.env.GH_TOKEN + '@github.com/ReduxFramework/ReduxFramework.git',
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
      options: {
        banner: '/*! <%= pkg.name %> - v<%= pkg.version %> - ' +
        '<%= grunt.template.today("yyyy-mm-dd") %> */\n',
      },
      redux: {
        files: {
          'ReduxCore/assets/js/redux.min.js': ['ReduxCore/assets/js/redux.min.js']
        }
      }
    },    
    qunit: {
      files: ['test/qunit/**/*.html']
    },
    jshint: {
      files: [ 
      //* // for testing individually
        'ReduxCore/inc/fields/ace_editor/*.js',
        'ReduxCore/inc/fields/border/*.js',
        'ReduxCore/inc/fields/button_set/*.js',
        'ReduxCore/inc/fields/checkbox/*.js',
        'ReduxCore/inc/fields/color/*.js',
        'ReduxCore/inc/fields/color_gradient/*.js',
        'ReduxCore/inc/fields/date/*.js',
        'ReduxCore/inc/fields/dimensions/*.js',
        'ReduxCore/inc/fields/divide/*.js',
        'ReduxCore/inc/fields/editor/*.js',
        'ReduxCore/inc/fields/gallery/*.js',
        'ReduxCore/inc/fields/group/*.js',
        'ReduxCore/inc/fields/image_select/*.js',
        'ReduxCore/inc/fields/info/*.js',
        'ReduxCore/inc/fields/link_color/*.js',
        'ReduxCore/inc/fields/media/*.js',
        'ReduxCore/inc/fields/multi_text/*.js',
        'ReduxCore/inc/fields/password/*.js',
        'ReduxCore/inc/fields/radio/*.js',
        'ReduxCore/inc/fields/raw/*.js',
        'ReduxCore/inc/fields/raw_align/*.js',
        'ReduxCore/inc/fields/select/*.js',
        'ReduxCore/inc/fields/slider/*.js',
        'ReduxCore/inc/fields/slides/*.js',
        'ReduxCore/inc/fields/sortable/*.js',
        'ReduxCore/inc/fields/sorter/*.js',
        'ReduxCore/inc/fields/spacing/*.js',
        'ReduxCore/inc/fields/spinner/*.js',
        'ReduxCore/inc/fields/switch/*.js',
        'ReduxCore/inc/fields/text/*.js',
        'ReduxCore/inc/fields/textarea/*.js',
        'ReduxCore/inc/fields/typography/*.js',
      //*/
        'ReduxCore/assets/js/admin.js',
        //'ReduxCore/inc/fields/**/*.js',
      ],
      options: {
        expr: true,
        // options here to override JSHint defaults
        globals: {
          jQuery: true,
          console: true,
          redux_change: true,
          module: true,
          document: true,
        }
      }
    },
    watch: {
      files: ['<%= jshintFields.files %>'],
      tasks: ['jshint']
    },
  });

  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-qunit');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-gh-pages');

  // Default task(s).
  grunt.registerTask('default', ['jshint', 'concat']);

  grunt.registerTask('travis', ['jshint']);

  // this would be run by typing "grunt test" on the command line
  grunt.registerTask('test', ['jshint', 'qunit']);  

};