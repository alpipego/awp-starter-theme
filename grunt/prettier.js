module.exports = function (grunt, options) {
    return {
        default: {
            options: {
                plugins: [
                    require("@prettier/plugin-php"),
                    "prettier-plugin-tailwindcss"
                ],
                progress: true,
                configFilePath: options.path.project,
                tabWidth: 4,
                phpVersion: "8.3",
                pluginSearchDirs: false
            },
            files: [{
                expand: true,
                cwd: options.path.project + "/_{components,templates,pages}/",
                src: "**/*.php",
                dest: options.path.project + "/_{components,templates,pages}/",
                ext: ".php",
                extDot: "last",
                filter: dist => {
                    console.debug(dist);
                    return true;
                }
            }]
        }
    }
}
