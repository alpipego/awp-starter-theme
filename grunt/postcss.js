module.exports = function (grunt, options) {
    const sourcemap = !options.isBuild;
    const defaultTheme = require('tailwindcss/defaultTheme');

    return {
        mediaqueries: {
            options: {
                processors: [
                    require('@tailwindcss/nesting'),
                    require('postcss-combine-media-query'),
                    require("postcss-extract-media-query")({
                        output: {
                            path: options.path.tmp + "/css"
                        },
                        entry: options.path.tmp + "/css/app.css",
                        queries: ["md", "lg", "xl", "2xl"].reduce((acc, size) => {
                            acc[`(width >= ${defaultTheme.screens[size]})`] = size;
                            return acc;
                        }, {}),
                        extractAll: false
                    })
                ],
                map: sourcemap
            },
            files: [{
                src: options.path.tmp + "/css/app.css",
                dest: options.path.tmp + "/css/app.css"
            }]
        },
        default: {
            options: {
                processors: [
                    require("tailwindcss"),
                    require("autoprefixer")(),
                ],
                map: sourcemap,
                diff: false
            },
            files: [{
                expand: true,
                cwd: options.path.tmp + "/scss",
                src: "**/*.css",
                dest: options.path.tmp + "/css",
                ext: ".css",
                extDot: "last"
            }]
        },
        nano: {
            options: {
                processors: [
                    require("cssnano")({discardUnused: false}) // else @font-face disappears in font.css
                ],
                map: sourcemap
            },
            files: [{
                expand: true,
                cwd: options.path.tmp + "/css",
                src: "**/*.css",
                dest: options.path.dest + "/css",
                ext: ".css",
                extDot: "last"
            }]
        },
        tailwind: {
            options: {
                processors: [
                    require("@tailwindcss/postcss")(),
                ]
            },
            files: [
                {
                    src: options.path.src + "/tailwind/tailwind.css",
                    dest: options.path.tmp + "/css/app.css"
                }, {
                    src: options.path.src + "/tailwind/editor-extra.css",
                    dest: options.path.tmp + "/css/editor-extra.css"
                }
            ]
        }
    };
};
