<?php
session_start();
include_once('../config/dbconn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['correo'];
    $password = $_POST['contrasena'];

    $database = new Connection();
    $db = $database->open();

    $stmt = $db->prepare("SELECT * FROM usuarios WHERE correo = :email");
    $stmt->bindParam(':email', $email); 
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($user && $password === $user['contrasena']) {
        session_start();
        $_SESSION['user_id'] = $user['id_usuario'];
        $_SESSION['user_nombre'] = $user['nombre'];
        $_SESSION['user_apellidos'] = $user['apellidos'];
        $_SESSION['user_correo'] = $user['correo'];
        $_SESSION['user_contrasena'] = $user['contrasena'];
        $_SESSION['user_rol'] = $user['rol'];
        $_SESSION['user_fecha_nacimiento'] = $user['fecha_nacimiento'];
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
    <link rel="stylesheet" href="./public/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css">
    <title>Login</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-white shadow-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="./index.php">
                <img src="./Public/Images/logo_n.png" alt="logo" width="150" height="40">

            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="./index.php">Menu</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>

    <section class="hero section-login">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-12 text-center mx-auto">
                    <div class="section-hero-text">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class=" align-self-center">
                                    <div class="about-thumb bg-white shadow-lg">
                                        <div class="about-info">
                                            
                                            <form action="./login.php" method="POST">
                                                <h2 class="mt-5 mb-4 text-center">Iniciar Sesión</h2>
                                                <div class="form-group">
                                                    <input type="email" class="form-control mb-3" name="correo"
                                                        placeholder="Correo electrónico" required>
                                                </div>
                                                <div class="input-group">
                                                        <input type="password" class="form-control mb-3"
                                                            name="contrasena" id="contrasena" placeholder="Contraseña"
                                                            required>
                                                        <div class="input-group-append">
                                                            <button type="button" id="togglePassword"
                                                                class="btn btn-outline-secondary"
                                                                onclick="togglePasswordVisibility()">Mostrar</button>
                                                        </div>
                                                </div>
                                                
                                                <button type="submit" class="btn btn-dark mx-auto d-block"
                                                    name="login">Entrar</button>

                                                <?php
                                                session_start();
                                            
                                                if (isset($_SESSION['message'])) {
                                                    echo '<div class="mensaje">' . $_SESSION['message'] . '</div>';
                                                    unset($_SESSION['message']);
                                                }
                                                ?>
                                                
                                            </form>
                                            <p class="mt-3">¿No tienes una cuenta? <a href="register.php">Regístrate</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <script src="./public/js/bootstrap.js"></script>
    <script src="../public/funciones.js"></script>

</body>

</html>