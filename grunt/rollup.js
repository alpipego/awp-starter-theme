module.exports = (grunt, options) => {
    const terser = require("@rollup/plugin-terser");
    const commonjs = require("@rollup/plugin-commonjs");
    const {nodeResolve} = require("@rollup/plugin-node-resolve");

    return {
        options: {
            treeshake: true,
            plugins: [
                nodeResolve(),
                commonjs(),
                terser({
                    sourceMap: !options.isBuild,
                    compress: options.isBuild ? {
                        drop_console: true
                    } : false,
                    mangle: options.isBuild,
                    toplevel: true,
                    format: {
                        beautify: !options.isBuild,
                    }
                })
            ],
            sourceMap: true,
            format: 'iife',
        },
        default: {
            options: {},
            files: [{
                expand: true,
                cwd: options.path.src + '/js',
                src: ['**/*.js', '!src/**/*', '!src/*'],
                dest: options.path.dest + '/js',
            }]
        }
    };
};
