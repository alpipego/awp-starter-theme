module.exports = {
    "build": [
        "clean:build",
        "copy",
        "sass",
        "css",
        "postcss:nano",
        "js",
        "buildImages",
        "i18n",
    ],
    "default": [
        "grunt",
        "browserSync",
        "watch"
    ],
    // if grunt config changes
    "grunt": [
        "clean:default",
        "svgmin",
        "copy",
        "sass",
        "css",
        "js",
    ],
    // groups
    "buildImages": [
        "clean:images",
        "svgmin",
        "imagemin",
    ],
    "css": [
        "postcss:tailwind",
        "postcss:default",
        "postcss:mediaqueries",
        "newer:copy:css"
    ],
    "js": [
        "rollup",
    ]
};
