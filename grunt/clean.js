module.exports = {
    default: [
        '<%= path.tmp %>/',
    ],
    build: [
        '<%= path.tmp %>/',
        '<%= path.dest %>/{css,js,fonts}/**/*',
    ],
    responsiveCss: [
        '<%= path.dest %>/css/app-*.css',
    ],
    images: [
        '<%= path.dest %>/img/**/*',
    ],
    options: {
        force: true
    }
};
