<?php
session_start();
require_once('../config/dbconn.php');

// Verificar si el usuario está logueado y es tutor
if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'tutor') {
    header('Location: login.php');
    exit();
}

// Conexión a la base de datos
$conn = (new Connection())->open();

// Obtener los datos del tutor logueado
$tutorId = $_SESSION['user_id'];

// Obtener descripción del tutor
$sqlTutor = "SELECT descripcion, calificacion_promedio FROM tutores WHERE id_tutor = ?";
$stmtTutor = $conn->prepare($sqlTutor);
$stmtTutor->execute([$tutorId]);
$tutorData = $stmtTutor->fetch();

// Obtener niveles académicos disponibles
$sqlNiveles = "SELECT * FROM nivel_academico";
$stmtNiveles = $conn->prepare($sqlNiveles);
$stmtNiveles->execute();
$niveles = $stmtNiveles->fetchAll(PDO::FETCH_ASSOC);

// Obtener materias disponibles
$sqlMaterias = "SELECT * FROM materias";
$stmtMaterias = $conn->prepare($sqlMaterias);
$stmtMaterias->execute();
$materias = $stmtMaterias->fetchAll(PDO::FETCH_ASSOC);

// Obtener los niveles académicos asociados al tutor
$sqlTutorNiveles = "SELECT n.id_nivel_academico, n.nombre_nivel_academico 
                    FROM tutoresnivelesacademicos tn
                    JOIN nivel_academico n ON tn.id_nivel_academico = n.id_nivel_academico
                    WHERE tn.id_tutor = ?";
$stmtTutorNiveles = $conn->prepare($sqlTutorNiveles);
$stmtTutorNiveles->execute([$tutorId]);
$nivelesDelTutor = $stmtTutorNiveles->fetchAll(PDO::FETCH_ASSOC);

// Obtener las materias asociadas al tutor
$sqlTutorMaterias = "SELECT m.id_materia, m.nombre_materia 
                    FROM tutoresmaterias tm
                    JOIN materias m ON tm.id_materia = m.id_materia
                    WHERE tm.id_tutor = ?";
$stmtTutorMaterias = $conn->prepare($sqlTutorMaterias);
$stmtTutorMaterias->execute([$tutorId]);
$materiasDelTutor = $stmtTutorMaterias->fetchAll(PDO::FETCH_ASSOC);

// Obtener las dias de la semana y horas
$sqlDisponibilidad = "SELECT dia_semana, hora_inicio, hora_fin 
                    FROM disponibilidad 
                    WHERE id_tutor = ?";
$stmtDisponibilidad = $conn->prepare($sqlDisponibilidad);
$stmtDisponibilidad->execute([$tutorId]);
$disponibilidades = $stmtDisponibilidad->fetchAll(PDO::FETCH_ASSOC);

// Cerrar conexión
$conn = null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
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
    <!--Comentario-->
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container-sm">
            <a class="parkinsans-700 text-white h2 mt-2" aria-current="page"
                href="#">Cognitio</a>

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
                            <a class="nav-link montserrat-600 text-white" aria-current="page" href="#">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link montserrat-600 text-white" href="#servicios">Servicios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link montserrat-600 text-white" href="#funcionamiento">Guía</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link montserrat-600 text-white" href="#testimonios">Historias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link montserrat-600 text-white" href="./login.php">Inicia Sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link montserrat-600 text-white" href="./register.php">Regístrate</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="card shadow-lg p-4 border">
            <div class="row">
                <!-- Nombre del Tutor -->
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <h1 class="card-title montserrat-700">
                            <?= htmlspecialchars($_SESSION['user_nombre']) ?>
                        </h1>
                        
                    </div>
                    <h2 class="card-title montserrat-700">
                            <?= htmlspecialchars($_SESSION['user_apellidos']) ?>
                    </h2>
                    <!-- Calificación del tutor -->
                    <div class="rating-text">
                        Calificación: <?= number_format($tutorData['calificacion_promedio'], 1) ?>/5
                    </div>
                </div>

                <!-- Biografía -->
                <div class="col-md-6">
                    <div class="mb-4">
                        <p class="p-3 rounded border">
                            <?= htmlspecialchars($tutorData['descripcion'] ?? 'No hay biografía escrita aún.') ?>
                        </p>
                        <button class="btn btn-orange" data-bs-toggle="modal" data-bs-target="#modalBiografia">
                            Editar Biografía
                        </button>
                    </div>
                </div>

                <!-- Niveles Académicos -->
                <div class="col-md-6">
                    <div class="mb-4">
                        <h4>Nivel</h4>
                        <div class="d-flex gap-2">
                            <?php foreach ($nivelesDelTutor as $nivel): ?>
                                <span class="border px-2 py-1 rounded"><?= htmlspecialchars($nivel['nombre_nivel_academico']) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <button class="btn btn-green mt-3" data-bs-toggle="modal" data-bs-target="#modalNiveles">
                            Añadir Nivel Académico
                        </button>
                    </div>
                </div>

                <!-- Materias -->
                <div class="col-md-6">
                <div class="mb-4">
                        <h4>Materias</h4>
                        <div class="d-flex gap-2 flex-wrap">
                            <?php foreach ($materiasDelTutor as $materia): ?>
                                <span class="border px-2 py-1 rounded"><?= htmlspecialchars($materia['nombre_materia']) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <button class="btn btn-green mt-3" data-bs-toggle="modal" data-bs-target="#modalMaterias">
                            Añadir Materias
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Modal Biografía -->
    <div class="modal fade" id="modalBiografia" tabindex="-1" aria-labelledby="modalBiografiaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="../add/agregar_biografia.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalBiografiaLabel">Editar Biografía</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <textarea class="form-control" name="descripcion" rows="5"><?= htmlspecialchars($tutorData['descripcion'] ?? '') ?></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-green">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Niveles Académicos -->
    <div class="modal fade" id="modalNiveles" tabindex="-1" aria-labelledby="modalNivelesLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="../add/agregar_niveles.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalNivelesLabel">Añadir Nivel Académico</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php foreach ($niveles as $nivel): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="niveles[]" value="<?= $nivel['id_nivel_academico'] ?>">
                                <label class="form-check-label"><?= htmlspecialchars($nivel['nombre_nivel_academico']) ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-green">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Materias -->
    <div class="modal fade" id="modalMaterias" tabindex="-1" aria-labelledby="modalMateriasLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="../add/agregar_materias.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalMateriasLabel">Añadir Materias</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php foreach ($materias as $materia): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="materias[]" value="<?= $materia['id_materia'] ?>">
                                <label class="form-check-label"><?= htmlspecialchars($materia['nombre_materia']) ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-green">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script
        src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script
        src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../script.js"></script>
    <script src="../public/funciones.js"></script>
</body>

</html>
