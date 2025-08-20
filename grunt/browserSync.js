module.exports = function (grunt, options) {
    return {
        dev: {
            bsFiles: {
                src: [
                    options.path.dest + '/**/*',
                    './**/*.php'
                ]
            },
            options: {
                watchTask: true,
                // proxy: 'https://'+process.env.DDEV_HOSTNAME +':'+ process.env.DDEV_ROUTER_HTTPS_PORT,
                // proxy: 'localhost',
                online: false,
                open: false,
                notify: true,
                browser: false,
                injectChanges: true,
                host: process.env.DDEV_HOSTNAME,
            }
        }
    };
};
