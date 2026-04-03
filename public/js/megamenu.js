document.addEventListener("DOMContentLoaded", function () {
    const columns = document.querySelectorAll(".mega-menu .col-md-3");

    columns.forEach(col => {
        col.addEventListener("mouseenter", () => {
            const submenu = col.querySelector("ul");
            if (submenu) submenu.style.display = "block";
        });

        col.addEventListener("mouseleave", () => {
            const submenu = col.querySelector("ul");
            if (submenu) submenu.style.display = "none";
        });
    });
});
