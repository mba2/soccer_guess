// =========================
// MODULES
const gulp          = require('gulp');                  // MAIN MODULE
const _m            = require('gulp-load-plugins')();   // THIS VARIABLE WILL HOLD ALMOST !!! ALL PLUGINS

// =============== MODULES THAT FAILS TO BE LOADED INTO _m VARIABLE
const del           = require('del');                   // MODULE TO DELETE FILES AND FOLDERS


// =========================
// BUILD CONFIGURATION
const CONFIG = {
    DEPLOY_PATH : './server-deployed',
};


// CLEAN PROCESS
// =========================
gulp.task('clean', () => {
    return del.sync([
        './'+ CONFIG.DEPLOY_PATH
    ]);
});

// COPY PROCESS
// =========================
gulp.task('copy', () => {
    const filterAppClass = _m.filter('**/App.php', {restore: true});
    return gulp.src([
                    './+(api|classes|configuration)/**/*',      
                ])
                // HANDLING ERRORS - PLUMBER`S DEFAULT BEHAVIOR
                .pipe(_m.plumber())
                /*
                    WHEN RUNNING ON A PRODUCTION ENVIRONMENT... ON ALL FILES SELECTED ABOVE,
                    FILTER THE MAIN APP CLASS NAMED 'App.php'
                */
                .pipe( (_m.util.env.production) ? filterAppClass : _m.util.noop() )
                /*
                    WHEN RUNNING ON A PRODUCTION ENVIRONMENT... CHANGE THE $ENV VARIABLE 
                    VALUE FROM 'development' TO 'production'  
                */
                .pipe( (_m.util.env.production) 
                        ? _m.replace(/\$ENV\s*=\s*(.*)\s*;/, (match,group1) => {
                                return match.replace(group1,'"production"'); 
                          })                
                        : _m.util.noop()
                ) 
                /* 
                    WHEN RUNNING ON A PRODUCTION ENVIRONMENT ....RESTORE THE INITIAL SET OF 
                    FILES DEFINED ON gulp.src().. RIGHT AFTER THE 'return' KEYWORD 
                */
                .pipe( (_m.util.env.production) ? filterAppClass.restore : _m.util.noop() ) 
                /* 
                   WHEN RUNNING ON A PRODUCTION ENVIRONMENT... SET A DIFERENT DESTINATION PATH 
                */
                .pipe( (_m.util.env.production) ? gulp.dest( CONFIG.DEPLOY_PATH ) : _m.util.noop() ) 
});


gulp.task('default', 
                    [
                        'clean',
                        'copy'
                    ],
                    
                    () => {
                    
                    }
);