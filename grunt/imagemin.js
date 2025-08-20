module.exports = function (grunt, options) {
    const imagemin = import('imagemin');
    const imageminOptipng = require('imagemin-optipng');
    return {
        dynamic: {
            options: {
                plugins: [
                    imageminOptipng()
                ]
            },
            files: [{
                expand: true,
                cwd: '<%= path.src %>/img',
                src: ['**/*.jpg', '**/*.jpeg', '**/*.png'],
                dest: '<%= path.dest %>/img',
                flatten: false,
            }]
        }
    }
}
