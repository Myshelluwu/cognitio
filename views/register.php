<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!--Icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../public/style.css">

<body>

    <section class="hero section-registro">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-12 text-center mx-auto">
                    <div class="section-hero-text">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class=" align-self-center">
                                    <div class="about-thumb bg-white shadow-lg">
                                        <section>
                                            <h2 class="mt-5 mb-4 baloo-2-bold">Registro</h2>

                                            <!-- Función checkboxChanged  -->
                                            <div class="formulario mb-2 ">                                 
                                                <input type="radio" name="btnradio" id="info1" autocomplete="off" checked>
                                                <label class="poppins-regular btn" for="info1">Estudiante</label>

                                                <input type="radio" name="btnradio" id="info2" autocomplete="off">
                                                <label class="poppins-regular btn" for="info2">Tutor</label>
                                            </div>
                                            
                                            <!-- Agregar estudiante php -->
                                            <form action="../agregar/agregar_estudiante.php" method="POST">
                                                <section id="content1">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control mb-3 poppins-light" name="nombre"
                                                            placeholder="Nombre" required autofocus>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control mb-3 poppins-light" name="apellidos"
                                                            placeholder="Apellidos" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="email" class="form-control mb-3 poppins-light" name="correo"
                                                            placeholder="email@uppenjamo.edu.mx" required>
                                                    </div>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control mb-3 poppins-light"
                                                            name="contrasena" id="contrasena" placeholder="contraseña"
                                                            required>
                                                        <div class="input-group-append">
                                                            <button type="button" id="togglePassword"
                                                                class="btn btn-outline-secondary poppins-extralight"
                                                                onclick="togglePasswordVisibility()">Mostrar</button>
                                                        </div>
                                                    </div>
                                                    
                                                    <button type="submit" class="btn btn-purple poppins-light"
                                                        name="ingresar_estudiante">Registrarse</button>
                                                </section>
                                            </form>

                                            <!-- Agregar profesor php -->
                                            <form action="../agregar/agregar_profesor.php" method="POST">
                                                <section id="content2" style="display: none;" >
                                                    <div class="form-group">
                                                        <input type="text" class="form-control mb-3 poppins-light" name="nombre"
                                                            placeholder="nombre" required utofocus>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" class="form-control mb-3 poppins-light" name="apellidos"
                                                            placeholder="apellidos" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="email" class="form-control mb-3 poppins-light" name="correo"
                                                            placeholder="email@uppenjamo.edu.mx" required>
                                                    </div>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control mb-3 poppins-light" 
                                                            id="contrasena2" name="contrasena" placeholder="contraseña"
                                                            required>
                                                        <div class="input-group-append">
                                                            <button type="button" id="togglePassword2" class="btn btn-outline-secondary poppins-extralight"
                                                                onclick="togglePasswordVisibility2()">Mostrar</button>
                                                        </div>
                                                    </div>


                                                    
                                                    <button type="submit" class="btn btn-purple poppins-light"
                                                        name="ingresar_profesor">Registrarse</button>
                                                </section>
                                            </form>

                                            <?php

                                            session_start();
                                            if (isset($_SESSION['message'])) {
                                                echo '<div class="mensaje">' . $_SESSION['message'] . '</div>';
                                                unset($_SESSION['message']);
                                            }
                                            ?>

                                            <p class="mt-3 poppins-medium">¿Ya tienes una cuenta? <a href="login.php">Inicia Sesión</a>
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

    <script src='../public/functions.js'></script>
</body>

</html>