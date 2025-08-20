module.exports = function(grunt, options) {
    return {
        default: {
            options: {
                plugins: [{
                    name: "preset-default",
                    params: {
                        overrides: {
                            removeViewBox: false
                        }
                    }
                }]
            },
            files: [{
                expand: true,
                cwd: "<%= path.src %>/img",
                src: ["**/*.svg"],
                dest: "<%= path.dest %>/img",
                flatten: false
            }]
        }
    };
};
