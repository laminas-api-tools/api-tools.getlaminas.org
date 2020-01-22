var autoprefixer = require('autoprefixer'),
    babel = require('gulp-babel'),
    cleanCss = require('gulp-clean-css'),
    concat = require('gulp-concat'),
    gulp = require('gulp'),
    postcss = require('gulp-postcss'),
    rename = require('gulp-rename'),
    sass = require('gulp-sass'),
    sourcemaps = require('gulp-sourcemaps'),
    terser = require('gulp-terser'),
    uglify = require('gulp-uglify'),
    prism = [
        'core',
        'markup',
        'css',
        'clike',
        'markup-templating',
        'javascript',
        'apacheconf',
        'bash',
        'batch',
        'css-extras',
        'diff',
        'docker',
        'git',
        'handlebars',
        'http',
        'ini',
        'json',
        'less',
        'makefile',
        'markdown',
        'nginx',
        'php',
        'php-extras',
        'powershell',
        'puppet',
        'rest',
        'sass',
        'scss',
        'smarty',
        'sql',
        'twig',
        'vim',
        'yaml'
    ];

gulp.task('js', function () {
    var prismComponents = [];
    for (var component in prism) {
        prismComponents[component] = 'node_modules/prismjs/components/prism-' + prism[component] + '.js';
    }

    return gulp.src(prismComponents.concat([
            'node_modules/prismjs/plugins/normalize-whitespace/prism-normalize-whitespace.js',
            'node_modules/bootstrap/dist/js/bootstrap.js',
            'node_modules/popper.js/dist/popper.js',
            'node_modules/jquery/dist/jquery.js'
        ]))
        .pipe(babel({presets: ['@babel/env'], sourceType: 'unambiguous'}))
        .pipe(concat({path: 'scripts.js'}))
        .pipe(gulp.dest('../public/js/'))
        .pipe(terser({mangle: false}).on('error', function (e) {
            console.log(e);
        }))
        .pipe(uglify({mangle: false}))
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('../public/js/'));
});

gulp.task('css', function () {
    return gulp
        .src('scss/*.scss')
        .pipe(sourcemaps.init())
        .pipe(sass())
        .on('error', sass.logError)
        .pipe(postcss([autoprefixer()]))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('../public/css/'))
        .pipe(cleanCss())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('../public/css/'));
});

gulp.task('default', gulp.series('css', 'js'));
