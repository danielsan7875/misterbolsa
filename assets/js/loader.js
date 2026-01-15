// Esperamos a que el DOM esté disponible para manipular el body
document.addEventListener("DOMContentLoaded", () => {
    document.body.classList.add('loading-active');
});

// Cuando TODO (imágenes, estilos, etc) termine de cargar
window.addEventListener('load', () => {
    const loader = document.getElementById('loading-screen');
    
    if (loader) {
      
        loader.classList.add('fade-out');
        
        // Quitamos el bloqueo de scroll
        document.body.classList.remove('loading-active');

    
        setTimeout(() => {
            loader.style.display = 'none';
        }, 500);
    }
});