module.exports = function (grunt) {
    const path = require('path');
    require('jit-grunt')(grunt, {
        'postcss': '@lodder/grunt-postcss'
    });
    require('time-grunt')(grunt);
    require('load-grunt-config')(grunt, {
        jitGrunt: true,
        data: {
            isBuild: grunt.cli.tasks.indexOf('build') > -1,
            path: {
                project: path.resolve(__dirname, './'),
                src: 'assets',
                dest: 'dist',
                tmp: 'cache',
            }
        }
    });
};
