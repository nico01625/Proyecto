// Funcionalidad para abrir y cerrar el menú con el botón hamburguesa
document.getElementById("menu-toggle").addEventListener("click", function (e) {
    e.preventDefault();
    document.getElementById("wrapper").classList.toggle("toggled");
});

// Reloj en tiempo real 
function actualizarReloj() {
    const ahora = new Date();
    const opciones = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
    document.getElementById('reloj-actual').textContent = ahora.toLocaleTimeString('es-CO', opciones);
}
setInterval(actualizarReloj, 1000);
actualizarReloj();