[...document.querySelectorAll(".block-editor-iframe__body a, .block-editor-iframe__body button")].forEach(clickable => {
    clickable.addEventListener("click", e => {
        e.preventDefault();
    });
});
