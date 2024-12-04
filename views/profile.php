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
$sqlTutor = "SELECT descripcion FROM tutores WHERE id_tutor = ?";
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
    <title>Perfil del Tutor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-body">
                <!-- Nombre del Tutor -->
                <h1 class="card-title text-center mb-4" id="tutor-nombre">
                    <?= htmlspecialchars($_SESSION['user_nombre']) ?></h1>

                <!-- Biografía -->
                <div class="mb-4">
                    <h4>Biografía</h4>
                    <p id="biografia" class="bg-light p-3 rounded">
                        <?= htmlspecialchars($tutorData['descripcion'] ?? 'No hay biografía escrita aún.') ?></p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalBiografia">Editar
                        Biografía</button>
                </div>

                <!-- Niveles Académicos -->
                <div class="mb-4">
                    <h4>Niveles Académicos</h4>
                    <ul id="niveles-academicos" class="list-group">
                        <?php foreach ($nivelesDelTutor as $nivel): ?>
                            <li class="list-group-item"><?= htmlspecialchars($nivel['nombre_nivel_academico']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#modalNiveles">Añadir
                        Nivel Académico</button>
                </div>

                <!-- Materias -->
                <div class="mb-4">
                    <h4>Materias</h4>
                    <ul id="materias" class="list-group">
                        <?php foreach ($materiasDelTutor as $materia): ?>
                            <li class="list-group-item"><?= htmlspecialchars($materia['nombre_materia']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#modalMaterias">Añadir
                        Materias</button>
                </div>

                <div class="mb-4">
                    <h4>Disponibilidad</h4>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Día</th>
                                <th>Hora de Inicio</th>
                                <th>Hora de Fin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($disponibilidades as $disp): ?>
                                <tr>
                                    <td><?= htmlspecialchars($disp['dia_semana']) ?></td>
                                    <td><?= htmlspecialchars($disp['hora_inicio']) ?></td>
                                    <td><?= htmlspecialchars($disp['hora_fin']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button class="btn btn-primary mt-2" data-bs-toggle="modal"
                        data-bs-target="#modalDisponibilidad">Agregar Horario</button>
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
                        <textarea class="form-control" name="descripcion"
                            rows="5"><?= htmlspecialchars($tutorData['descripcion'] ?? '') ?></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
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
                                <input class="form-check-input" type="checkbox" name="niveles[]"
                                    value="<?= $nivel['id_nivel_academico'] ?>">
                                <label
                                    class="form-check-label"><?= htmlspecialchars($nivel['nombre_nivel_academico']) ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
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
                                <input class="form-check-input" type="checkbox" name="materias[]"
                                    value="<?= $materia['id_materia'] ?>">
                                <label class="form-check-label"><?= htmlspecialchars($materia['nombre_materia']) ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>





    <!-- Modal Disponibilidad -->
    <!-- Modal Disponibilidad -->
    <div class="modal fade" id="modalDisponibilidad" tabindex="-1" aria-labelledby="modalDisponibilidadLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="../add/agregar_disponibilidad.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDisponibilidadLabel">Agregar Disponibilidad</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Días de la semana -->
                        <h6>Días disponibles</h6>
                        <?php
                        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                        foreach ($diasSemana as $dia): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dias_semana[]" value="<?= $dia ?>">
                                <label class="form-check-label"><?= $dia ?></label>
                            </div>
                        <?php endforeach; ?>

                        <!-- Hora de inicio -->
                        <h6 class="mt-3">Hora de Inicio</h6>
                        <div class="form-group">
                            <input type="time" class="form-control" name="hora_inicio" step="3600" required>
                        </div>

                        <!-- Hora de fin -->
                        <h6 class="mt-3">Hora de Fin</h6>
                        <div class="form-group">
                            <input type="time" class="form-control" name="hora_fin" step="3600" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>