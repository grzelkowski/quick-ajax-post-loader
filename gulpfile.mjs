import gulp from 'gulp';
import replace from 'gulp-replace';
import prettier from 'gulp-prettier';
import terser from 'gulp-terser';
import rename from 'gulp-rename';
import cleanCSS from 'gulp-clean-css';
import postcss from 'gulp-postcss';
import autoprefixer from 'autoprefixer';

function logMessage(message) {
    console.log(`[QAPL Gulp notification] ${message}`);
}

// Task to optimize and beautify JavaScript files
export const optimizeJs = gulp.task('optimize-js', function () {
    return gulp.src(['js/*-dev.js']) // source files ending with "-dev.js"
        .pipe(prettier({
            tabWidth: 4,           // use 4 spaces for indentation
            useTabs: false,        // use spaces instead of tabs
            singleQuote: false,    // use double quotes
            trailingComma: 'none', // no trailing commas
            printWidth: 240        // maximum line width for better readability
        }))
        .pipe(rename(function (path) {
            path.basename = path.basename.replace('-dev', ''); // remove "-dev" from the file name
        }))
        .pipe(gulp.dest('js')) // output beautified JS files
        .on('end', () => logMessage('JavaScript optimized successfully!'));
});

// Task to minify JavaScript files
export const minifyJs = gulp.task('minify-js', function () {
    return gulp.src(['js/*-dev.js']) // source files ending with "-dev.js"
        .pipe(terser({
            compress: {
                drop_console: true, // remove console.log, console.warn, etc.
            },
            format: {
                comments: false, // remove all comments
            }
        }).on('error', function (err) {
            console.error('Error during minification:', err.message);
            process.exit(1); // stop the process completely
        }))
        .pipe(rename(function (path) {
            path.basename = path.basename.replace('-dev', ''); // remove "-dev" from the file name
            path.basename += '.min'; // add ".min" suffix
        }))
        .pipe(gulp.dest('js')) // output minified JS files
        .on('end', () => logMessage('JavaScript minified successfully!'));
});

// Default task to run all JavaScript tasks
//gulp.task('default', gulp.series('optimize-js', 'minify-js'));

// Task to optimize CSS files
export const optimizeCss = gulp.task('optimize-css', function () {
    return gulp.src(['css/style-dev.css', 'css/admin-style-dev.css']) // source files ending with "-dev.css"
        .pipe(postcss([
            autoprefixer({
                overrideBrowserslist: ['> 0.5%', 'last 3 versions'],
                grid: true
            })
        ])) // add vendor prefixes
        .pipe(rename(function (path) {
            path.basename = path.basename.replace('-dev', ''); // remove "-dev" from the file name
        }))
        .pipe(gulp.dest('css')) // output optimized CSS files
        .on('end', () => logMessage('CSS optimized successfully!'));
});

// Task to minify CSS files
export const minifyCss = gulp.task('minify-css', function () {
    return gulp.src(['css/style.css', 'css/admin-style.css']) // optimized CSS files
        .pipe(cleanCSS()) // minify CSS
        .pipe(rename({ suffix: '.min' })) // add ".min" suffix
        .pipe(gulp.dest('css')) // output minified CSS files
        .on('end', () => logMessage('CSS minified successfully!'));
});

// Default task to run all tasks in sequence
gulp.task('default', gulp.series(
    gulp.series('optimize-js', 'minify-js'), // first optimize, then minify JS
    gulp.series('optimize-css', 'minify-css') // then optimize and minify CSS
));
