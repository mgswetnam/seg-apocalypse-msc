var gulp = require( 'gulp' ),
  modernizr = require('gulp-modernizr'),
  flog = require('fancy-log'),
  pump = require( 'pump' ),
  babelify = require( 'babelify' ),
  browserify = require( 'browserify' ),
  sass = require('gulp-sass'),
  $ = require( 'gulp-load-plugins' )(),
  browserSync = require( 'browser-sync' ).create();

var AUTOPREFIXER_BROWSERS = [
  "Android >= 4",
  "last 5 Chrome versions",
  "last 5 Firefox versions",
  "Explorer >= 11",
  "iOS >= 6",
  "last 5 Opera versions",
  "last 5 Safari versions",
  "last 5 Edge versions",
  "> 1%"
];

gulp.task( 'devImages', function( callback ) {
  pump(
    [
      gulp.src(['assets/src/images/**/*.{png,PNG,jpg,JPG,jpeg,JPEG,gif,GIF}'], {
        cwd: '.'
      }),
      $.imagemin(),
      gulp.dest('assets/dist/images'),
      browserSync.stream()
    ],
    callback()
  );
} );

gulp.task( 'webpImages', function( callback ) {
  pump(
    [
      gulp.src(['assets/dist/images/**/*.{png,PNG,jpg,JPG,jpeg,JPEG,gif,GIF}'], {
        cwd: '.'
      }),
      $.webp(),
      gulp.dest('assets/dist/images'),
      browserSync.stream()
    ],
    callback()
  );
} );

gulp.task( 'svgFiles', function( callback ) {
  pump(
    [
      gulp.src( [ 'assets/src/images/**/*.svg' ], {
        cwd: '.'
      } ),
      gulp.dest( 'assets/dist/images' ),
      browserSync.stream()
    ],
    callback()
  );
} );

gulp.task( 'fonts', function( callback ) {
  pump(
    [
      gulp.src( ['assets/src/fonts/**/*'], {
        cwd: '.'
      } ),
      gulp.dest( 'assets/dist/fonts' ),
      browserSync.stream()
    ],
    callback()
  );
} );

gulp.task( 'faFonts', function( callback ) {
  pump(
    [
      gulp.src( ['././node_modules/@fortawesome/fontawesome-free/webfonts/**/*'], {
        cwd: '.'
      } ),
      gulp.dest( 'assets/dist/css/vendor/fontawesome/webfonts' ),
      browserSync.stream()
    ],
    callback()
  );
} );

gulp.task( 'modernizr', function( callback ) {
  pump(
    [
      gulp.src( [
        './assets/src/js/*.js'
      ] ),
      $.modernizr(),
      gulp.dest( './assets/src/js/vendor/' )
    ],
    callback()
  );
});

gulp.task( 'js', function( callback ) {
  pump(
    [
      gulp.src( [
        './assets/src/js/main.js'
      ] ),
      $.tap( function ( file ) {
        flog( 'bundling ' + file.path );
        // replace file contents with browserify's bundle stream
        file.contents = browserify( file.path )
          .transform( 'babelify', {
            presets: [
              [ "@babel/env",  {
                "targets": {
                  "browsers": AUTOPREFIXER_BROWSERS
                }
              } ]
            ]
          } )
          .bundle();
      }),
      $.buffer(),
      $.sourcemaps.init(),
      $.uglify( false ),
      $.rename( {
        extname: '.min.js'
      } ),
      $.sourcemaps.write( '.' ),
      gulp.dest( './assets/dist/js' ),
      browserSync.stream()
    ],
    callback()
  );
});

gulp.task( 'jsModernizr', function( callback ) {
  pump(
    [
      gulp.src( [
        './assets/src/js/vendor/modernizr.js'
      ] ),
      $.sourcemaps.init(),
      $.uglify( false ),
      $.rename( {
        extname: '.min.js'
      } ),
      $.sourcemaps.write( '.' ),
      gulp.dest( './assets/dist/js/vendor' ),
      browserSync.stream()
    ],
    callback()
  );
} );

gulp.task( 'jsAdmin', function( callback ) {
  pump(
    [
      gulp.src( [
        './admin/assets/src/js/admin.js'
      ] ),
      $.tap( function ( file ) {
        flog( 'bundling ' + file.path );
        // replace file contents with browserify's bundle stream
        file.contents = browserify( file.path )
          .transform( 'babelify', {
            presets: [
              [ "@babel/env",  {
                "targets": {
                  "browsers": AUTOPREFIXER_BROWSERS
                }
              } ]
            ]
          } )
          .bundle();
      }),
      $.buffer(),
      $.sourcemaps.init(),
      $.uglify( false ),
      $.rename( {
        extname: '.min.js'
      } ),
      $.sourcemaps.write( '.' ),
      gulp.dest( './admin/assets/dist/js' ),
      browserSync.stream()
    ],
    callback()
  );
});

