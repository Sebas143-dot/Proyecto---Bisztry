document.addEventListener("DOMContentLoaded", () => {

    // Funcionalidad de Alertas auto-dismiss
    const alerts = document.querySelectorAll(".alert");
    alerts.forEach((alert) => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000); // La alerta desaparece después de 5 segundos
    });

    // En el futuro, puedes añadir aquí más interactividad,
    // como la de ocultar el menú lateral (sidebar).

});