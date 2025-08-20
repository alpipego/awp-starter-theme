// i18n.js - save this in your grunt config folder (e.g., grunt/i18n.js)
module.exports = function (grunt) {
    'use strict';

    grunt.registerTask('i18n', 'Run i18n tasks', function () {
        const done = this.async();
        const execOptions = {
            stdout: true,
            stderr: true
        };

        // The path to your PHP script
        const phpScript = 'scripts/create-translatables.php';

        grunt.log.writeln('Running i18n via direct PHP execution: ' + phpScript);
        grunt.util.spawn({
            cmd: 'php',
            args: [phpScript],
            opts: execOptions
        }, function (error, result, code) {
            if (error) {
                grunt.log.error('PHP i18n script failed with error code: ' + code);
                done(false);
            } else {
                grunt.log.ok('PHP i18n script completed successfully.');
                done();
            }
        });
    });

    // Configuration for the i18n task
    return {
        options: {
            // Default options here
        },
    };
};
