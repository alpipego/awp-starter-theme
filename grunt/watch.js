module.exports = {
    grunt: {
        files: ['Gruntfile.js', 'grunt/*'],
        tasks: 'grunt'
    },
    sass: {
        files: ['<%= path.src %>/scss/**/*.scss', '<%= path.src %>/scss/*.scss'],
        tasks: ['sass', 'css']
    },
    tailwind: {
        files: ["<%= path.src %>/css/tailwind.css", "<%= path.src %>/css/tailwind/**/*.css", "_components/**/*.php", "_pages/**/*.php", "_templates/**/*.php", "tailwind.config.js"],
        tasks: ['tailwind', 'css']
    },
    js: {
        files: ['<%= path.src %>/js/**/*.js', '<%= path.src %>/js/*.js'],
        tasks: 'js'
    },
    img: {
        files: ['<%= path.src %>/img/**/*.{jpe?g,png,gif}'],
        tasks: ['imagemin']
    },
    svg: {
        files: ['<%= path.src %>/img/**/*.svg'],
        tasks: ['svgmin']
    },
    fonts: {
        files: ['<%= path.src %>/fonts/**/*'],
        tasks: ['copy:fonts']
    },
    fields: {
        files: ['<%= path.src %>/fields/**/*'],
        tasks: ['copy:acfJson']
    },
    options: {
        spawn: false,
        interval: 1000
    }
};
