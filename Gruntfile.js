// Generated on 2014-03-10 using generator-angular 0.7.1
'use strict';

// # Globbing
// for performance reasons we're only matching one level down:
// 'test/spec/{,*/}*.js'
// use this if you want to recursively match all subfolders:
// 'test/spec/**/*.js'

module.exports = function (grunt) {

  // Load grunt tasks automatically
  require('load-grunt-tasks')(grunt);

  // Time how long tasks take. Can help when optimizing build times
  require('time-grunt')(grunt);

  // Define the configuration for all the tasks
  grunt.initConfig({

    // Project settings
    brownPaperTickets: {
      // configurable paths
      project: '',
      dist: 'dist'
    },

    // Watches files for changes and runs tasks based on the changed files
    watch: {
      js: {
        files: ['*.js'],
        tasks: ['newer:jshint:all']
      },
      // // jsTest: {
      // //   files: ['test/spec/{,*/}*.js'],
      // //   tasks: ['newer:jshint:test', 'karma']
      // // },
      // compass: {
      //   files: ['<%= brownPaperTickets.project %>/styles/{,*/}*.{scss,sass}'],
      //   tasks: ['compass:server', 'autoprefixer']
      // },
      gruntfile: {
        files: ['Gruntfile.js']
      },
      // livereload: {
      //   options: {
      //     livereload: '<%= connect.options.livereload %>'
      //   },
      //   files: [
      //     '<%= brownPaperTickets.project %>/{,*/}*.html',
      //     '.tmp/styles/{,*/}*.css',
      //     '<%= brownPaperTickets.project %>/images/{,*/}*.{png,jpg,jpeg,gif,webp,svg}'
      //   ]
      // }
    },
    // Make sure code styles are up to par and there are no obvious mistakes
    jshint: {
      options: {
        jshintrc: '.jshintrc',
        reporter: require('jshint-stylish')
      },
      all: [
        'Gruntfile.js',
        'assets/js/*.js',
        'admin/assets/js/*.js'
      ]
    },

    // Empties folders to start fresh
    clean: {
      dist: {
        files: [{
          dot: true,
          src: [
            '.tmp',
            '<%= brownPaperTickets.dist %>/*',
            '!<%= brownPaperTickets.dist %>/.git*'
          ]
        }]
      },
      server: '.tmp'
    }

  });

  grunt.registerTask('hint', [
    'jshint'
  ]);

  grunt.registerTask('build', [
    'clean:dist',
    'autoprefixer',
    'copy:dist',
    'cdnify',
    'cssmin',
    'uglify',
  ]);

  grunt.registerTask('default', [
    'jshint',
  ]);
};
