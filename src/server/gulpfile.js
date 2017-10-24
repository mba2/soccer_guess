const gulp  = require('gulp');          // MAIN MODULE
const del   = require('del');           // MODULE TO DELETE FILES AND FOLDERS
const util  = require('gulp-util');     // UTILITIES: ['GET CLI PASSED VARIABLES']


// gulp.task('clean',require('./tasks/clean')(del,'server-deploy'));
gulp.task('clean',require('./tasks/clean')(del,'server-deploy/'));
gulp.task('copy', require('./tasks/copy')(gulp));



console.log(util.env.production);




gulp.task('default', 
    [
        'clean',
        'copy'
    ],
    
    () => {
    
    }
);