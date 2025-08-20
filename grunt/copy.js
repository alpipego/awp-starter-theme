module.exports = function (grunt, options) {
    return {
        fonts: {
            options: {
                noProcess: true
            },
            files: [{
                expand: true,
                cwd: '<%= path.src %>/fonts',
                src: ['**/*.{eot,svg,ttf,woff,woff2}'],
                dest: '<%= path.dest %>/fonts',
                flatten: true,
            }]
        },
        css: {
            files: [{
                expand: true,
                cwd: '<%= path.tmp %>/css',
                src: ['**/*.css'],
                dest: '<%= path.dest %>/css',
            }]
        },
        acfJson: {
            files: [{
                expand: true,
                cwd: '<%= path.src %>/fields',
                src: ['**/*.json'],
                dest: '<%= path.dest %>/fields',
            }]
        }
    };
};
