module.exports = function(grunt) {

  grunt.initConfig({
    concat: {
      dist: {
        src: ['bower_components/jquery/dist/jquery.js', 'src/js/lightbox.js'],
        dest: 'dist/js/jquery.lightbox.js',
      },
    },
    connect: {
      server: {
        options: {
          port: 8000
        }
      }
    },
    copy: {
      dist: {
        files: [
          {
            expand: true,
            cwd: 'src/',
            src: ['**'],
            dest: 'dist/'
          }
        ],
      },
    },
    jshint: {
      all: [
        'src/js/lightbox.js'
      ],
      options: {
        jshintrc: true
      }
    },
    jscs: {
      src: [
        'src/js/lightbox.js'
      ],
      options: {
        config: ".jscsrc"
      }
    },
    uglify: {
      options: {
        preserveComments: 'some',
        sourceMap: true
      },
      dist: {
        files: {
          'dist/js/lightbox.min.js': ['src/js/lightbox.js'],
          'dist/js/jquery.lightbox.min.js': ['dist/js/jquery.lightbox.js']
        }
      }
    },   
    watch: {
      jshint: {
        files: ['src/js/lightbox.js'],
        tasks: ['jshint', 'jscs']
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-connect');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks("grunt-jscs");

  grunt.registerTask('default', ['connect', 'watch']);
  grunt.registerTask('test', ['jshint', 'jscs']);
  grunt.registerTask('build', ['jshint', 'jscs', 'copy:dist', 'concat', 'uglify']);
};