<?php
session_start();
require_once('../config/dbconn.php');

// Verificar si el usuario está logueado y es tutor
if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'tutor') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $materias = $_POST['materias'] ?? [];
    $tutorId = $_SESSION['user_id'];

    if (!empty($materias)) {
        $conn = (new Connection())->open();

        // Eliminar materias actuales del tutor
        $sqlDelete = "DELETE FROM tutoresmaterias WHERE id_tutor = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->execute([$tutorId]);

        // Insertar nuevas materias
        $sqlInsert = "INSERT INTO tutoresmaterias (id_tutor, id_materia) VALUES (?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);

        foreach ($materias as $materiaId) {
            $stmtInsert->execute([$tutorId, $materiaId]);
        }

        $conn = null;
        $_SESSION['message'] = "Materias actualizadas con éxito.";
    } else {
        $_SESSION['message'] = "Debes seleccionar al menos una materia.";
    }
}

header('Location: ../views/profile.php');
exit();
?>
