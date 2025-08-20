module.exports = function (grunt, options) {
    return {
        default: {
            options: {
                sourceMap: false,
                compress: {
                    drop_console: true
                },
                mangle: true,
            },
            files: [{
                expand: true,
                cwd: '<%= path.tmp %>/js',
                src: ['**/*.js', '*.js'],
                dest: options.path.dest + '/js',
                ext: '.js',
                extDot: 'first'
            }]
        },
        dev: {
            options: {
                sourceMap: true,
                compress: false,
                mangle: false
            },
            files: [{
                expand: true,
                cwd: '<%= path.tmp %>/js',
                src: ['**/*.js', '*.js'],
                dest: options.path.dest + '/js',
                ext: '.js',
                extDot: 'first'
            }]
        }
    };
};
