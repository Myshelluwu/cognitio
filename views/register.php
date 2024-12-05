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
    <title>Registrate</title>
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
                            <a class="nav-link montserrat-600 text-white" href="./login.php">Inicia sesión</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- Hero Section -->
    <section class="hero section-registro mt-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-12 text-center mx-auto">
                    <div class="section-hero-text">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class=" align-self-center">
                                    <div class="about-thumb2 bg-white shadow-lg">
                                        <section>
                                            <h2 class="mt-4 mb-3 montserrat-600">Registro</h2>

                                            <form action="../add/agregar_usuario.php" method="POST">
                                                <section id="content1" style="display: block;">
                                                    <!-- Nombre -->
                                                    <div class="form-group">
                                                        <input type="text" class="poppins-200 form-control mb-3 poppins-light"
                                                            name="nombre" placeholder="Nombre" required autofocus>
                                                    </div>
                                                    <!-- Apellidos -->
                                                    <div class="form-group">
                                                        <input type="text" class="poppins-200 form-control mb-3 poppins-light"
                                                            name="apellidos" placeholder="Apellidos" required>
                                                    </div>
                                                    <!-- Correo -->
                                                    <div class="form-group">
                                                        <input type="email" class="poppins-200 form-control mb-3 poppins-light"
                                                            name="correo" placeholder="nombre@email.com" required>
                                                    </div>
                                                    <!-- Fecha de Nacimiento -->
                                                    <div class="form-group">
                                                        <input type="date" class="poppins-200 form-control mb-3 poppins-light"
                                                            name="fecha_nacimiento" required>
                                                    </div>
                                                    <!-- Contraseña -->
                                                    <div class="input-group">
                                                        <input type="password" class="poppins-200 form-control mb-3 poppins-light"
                                                            id="contrasena" name="contrasena" placeholder="Contraseña"
                                                            required>
                                                        <div class="input-group-append">
                                                            <button type="button" id="togglePassword"
                                                                class="btn btn-outline-secondary poppins-200"
                                                                onclick="togglePasswordVisibility()">Mostrar</button>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Función checkboxChanged -->
                                                        <div class="input-group mb-2 justify-content-between">
                                                            <p class="mt-4 mb-3 poppins-300">Elige una opción</p>
                                                            <div class="d-flex justify-content-start align-items-center">
                                                                <input type="radio" name="rol" id="info1" value="Estudiante" checked autocomplete="off">
                                                                <label class="poppins-200 ms-2 me-4" for="info1">Estudiante</label>

                                                                <input type="radio" name="rol" id="info2" value="Tutor" autocomplete="off">
                                                                <label class="poppins-200 ms-2" for="info2">Tutor</label>
                                                            </div>
                                                        </div>

                                                    <!-- Rol -->
                                                    <input type="hidden" name="rol" id="rol" value="Estudiante">
                                                    <!-- Default -->
                                                    <button type="submit" class="btn btn-purple poppins-300"
                                                        name="registrar_usuario">Registrarse</button>
                                                </section>
                                            </form>

                                            <?php

                                            session_start();
                                            if (isset($_SESSION['message'])) {
                                                echo '<div class="mensaje">' . $_SESSION['message'] . '</div>';
                                                unset($_SESSION['message']);
                                            }
                                            ?>

                                            <p class="mt-3 poppins-200">¿Ya tienes una cuenta? <a
                                                    href="login.php">Inicia Sesión</a>
                                            </p>

                                        </section>
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