var gulp = require('gulp');
var sass = require('gulp-sass');
var pug = require('gulp-pug');
var rename = require('gulp-rename');
var autoprefixer = require('gulp-autoprefixer');
var plumber = require('gulp-plumber');
var browserSync = require('browser-sync');

gulp.task('sass', function() {
  var option = {
    outputStyle: 'expanded',
  };

  return gulp.src('./css/scss/**/*.scss')
  .pipe(plumber())
  .pipe(sass(option))
  .pipe(autoprefixer())
  .pipe(gulp.dest('./css/'))
  .pipe(browserSync.stream());
});

gulp.task('pug', function() {
  var option = {
    pretty: true,
  };

  return gulp.src(['./pug/**/*.pug'])
  .pipe(plumber())
  .pipe(pug(option))
  .pipe(rename({
    extname: '.php'
  }))
  .pipe(gulp.dest('./'));
});

gulp.task('browser-sync', function () {
    browserSync.init({
        // vccwで設定したipアドレス
        proxy: "http://dou.wp/",
        open: true,
        watchOptions: {
            debounceDelay: 1000
        }
    });
});

gulp.task('reload', () => {
  browserSync.reload();
});

gulp.task('watch', function() {
  gulp.watch('./css/scss/**/*.scss', gulp.series('sass'));
  gulp.watch('./pug/**/*.pug', gulp.series('pug'));
});

gulp.task('default', gulp.parallel('browser-sync', 'sass', 'pug', 'watch'));



/*
gulp.task('convertToCss', function() {
  gulp.src('./css/scss/style.scss')
      .pipe(plumber())
      .pipe(sass({
        outputStyle: 'expanded'
      }))
      .pipe(autoprefixer())
      .pipe(gulp.dest('./css'));
});
*/

//gulp.task('default', function() {
//    gulp.watch('./css/scss/**/*.scss',gulp.series('convertToCss'));
//});

