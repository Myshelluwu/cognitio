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
        header('Location: menu_estudiante.php'); 
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
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Parkinsans:wght@300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!--Icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../style.css">
    <title>Inicia Sesion</title>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container-sm">
            <a class="parkinsans-700 text-white h2 mt-2" aria-current="page"
                href="../index.html">Cognitio</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="offcanvas offcanvas-end nav-color" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h3 class="offcanvas-title parkinsans-600 text-white"
                        id="offcanvasNavbarLabel">Cognitio</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>

                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link montserrat-600 text-white" aria-current="page" href="../index.html">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link montserrat-600 text-white" href="./register.php">Regístrate</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- Hero Section -->
    <section class="hero section-login py-5">
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
                                                <h2 class="mt-5 mb-4 text-center montserrat-600">Iniciar Sesión</h2>
                                                <div class="form-group">
                                                    <input type="email" class="form-control mb-3 poppins-200" name="correo"
                                                        placeholder="Correo electrónico" required>
                                                </div>
                                                <div class="input-group">
                                                        <input type="password" class="form-control mb-3 poppins-200"
                                                            name="contrasena" id="contrasena" placeholder="Contraseña"
                                                            required>
                                                        <div class="input-group-append">
                                                            <button type="button" id="togglePassword"
                                                                class="btn btn-outline-secondary poppins-200"
                                                                onclick="togglePasswordVisibility()">Mostrar</button>
                                                        </div>
                                                </div>
                                                
                                                <button type="submit" class="btn btn-purple mx-auto d-block poppins-300"
                                                    name="login">Entrar</button>

                                                <!-- <?php
                                                session_start();
                                            
                                                if (isset($_SESSION['message'])) {
                                                    echo '<div class="mensaje">' . $_SESSION['message'] . '</div>';
                                                    unset($_SESSION['message']);
                                                }
                                                ?> -->
                                                
                                            </form>
                                            <p class="mt-3 poppins-200">¿No tienes una cuenta? <a href="register.php">Regístrate</a>
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

    <!-- scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script
        src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script
        src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="/script.js"></script>
    <script src="../public/funciones.js"></script>

</body>

</html>