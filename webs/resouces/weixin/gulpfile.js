// Load Gulp
var gulp    = require('gulp'),
    gutil   = require('gulp-util');
plugins = require('gulp-load-plugins')();

gulp.task('default', ['watch']);

gulp.task('compress-js', function () {
    return gulp.src([
            , 'bower_components/jquery/dist/*.min.js'
            , 'bower_components/angular/*.min.js'
            , 'bower_components/angular-resource/*.min.js'
            , 'bower_components/angular-route/*.min.js'
            , 'bower_components/angular-animate/*.min.js'
            , 'bower_components/AngularJS-Toaster/*.min.js'
            , 'bower_components/angular-file-upload/dist/*.min.js'
            , '!app/production.min.js'
            , 'app/app.js'
            , 'app/components/services/*.js'
            , 'app/components/directives/*.js'
            , 'app/components/controllers/*.js'])
        .pipe(plugins.jshint())
        .pipe(plugins.concat('production.min.js'))
        .pipe(gulp.dest('app'));
});

// Default task
gulp.task('watch', function() {
    gulp.watch(['app/*.js', 'app/**/**/*.js', '!app/production.min.js', 'bower_components/**/*.js'], ['compress-js']);
});