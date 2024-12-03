<?php
session_start();
include_once '../config/dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['correo'];
    $password = $_POST['contrasena'];

    $database = new Connection();
    $db = $database->open();

    $stmt = $db->prepare("SELECT * FROM reg_usuarios WHERE correo = :email");
    $stmt->bindParam(':email', $email); 
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($user && $password === $user['contrasena']) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_tipo'] = $user['tipo'];
        $_SESSION['user_correo'] = $user['correo'];
        $_SESSION['user_contrasena'] = $user['contrasena'];
        header('Location: index.php'); 
    } else {

        $_SESSION['message'] = 'Correo electrónico o contraseña incorrectos';
        header('Location: login.php');
    }


    $database->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!--Icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../public/style.css">

<body class="body">
    <div class="card-container">
        <div class="cardlog">
            <header class="card-titlelog">Iniciar sesión</header>
          
            
            <div class="form-group">
              <label for="email">Correo electrónico</label>
              <input type="text" name="email" id="email" required class="input-field">
            </div>
          
            <div class="form-group">
              <label for="contrasena">Contraseña</label>
              <input type="contrasena" name="contrasena" id="contrasena" required class="input-field">
            </div>
          
            <br>
          
            <div>
              <button class="btnfos btnfos-5">Iniciar</button>
            </div>

            <?php
            session_start();
            if (isset($_SESSION['message'])) {
                echo '<div class="mensaje">' . $_SESSION['message'] . '</div>';
                unset($_SESSION['message']);
            }
            ?>
          
            <div class="register-link">
              No tienes una cuenta? <a href="register.php">Regístrate ahora</a>
            </div>
          </div>
    </div>
    <script src="/AprendeL/public/functions.js"></script>
</body>
</html>