document.addEventListener("DOMContentLoaded", () => {
    document.querySelector('.m-header-burger').onclick = (event) => {
        for (let elem of[document.querySelector('.m-header-burger'), document.querySelector('.m-header-menu')]) {
            elem.classList.toggle("active");
        }
    };
});