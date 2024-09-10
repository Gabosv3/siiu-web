document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("updateForm");

    // Validate form on submit
    form.addEventListener("submit", function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }

        form.classList.add("was-validated");
    });

    // DUI input mask and validation
    const duiInput = document.getElementById("dui");
    duiInput.addEventListener("input", function () {
        let value = duiInput.value.replace(/\D/g, "");
        if (value.length > 8) {
            value = value.slice(0, 8) + "-" + value.slice(8);
        }
        duiInput.value = value;

        // Validación adicional para evitar el DUI 00000000-0
        if (duiInput.value === "00000000-0") {
            duiInput.setCustomValidity("Este DUI no es válido.");
        } else {
            duiInput.setCustomValidity(
                /^\d{8}-\d$/.test(duiInput.value) ? "" : "Invalid"
            );
        }
    });

    const telefonoInput = document.getElementById("telefono");
    telefonoInput.addEventListener("input", function () {
        let value = telefonoInput.value.replace(/\D/g, "");

        // Aplica la máscara si la longitud es mayor a 4 caracteres
        if (value.length > 4) {
            value = value.slice(0, 4) + "-" + value.slice(4);
        }

        telefonoInput.value = value;

        // Valida el formato del teléfono y que no sea 0000-0000
        const isValid =
            /^\d{4}-\d{4}$/.test(telefonoInput.value) &&
            telefonoInput.value !== "0000-0000";
        telefonoInput.setCustomValidity(isValid ? "" : "Invalid");
    });

    // Age validation
    const fechaNacimientoInput = document.getElementById("fecha_nacimiento");
    fechaNacimientoInput.addEventListener("input", function () {
        const birthDate = new Date(fechaNacimientoInput.value);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const month = today.getMonth() - birthDate.getMonth();
        if (
            month < 0 ||
            (month === 0 && today.getDate() < birthDate.getDate())
        ) {
            age--;
        }
        fechaNacimientoInput.setCustomValidity(age >= 16 ? "" : "Invalid");
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const passwordUpdateForm = document.getElementById("passwordUpdateForm");
    const newPasswordInput = document.getElementById("newPassword");
    const renewPasswordInput = document.getElementById("renewPassword");
    const passwordPattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/;

    // Validación del formulario de actualización de contraseña
    passwordUpdateForm.addEventListener("submit", function (event) {
        if (!passwordUpdateForm.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        passwordUpdateForm.classList.add("was-validated");
    });

    newPasswordInput.addEventListener("input", function () {
        newPasswordInput.setCustomValidity(
            newPasswordInput.value === "" ||
                passwordPattern.test(newPasswordInput.value)
                ? ""
                : "La nueva contraseña debe tener al menos 8 caracteres, incluyendo una letra mayúscula, una letra minúscula, un número y un carácter especial."
        );
        if (renewPasswordInput.value !== "") {
            renewPasswordInput.dispatchEvent(new Event("input"));
        }
    });

    renewPasswordInput.addEventListener("input", function () {
        renewPasswordInput.setCustomValidity(
            newPasswordInput.value === renewPasswordInput.value
                ? ""
                : "La confirmación de la nueva contraseña no coincide."
        );
    });
});

document.addEventListener("DOMContentLoaded", function () {
    if (document.querySelector(".alert-danger")) {
        var profileChangePasswordTab = new bootstrap.Tab(
            document.querySelector(
                '[data-bs-target="#profile-change-password"]'
            )
        );
        profileChangePasswordTab.show();
    }
});
