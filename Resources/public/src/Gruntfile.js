module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    watch: {
      scripts: {
        files: ['less/**/*.less'],
        tasks: ['less'],
        options: {
          spawn: false,
        },
      },
    },    
    less: {
      development: {
        options: {
            paths: ['less/'],
          compress: false,
          yuicompress: true,
          syncImport: true,
          strictImports: true          
        },
        files: {
          "../css/admin.css": ["less/mixins.less","less/admin.less"] ,
          "../css/survey.css" : ["less/mixins.less","less/survey.less"]
          
        }
      }
    }
  });


  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-less');

  // Default task(s).
  grunt.registerTask('default', ['less']);

};