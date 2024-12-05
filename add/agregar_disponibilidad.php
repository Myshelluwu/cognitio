<?php
session_start();
require_once('../config/dbconn.php');

// Verificar si el usuario está logueado y es tutor
if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'tutor') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $diasSemana = $_POST['dias_semana'] ?? []; // Múltiples días seleccionados
    $horaInicio = $_POST['hora_inicio'];
    $horaFin = $_POST['hora_fin'];
    $tutorId = $_SESSION['user_id'];

    // Validar los campos
    if (!empty($diasSemana) && !empty($horaInicio) && !empty($horaFin)) {
        $conn = (new Connection())->open();

        // Insertar una fila por cada día seleccionado
        $sqlInsert = "INSERT INTO disponibilidad (id_tutor, dia_semana, hora_inicio, hora_fin) VALUES (?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);

        foreach ($diasSemana as $dia) {
            $stmtInsert->execute([$tutorId, $dia, $horaInicio, $horaFin]);
        }

        $conn = null;
        $_SESSION['message'] = "Disponibilidad añadida con éxito.";
    } else {
        $_SESSION['message'] = "Debes seleccionar al menos un día y configurar las horas.";
    }
}

header('Location: ../views/profile.php');
exit();
?>
