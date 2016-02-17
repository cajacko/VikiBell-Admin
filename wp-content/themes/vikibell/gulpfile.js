var gulp = require('gulp');
var sass = require('gulp-sass');
var minifyCss = require('gulp-minify-css');
var sourcemaps = require('gulp-sourcemaps');
var concat = require('gulp-concat');

var sassInput = './css/*.scss';

gulp.task('sass', function () {
	gulp.src('./css/pdf.scss')
		.pipe(sass().on('error', sass.logError))
		.pipe(gulp.dest('./css/'));
});


gulp.task( 'watch', function () {
	gulp.watch([sassInput], ['sass'] );
});

gulp.task('default', ['watch']);