gulp.task( 'jsCustomizer', function( callback ) {
  pump(
    [
      gulp.src( [
        './admin/assets/src/js/customizer.js'
      ] ),
      $.sourcemaps.init(),
      $.uglify( false ),
      $.rename( {
        extname: '.min.js'
      } ),
      $.sourcemaps.write( '.' ),
      gulp.dest( './admin/assets/dist/js' ),
      browserSync.stream()
    ],
    callback()
  );
} );

gulp.task( 'css', function( callback ) {
  pump(
    [
      gulp.src( [
        'assets/src/scss/main.scss'
      ] ),
      $.sourcemaps.init(),
      sass( {
        outputStyle: 'compressed'
      } ).on( 'error', sass.logError ),
      $.autoprefixer(),
      $.rename( {
        extname: '.min.css'
      } ),
      $.sourcemaps.write( '.' ),
      gulp.dest( 'assets/dist/css' ),
      browserSync.stream()
    ],
    callback()
  );
} );

gulp.task( 'cssAdmin', function( callback ) {
  pump(
    [
      gulp.src( [
        'admin/assets/src/scss/admin.scss'
      ] ),
      $.sourcemaps.init(),
      sass( {
        outputStyle: 'compressed'
      } ).on( 'error', sass.logError ),
      $.autoprefixer(),
      $.rename( {
        extname: '.min.css'
      } ),
      $.sourcemaps.write( '.' ),
      gulp.dest( 'admin/assets/dist/css' ),
      browserSync.stream()
    ],
    callback()
  );
});

gulp.task( 'cssBootstrap', function( callback ) {
  pump(
    [
      gulp.src( [
        '././node_modules/bootstrap/scss/bootstrap-grid.scss'
      ] ),
      $.sourcemaps.init(),
      sass( {
        outputStyle: 'compressed'
      } ).on( 'error', sass.logError ),
      $.autoprefixer(),
      $.rename( {
        basename: 'bootstrap',
        extname: '.min.css'
      } ),
      $.sourcemaps.write( '.' ),
      gulp.dest( 'assets/dist/css/vendor/bootstrap' ),
      browserSync.stream()
    ],
    callback()
  );
});

gulp.task( 'cssFontawesome', function( callback ) {
  pump(
    [
      gulp.src( [
        '././node_modules/@fortawesome/fontawesome-free/css/all.css',
      ] ),
      $.sourcemaps.init(),
      sass( {
        outputStyle: 'compressed'
      } ).on( 'error', sass.logError ),
      $.autoprefixer(),
      $.rename( {
        basename: 'fontawesome',
        extname: '.min.css'
      } ),
      $.sourcemaps.write( '.' ),
      gulp.dest( 'assets/dist/css/vendor/fontawesome/css' ),
      browserSync.stream()
    ],
    callback()
  );
});

gulp.task( 'browserSync', function() {
  browserSync.init( {
    proxy: 'http://cs-csawp:8888/wp/', // change this to match your host
    watchTask: true,
    open: false
  } );
} );

gulp.task( 'watch', function() {
  gulp.watch( [ '*.html', '*.php', 'templates/**/*.twig', 'includes/*.php' ] ).on( 'change', browserSync.reload );
  gulp.watch( [ 'assets/src/images/**/*.{png,PNG,jpg,JPG,jpeg,JPEG,gif,GIF}', 'assets/src/images/**/*.svg' ], gulp.parallel( 'devImages','svgFiles' ) );
  gulp.watch( [ 'assets/src/fonts/**/*.{eot,otf,svg,ttf,woff,woff2}' ], gulp.series( 'fonts' ) );
  gulp.watch( [ 'assets/src/js/**/*.js', 'admin/assets/src/js/**/*.js', 'templates/**/*.js' ], gulp.parallel( 'js', 'jsAdmin', 'jsCustomizer' ) );
  gulp.watch( [ 'assets/src/scss/**/*.scss', 'admin/assets/src/scss/**/*.scss', 'templates/*.scss', 'templates/**/*.scss' ], gulp.parallel( 'css', 'cssAdmin' ) );
});

gulp.task( 'build', gulp.series( 'modernizr', 'devImages', gulp.parallel( 'webpImages', 'svgFiles', 'fonts', 'faFonts', 'js', 'jsModernizr', 'css', 'jsAdmin', 'jsCustomizer', 'cssAdmin', 'cssBootstrap', 'cssFontawesome' ) ) );
gulp.task( 'default', gulp.series( 'build', gulp.parallel( 'browserSync', 'watch' ) ) );
