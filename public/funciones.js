function checkboxChanged(checkboxNumber) {
    var checkbox = document.getElementById('checkbox' + checkboxNumber);
    var infoDiv = document.getElementById('info' + checkboxNumber);

    var otherCheckboxNumber = checkboxNumber === 1 ? 2 : 1;
    var otherCheckbox = document.getElementById('checkbox' + otherCheckboxNumber);

    otherCheckbox.disabled = checkbox.checked;

    if (checkbox.checked) {
        infoDiv.style.display = 'block';
    } else {
        infoDiv.style.display = 'none';
    }
}

function togglePasswordVisibility() {
    var passwordField = document.getElementById("contrasena");
    var toggleButton = document.getElementById("togglePassword");

    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleButton.textContent = "Ocultar";
    } else {
        passwordField.type = "password";
        toggleButton.textContent = "Mostrar";
    }
}

function togglePasswordVisibility2() {
    var passwordField = document.getElementById("contrasenaE");
    var toggleButton = document.getElementById("togglePassword2");

    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleButton.textContent = "Ocultar";
    } else {
        passwordField.type = "password";
        toggleButton.textContent = "Mostrar";
    }
}

function actualizarValor() {
    var select = document.getElementById("estado");
}


function oferta() {

    var textoOferta = document.getElementById("textoOferta");

    if (textoOferta.style.display === "none") {
        textoOferta.style.display = "block";
    } else {
        textoOferta.style.display = "none";
    }
}
function toggleResul() {
    var textoResul = document.getElementById("textoResul");
        textoResul.hidden = !textoResul.hidden;
}

document.addEventListener("DOMContentLoaded", function () {
    const estudianteRadio = document.getElementById("info1");
    const tutorRadio = document.getElementById("info2");
    const rolInput = document.getElementById("rol");

// Actualizar el valor del rol basado en el radio seleccionado
estudianteRadio.addEventListener("change", function () {
    if (this.checked) {
    rolInput.value = "Estudiante";
    }
});

tutorRadio.addEventListener("change", function () {
    if (this.checked) {
    rolInput.value = "Tutor";
    }
});
});
