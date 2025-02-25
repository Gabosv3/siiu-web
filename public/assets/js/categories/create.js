document.addEventListener('DOMContentLoaded', function() {
    let form = document.querySelector('.needs-validation');
    let nameInput = document.getElementById('name');

    nameInput.addEventListener('input', function() {
        let pattern = /^[A-Za-z\s]+$/;
        if (pattern.test(nameInput.value)) {
            nameInput.classList.remove('is-invalid');
            nameInput.classList.add('is-valid');
        } else {
            nameInput.classList.remove('is-valid');
            nameInput.classList.add('is-invalid');
        }
    });

    form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    }, false);
}, false);

function previewImage() {
    const fileInput = document.getElementById('image');
    const preview = document.getElementById('imagePreview');
    const file = fileInput.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
}