// add viewport watch to load stylesheet on viewport change
// Chrome doesn't load rel="preload" when the viewport changes
let sheets = document.querySelectorAll("link[rel=\"preload\"][as=\"style\"]");
// matchMedia support
const matchMedia = window.matchMedia || window.msMatchMedia;

const mediaWatcher = () => {
    sheets.forEach(sheet => {
        const media = sheet.media;

        if (matchMedia(media).matches) {
            sheet.rel = "stylesheet";
            sheet.onload = null;
            sheets = document.querySelectorAll("link[rel=\"preload\"][as=\"style\"]");
        }
    });
    // if there are no more sheets, remove the event listeners
    if (!sheets.length) {
        window.removeEventListener("orientationchange", mediaWatcher);
        window.removeEventListener("resize", mediaWatcher);
    }
};

window.addEventListener("orientationchange", mediaWatcher);
window.addEventListener("resize", mediaWatcher);
