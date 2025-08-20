module.exports = function (grunt, options) {
    const sass = require('sass');

    return {
        default: {
            options: {
                implementation: sass,
                sourceMap: options.isBuild,
                includePaths: [
                    'assets/src/scss/',
                    'node_modules/'
                ],
            },
            files: [
                {
                    expand: true,
                    cwd: options.path.src + '/scss',
                    src: ["**/*.scss", "_pages/**/*.scss", "_templates/**/*.scss", "_components/**/*.scss"],
                    dest: options.path.tmp + '/scss',
                    ext: '.css',
                    extDot: 'first'
                }
            ]
        }
    };
};
