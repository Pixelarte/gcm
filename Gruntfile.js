module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    
 
    concat: {
      dist: {
         src: ["src/js/*.js"],
        dest: "assets/js/libs/libs.js"
      }
    },

    uglify: {
          options: {
            beautify: true,
            /*mangle: {
                toplevel: true
            }*/
          },
          my_target: {
            files: {
              'assets/js/main.min.js': ['src/js/main.js'],
              'serviceWorker/sw.min.js': ['src/serviceWorker/sw.js']
            }
          }
    },
    watch: {
      options: {
        livereload: true
      },
      scripts: {
        files: ['src/js/*.js'],
        tasks: ['newer:uglify'],
        options: {
            spawn: false
        }
      }
    },
  });

  grunt.loadNpmTasks("grunt-contrib-concat");
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-newer');

  grunt.registerTask('default', ['newer:uglify','watch']);
  grunt.registerTask('produccion',['concat','uglify']);

};