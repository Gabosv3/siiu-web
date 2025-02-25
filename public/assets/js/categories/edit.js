document.addEventListener('DOMContentLoaded', function() {
    // Selecciona el formulario para la validación
    let form = document.querySelector('.needs-validation');
    // Selecciona el campo de entrada de nombre
    let nameInput = document.getElementById('name');

    // Agrega un evento al campo de nombre para validar la entrada
    nameInput.addEventListener('input', function() {
        let pattern = /^[A-Za-z\s]+$/; // Patrón para letras y espacios
        if (pattern.test(nameInput.value)) {
            // Si el patrón es válido
            nameInput.classList.remove('is-invalid');
            nameInput.classList.add('is-valid');
        } else {
            // Si el patrón es inválido
            nameInput.classList.remove('is-valid');
            nameInput.classList.add('is-invalid');
        }
    });

    // Maneja el envío del formulario
    form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
            // Si el formulario no es válido, previene el envío
            event.preventDefault();
            event.stopPropagation();
        }
        // Agrega clase de validación al formulario
        form.classList.add('was-validated');
    }, false);
}, false);