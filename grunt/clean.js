module.exports = {
    default: [
        '<%= path.tmp %>/',
    ],
    build: [
        '<%= path.tmp %>/',
        '<%= path.dest %>/{css,js,fonts}/**/*',
    ],
    responsiveCss: [
        '<%= path.tmp %>/css/app-*.css',
        '<%= path.dest %>/css/app-*.css',
    ],
    images: [
        '<%= path.dest %>/img/**/*',
    ],
    options: {
        force: true
    }
};
