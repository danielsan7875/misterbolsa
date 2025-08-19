document.addEventListener("DOMContentLoaded", () => {
    const loader = document.getElementById("loaderPagina"); // Mueve la declaración aquí

    const showLoader = () => {
        loader.classList.add("show_loader");
    }

    const hideLoader = () => {
        loader.classList.remove("show_loader");
    }

    showLoader(); // Muestra el loader al cargar el DOM

    window.addEventListener("load", () => {
        setTimeout(() => {
            hideLoader(); // Oculta el loader después de 500 ms
        }, 500);
    });
});