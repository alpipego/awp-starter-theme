const menuToggle = document.getElementById("open-close-menu");
const open = document.getElementById("menu-open");
const close = document.getElementById("menu-close");
const shelf = document.getElementById("shelf");
menuToggle.addEventListener("click", () => {
    const isOpen = !!menuToggle.dataset.open;
    menuToggle.dataset.open = isOpen ? "" : "true";
    // swap the icons
    open.classList.toggle("hidden", !isOpen);
    open.classList.toggle("block", isOpen);
    close.classList.toggle("hidden", isOpen);
    close.classList.toggle("block", !isOpen);
    // open the menu shelf
    shelf.classList.toggle("flex", !isOpen);
    shelf.classList.toggle("hidden", isOpen);
});

const topMenus = document.querySelectorAll("#main-menu > ul > li");
// get all submenu toggles from the menus we gathered above
[...topMenus]
    .map(menu => [...menu.querySelectorAll("[data-submenu] button[data-submenu]")])
    .reduce((acc, buttons) => acc.concat(buttons), [])
    .forEach(toggle => {
        toggle.addEventListener("click", () => {
            const li = toggle.closest("li");
            // toggle this submenu
            li.dataset.submenuState = li.dataset.submenuState === "closed" ? "open" : "closed";

            if (li.dataset.submenuState === "closed") {
                // if this menu is closed, close all sub-sub...menus
                li.querySelectorAll("[data-submenu-state=\"open\"]").forEach(openMenu => {
                    openMenu.dataset.submenuState = "closed";
                });
            }
        });
    });

// Handle click and hover on the top menu items.
topMenus.forEach(menu => {
    let open, touch = false;
    menu.addEventListener("mouseover", () => {
        if (touch) {
            return;
        }
        // open on hover
        menu.dataset.submenuState = "open";
        open = Date.now();
    });
    menu.addEventListener("mouseout", () => {
        if (menu.matches(":hover")) {
            return;
        }

        // close this menu when not hovered
        menu.dataset.submenuState = "closed";

        // close all sub-sub... menus of this
        menu.querySelectorAll("[data-submenu-state=\"open\"]").forEach(openMenu => {
            openMenu.dataset.submenuState = "closed";
        });

        // blur so that the focus-within gets removed
        document.activeElement.blur();
    });
    menu.addEventListener("touchstart", () => {
        open = Date.now() - 10;
        touch = true;
    }, {passive: true});

    menu.querySelector("button[data-submenu]").addEventListener("click", () => {
        // prevent mouseover and click race-condition. Only observed in Chrome Device Simulator.
        if (Date.now() - open < 10) {
            return;
        }
        menu.dataset.submenuState = menu.dataset.submenuState === "closed" ? "open" : "closed";

        if (menu.dataset.submenuState !== "closed") {
            // if this is open close others.
            topMenus.forEach(topMenuToggle => {
                if (topMenuToggle !== menu) {
                    topMenuToggle.dataset.submenuState = "closed";
                }
            });

            return;
        }

        // close all sub-sub... menus of this
        menu.querySelectorAll("[data-submenu-state=\"open\"]").forEach(openMenu => {
            openMenu.dataset.submenuState = "closed";
        });

        // blur so that the focus-within gets removed
        document.activeElement.blur();
    });
});
