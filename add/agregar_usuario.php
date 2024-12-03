<?php
// Incluir la conexión a la base de datos
require '../config/dbconn.php';

// Crear una instancia de la clase Connection y abrir la conexión
$conn = (new Connection())->open();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT); // Encriptar la contraseña
    $rol = $_POST['rol'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];

    // Validar que los campos no estén vacíos
    if (!empty($nombre) && !empty($apellidos) && !empty($correo) && !empty($contrasena) && !empty($rol) && !empty($fecha_nacimiento)) {
        // Iniciar transacción para garantizar consistencia
        $conn->beginTransaction();

        try {
            // Insertar usuario en la tabla usuarios
            $sql = "INSERT INTO usuarios (nombre, apellidos, correo, contrasena, rol, fecha_nacimiento) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nombre, $apellidos, $correo, $contrasena, $rol, $fecha_nacimiento]);

            // Obtener el ID del usuario recién insertado
            $idUsuario = $conn->lastInsertId();

            // Consulta para obtener la última fila de la tabla usuarios con el rol de 'tutor'
            $sqlGetLastTutor = "SELECT id_usuario FROM usuarios WHERE rol = 'tutor' ORDER BY id_usuario DESC LIMIT 1";
            $stmtGetLastTutor = $conn->prepare($sqlGetLastTutor);
            $stmtGetLastTutor->execute();
            $tutor = $stmtGetLastTutor->fetch();
            
            if ($tutor) {
                // Insertar el ID del tutor en la tabla tutores
                $sqlTutor = "INSERT INTO tutores (id_tutor) VALUES (?)";
                $stmtTutor = $conn->prepare($sqlTutor);
                $stmtTutor->execute([$tutor['id_usuario']]);
            }
            
            // Confirmar transacción
            $conn->commit();

            // Redirigir con mensaje de éxito
            session_start();
            $_SESSION['message'] = "Registro exitoso";
            header('Location: ../views/register.php');
            exit();
        } catch (Exception $e) {
            // Revertir transacción si ocurre un error
            $conn->rollBack();
            session_start();
            $_SESSION['message'] = "Error: " . $e->getMessage();
            header('Location: ../views/register.php');
            exit();
        }
    } else {
        session_start();
        $_SESSION['message'] = "Por favor, completa todos los campos";
        header('Location: ../views/register.php');
        exit();
    }
}

// Cerrar la conexión
$conn = null;
?>
