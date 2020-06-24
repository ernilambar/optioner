// Config.
var rootPath = './';

// Variables.
var gulp = require( 'gulp' );
var sass = require( 'gulp-sass' );
var plumber = require( 'gulp-plumber' );
var autoprefixer = require( 'gulp-autoprefixer' );

// Error Handling.
var onError = function( err ) {
    console.log( 'An error occurred:', err.message );
    this.emit( 'end' );
};

gulp.task('scss', function () {
    return gulp.src(rootPath + 'resources/sass/style.scss')
        .on('error', sass.logError)
        .pipe(plumber())
        .pipe(sass())
        .pipe(autoprefixer('last 4 version'))
        .pipe(gulp.dest('assets/css'))
});

gulp.task('scripts', function() {
    return gulp.src( [rootPath + 'resources/scripts/script.js'] )
        .pipe(plumber())
        .pipe(gulp.dest('assets/js'))
});

// Tasks.
gulp.task( 'style', gulp.series('scss'));

gulp.task( 'build', gulp.series('style', 'scripts'));
