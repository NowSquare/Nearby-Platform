var gulp = require('gulp'),
    sass = require('gulp-ruby-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    cssnano = require('gulp-cssnano'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat'),
    notify = require('gulp-notify'),
    cache = require('gulp-cache'),
    livereload = require('gulp-livereload'),
    streamqueue = require('streamqueue'),
    del = require('del'),
    orderedMergeStream = require('ordered-merge-stream');

/*
 |--------------------------------------------------------------------------
 | Default task
 |--------------------------------------------------------------------------
 */

gulp.task('default', ['clean'], function() {
    gulp.start('styles', 'scripts', 'clean');
});

/*
 |--------------------------------------------------------------------------
 | Watch
 |--------------------------------------------------------------------------
 */

gulp.task('watch', function() {

  // Create LiveReload server
  livereload.listen();

  // Watch .scss files
  gulp.watch('resources/sass/**/*.scss', ['styles']);

  // Watch .js files
  gulp.watch('resources/scripts/**/*.js', ['scripts']);
});

gulp.task('watch_styles', function() {

  // Create LiveReload server
  livereload.listen();

  // Watch .scss files
  gulp.watch('resources/sass/**/*.scss', ['styles']);

});

gulp.task('watch_scripts', function() {

  // Create LiveReload server
  livereload.listen();

  // Watch .js files
  gulp.watch('resources/scripts/**/*.js', ['scripts']);

});

/*
 |--------------------------------------------------------------------------
 | Styles
 |--------------------------------------------------------------------------
 */

gulp.task('styles', function() {
  var sassStream = sass([
    'resources/sass/style.scss', 
    'resources/sass/prettyprint.scss',
    'bower_components/tether/src/css/tether.sass',
    'bower_components/owl.carousel/src/scss/owl.carousel.theme.scss',
    'bower_components/owl.carousel/src/scss/owl.carousel.scss',
    'bower_components/ladda/css/ladda.scss'
  ], {
    style: 'expanded',
    loadPath: [ 
      'resources/sass',
      'bower_components/bootstrap/scss',
      'bower_components/owl.carousel/src/scss'
    ]
  });

  var cssStream = gulp.src([
      'bower_components/fullcalendar/dist/fullcalendar.css',
      //'bower_components/sweetalert2/dist/sweetalert2.css',
      'bower_components/select2/dist/css/select2.css'
    ])
    .pipe(concat('css-files.css'));

  //var mergedStream = orderedMergeStream([cssStream, sassStream]);
  var mergedStream = orderedMergeStream([cssStream, sassStream]);

  mergedStream.pipe(concat('style.css'))
    .pipe(autoprefixer({
      browsers: ['last 2 version'], 
      cascade: false
    }))
    .pipe(gulp.dest('css'))
    .pipe(rename({suffix: '.min'}))
    .pipe(cssnano({
      discardComments: {removeAll: true}
    }))
    .pipe(gulp.dest('css'))
    .pipe(livereload())
    .pipe(notify({ message: 'Styles task complete' }));

  return mergedStream;
});

/*
 |--------------------------------------------------------------------------
 | Scripts
 |--------------------------------------------------------------------------
 */

gulp.task('scripts', function() {
return streamqueue({ objectMode: true },
        gulp.src('bower_components/jquery/dist/jquery.js'),
        gulp.src('bower_components/tether/dist/js/tether.js'),
        gulp.src('bower_components/moment/moment.js'),
        gulp.src('bower_components/owl.carousel/dist/owl.carousel.js'),
        gulp.src('bower_components/fullcalendar/dist/fullcalendar.js'),
        gulp.src('bower_components/jquery_lazyload/jquery.lazyload.js'),
        gulp.src('bower_components/bluebird/js/browser/bluebird.js'),
        gulp.src('bower_components/blockUI/jquery.blockUI.js'),
        gulp.src('bower_components/jquery.scrollTo/jquery.scrollTo.js'),
        gulp.src('bower_components/jquery-throttle-debounce/jquery.ba-throttle-debounce.js'),
        gulp.src('bower_components/ladda/js/spin.js'),
        gulp.src('bower_components/ladda/js/ladda.js'),
        gulp.src('bower_components/particles.js/particles.js'),
        gulp.src('bower_components/flat-surface-shader/deploy/fss.js'),
        gulp.src('bower_components/ladda/js/ladda.jquery.js'),
        gulp.src('bower_components/jquery-form/src/jquery.form.js'),
        gulp.src('bower_components/sweetalert2/dist/sweetalert2.js'),
        gulp.src('bower_components/bootstrap-validator/dist/validator.js'),
        gulp.src('bower_components/google-code-prettify/src/prettify.js'),
        gulp.src('bower_components/H5F/src/H5F.js'),
        gulp.src('bower_components/notifyjs/dist/notify.js'),
        gulp.src('bower_components/peity/jquery.peity.min.js'),
        gulp.src('bower_components/select2/dist/js/select2.full.js'),
        gulp.src('resources/scripts/**/*.js')
    )
    .pipe(concat('scripts.js'))
    .pipe(gulp.dest('js'))
    .pipe(rename({suffix: '.min'}))
    .pipe(uglify({
      mangle: true
    }))
    .pipe(gulp.dest('js'))
    .pipe(livereload())
    .pipe(notify({ message: 'Scripts task complete' }));
});

/*
 |--------------------------------------------------------------------------
 | Cleanup
 |--------------------------------------------------------------------------
 */

gulp.task('clean', function() {
    return del([
      'css/*.css', '!css/*.min.css',
      'js/*.js', '!js/*.min.js'
    ], {
      force: true
    });
});