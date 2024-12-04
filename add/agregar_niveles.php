<?php
session_start();
require_once('../config/dbconn.php');

// Verificar si el usuario está logueado y es tutor
if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'tutor') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $niveles = $_POST['niveles'] ?? [];
    $tutorId = $_SESSION['user_id'];

    if (!empty($niveles)) {
        $conn = (new Connection())->open();

        // Eliminar niveles actuales del tutor
        $sqlDelete = "DELETE FROM tutoresnivelesacademicos WHERE id_tutor = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->execute([$tutorId]);

        // Insertar nuevos niveles
        $sqlInsert = "INSERT INTO tutoresnivelesacademicos (id_tutor, id_nivel_academico) VALUES (?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);

        foreach ($niveles as $nivelId) {
            $stmtInsert->execute([$tutorId, $nivelId]);
        }

        $conn = null;
        $_SESSION['message'] = "Niveles académicos actualizados con éxito.";
    } else {
        $_SESSION['message'] = "Debes seleccionar al menos un nivel académico.";
    }
}

header('Location: ../views/profile.php');
exit();
?>
