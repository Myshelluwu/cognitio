<?php
session_start();
require_once('../config/dbconn.php');

// Verificar si el usuario está logueado y es tutor
if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'tutor') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descripcion = trim($_POST['descripcion'] ?? '');
    $tutorId = $_SESSION['user_id'];

    if (!empty($descripcion)) {
        $conn = (new Connection())->open();

        // Actualizar descripción en la tabla tutores
        $sql = "UPDATE tutores SET descripcion = ? WHERE id_tutor = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$descripcion, $tutorId]);

        $conn = null;
        $_SESSION['message'] = "Biografía actualizada con éxito.";
    } else {
        $_SESSION['message'] = "La biografía no puede estar vacía.";
    }
}

header('Location: ../views/profile.php');
exit();
?>
