document.addEventListener("DOMContentLoaded", function() {
    let passwordInput = document.getElementById('password');
    let confirmPasswordInput = document.getElementById('password-confirm');
    let lengthFeedback = document.getElementById('lengthFeedback');
    let lowercaseFeedback = document.getElementById('lowercaseFeedback');
    let uppercaseFeedback = document.getElementById('uppercaseFeedback');
    let numberFeedback = document.getElementById('numberFeedback');
    let symbolFeedback = document.getElementById('symbolFeedback');
    let confirmPasswordFeedback = document.getElementById('confirmPasswordFeedback');
    let lengthIcon = document.getElementById('lengthIcon');
    let lowercaseIcon = document.getElementById('lowercaseIcon');
    let uppercaseIcon = document.getElementById('uppercaseIcon');
    let numberIcon = document.getElementById('numberIcon');
    let symbolIcon = document.getElementById('symbolIcon');

    function validatePassword() {
        let password = passwordInput.value;

        // Check length
        if (password.length >= 8) {
            lengthFeedback.innerHTML = 'Debe tener al menos 8 caracteres. <i class="fas fa-check" style="color: green;"></i>';
            lengthIcon.classList.remove('fa-times');
            lengthIcon.classList.add('fa-check');
        } else {
            lengthFeedback.innerHTML = 'Debe tener al menos 8 caracteres. <i class="fas fa-times" style="color: red;"></i>';
            lengthIcon.classList.remove('fa-check');
            lengthIcon.classList.add('fa-times');
        }

        // Check lowercase
        if (/[a-z]/.test(password)) {
            lowercaseFeedback.innerHTML = 'Debe contener al menos una letra minúscula. <i class="fas fa-check" style="color: green;"></i>';
            lowercaseIcon.classList.remove('fa-times');
            lowercaseIcon.classList.add('fa-check');
        } else {
            lowercaseFeedback.innerHTML = 'Debe contener al menos una letra minúscula. <i class="fas fa-times" style="color: red;"></i>';
            lowercaseIcon.classList.remove('fa-check');
            lowercaseIcon.classList.add('fa-times');
        }

        // Check uppercase
        if (/[A-Z]/.test(password)) {
            uppercaseFeedback.innerHTML = 'Debe contener al menos una letra mayúscula. <i class="fas fa-check" style="color: green;"></i>';
            uppercaseIcon.classList.remove('fa-times');
            uppercaseIcon.classList.add('fa-check');
        } else {
            uppercaseFeedback.innerHTML = 'Debe contener al menos una letra mayúscula. <i class="fas fa-times" style="color: red;"></i>';
            uppercaseIcon.classList.remove('fa-check');
            uppercaseIcon.classList.add('fa-times');
        }

        // Check number
        if (/\d/.test(password)) {
            numberFeedback.innerHTML = 'Debe contener al menos un número. <i class="fas fa-check" style="color: green;"></i>';
            numberIcon.classList.remove('fa-times');
            numberIcon.classList.add('fa-check');
        } else {
            numberFeedback.innerHTML = 'Debe contener al menos un número. <i class="fas fa-times" style="color: red;"></i>';
            numberIcon.classList.remove('fa-check');
            numberIcon.classList.add('fa-times');
        }

        // Check symbol
        if (/[@$!%*?&]/.test(password)) {
            symbolFeedback.innerHTML = 'Debe contener al menos un símbolo (@$!%*?&). <i class="fas fa-check" style="color: green;"></i>';
            symbolIcon.classList.remove('fa-times');
            symbolIcon.classList.add('fa-check');
        } else {
            symbolFeedback.innerHTML = 'Debe contener al menos un símbolo (@$!%*?&). <i class="fas fa-times" style="color: red;"></i>';
            symbolIcon.classList.remove('fa-check');
            symbolIcon.classList.add('fa-times');
        }
    }

    function validateConfirmPassword() {
        let password = passwordInput.value;
        let confirmPassword = confirmPasswordInput.value;

        if (password !== confirmPassword) {
            confirmPasswordFeedback.innerHTML = 'Las contraseñas no coinciden. <i class="fas fa-times" style="color: red;"></i>';
        } else {
            confirmPasswordFeedback.innerHTML = 'Las contraseñas coinciden. <i class="fas fa-check" style="color: green;"></i>';
        }
    }

    passwordInput.addEventListener('input', validatePassword);
    confirmPasswordInput.addEventListener('input', validateConfirmPassword);

    let form = document.getElementById('resetPasswordForm');
    form.addEventListener('submit', function(event) {
        validatePassword();
        validateConfirmPassword();

        // Prevent form submission if there are validation errors
        if (document.querySelectorAll('#passwordFeedback .fa-times').length > 0) {
            event.preventDefault();
        }
    });
});