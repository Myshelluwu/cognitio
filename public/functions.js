// Función para mostrar el contenido correspondiente
function showContent(contentToShow, contentToHide) {
    document.getElementById(contentToShow).style.display = 'block';
    document.getElementById(contentToHide).style.display = 'none';
}

// Función para inicializar los event listeners
function init() {
    document.getElementById('info1').addEventListener('change', function() {
        showContent('content1', 'content2');
    });

    document.getElementById('info2').addEventListener('change', function() {
        showContent('content2', 'content1');
    });
}

// Inicializar los event listeners cuando se carga la página
document.addEventListener('DOMContentLoaded', init);


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
    var passwordField = document.getElementById("contrasena2");
    var toggleButton = document.getElementById("togglePassword2");

    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleButton.textContent = "Ocultar";
    } else {
        passwordField.type = "password";
        toggleButton.textContent = "Mostrar";
    }
}