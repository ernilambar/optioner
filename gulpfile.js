// Config.
const rootPath = './';

// Gulp.
const gulp = require( 'gulp' );

// Plumber.
const plumber = require('gulp-plumber');

// Autoprefixer.
const autoprefixer = require('gulp-autoprefixer');

// Sass.
const sass = require('gulp-sass')(require('sass'));

// Babel.
const babel = require('gulp-babel');

// SASS.
gulp.task('scss', function () {
	return gulp.src([rootPath + 'resources/sass/*.scss'])
		.pipe(sass().on('error', sass.logError))
		.pipe(plumber())
		.pipe(sass())
		.pipe(autoprefixer('last 4 version'))
		.pipe(gulp.dest(rootPath + 'assets/css'))
});

// Scripts.
gulp.task('js', function() {
  return gulp.src( [rootPath + 'resources/scripts/*.js'] )
	  .pipe(babel({
          presets: ['@babel/env']
        }))
	  .pipe(gulp.dest('assets/js'))
});

// Watch.
gulp.task( 'watch', function() {
    // Watch SCSS files.
    gulp.watch( rootPath + 'resources/sass/**/*.scss', gulp.series( 'scss' ) );

    // Watch JS files.
    gulp.watch( rootPath + 'resources/scripts/**/*.js', gulp.series( 'js' )  );
});

gulp.task( 'default', gulp.series('watch'));
gulp.task( 'build', gulp.series('scss', 'js'));
