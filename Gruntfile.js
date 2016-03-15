'use strict';
module.exports = function(grunt) {
	
  // Load all tasks
  require('load-grunt-tasks')(grunt);
  
  // Show elapsed time
  require('time-grunt')(grunt);

  var jsFileList = [
    'js/axiomz.js'
  ];

  grunt.initConfig({
	pkg: 
		grunt.file.readJSON('package.json'),
    jshint: {
      options: {
        jshintrc: '.jshintrc'
      },
      all: [
        'Gruntfile.js',
        'js/*.js',
        '!js/_*.js',
        '!**/*.min.*'
      ]
    },
    
	//the task that compiles less
    less: {
      dev: {
        files: {
          'css/axiomz.css': [
            'less/axiomz.less'
          ]
        },
        options: {
          compress: false
		}
      },
      build: {
        files: {
          'css/axiomz.min.css': [
            'less/axiomz.less'
          ]
        },
        options: {
          compress: true
        }
      }
    },
    
	//the task that concatenates lines and removes comments in javascript
    concat: {
      options: {
        separator: ';',
		stripBanners: true,
		banner: '/*! <%= pkg.name %> - v<%= pkg.version %>' + 
			' - by:<%= pkg.version %>' + 
			' - license:<%= pkg.licenses.type %>' +
			' - <%= grunt.template.today("yyyy-mm-dd") %> */',
      },
      dist: {
        src: [jsFileList],
        dest: 'js/_axiomz.js',
      },
    },
    
	//the task that gives js variables short names
    uglify: {
      dist: {
        files: {
          'js/axiomz.min.js': [jsFileList]
        }
      }
    },
	
	// task to postprocess css to add browser specific prefixes, like "-webkit-..." and minify css
    postcss:{  
		options: {
			map: true,
			processors: [
				require('pixrem')(), // add fallbacks for rem units 
				require('autoprefixer')({browsers: ['last 2 versions', 'ie 8', 'ie 9', 'android 2.3', 'android 4', 'opera 12']}),
				require('cssnano')() // minify the result 
			]
		},
		dev: {
			options: {
				  map: {
					prev: 'css/'
				  }
			},
			src: 'css/axiomz.css'
		},
		build: {
			src: 'css/axiomz.min.css'
		}
	},


    watch: {
      less: {
        files: [
          'less/axiomz.less'
        ],
        tasks: ['less:dev', 'postcss:dev']
      },
      js: {
        files: [
          jsFileList,
          '<%= jshint.all %>'
        ],
        tasks: ['jshint', 'concat']
      },
    }
  });

  // Register tasks
  grunt.registerTask('default', [
    'dev'
  ]);
  grunt.registerTask('dev', [
    'jshint',
    'less:dev',
    'postcss:dev',
    'concat'
  ]);
  grunt.registerTask('build', [
    'jshint',
    'less:build',
    'postcss:build',
    'uglify'
  ]);
};
