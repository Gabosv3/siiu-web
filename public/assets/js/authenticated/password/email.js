// Ejecutar cuando el contenido de la página esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    // Busca el <div> con el mensaje de éxito
    let successMessage = document.getElementById('success-message');
    if (successMessage) {
        // Obtiene el mensaje de éxito del atributo data-status
        let status = successMessage.getAttribute('data-status');
        // Muestra el mensaje de éxito con SweetAlert
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: 'Correo Enviado',
            confirmButtonText: 'Aceptar'
        });
    }
});