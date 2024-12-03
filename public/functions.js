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
