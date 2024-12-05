<?php
session_start();
require_once('../config/dbconn.php');

// Verificar si el usuario está logueado y es estudiante
if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'estudiante') {
    header('Location: login.php');
    exit();
}

// Conexión a la base de datos
$conn = (new Connection())->open();

// Obtener el término de búsqueda si existe
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$materiasFilter = isset($_GET['materias']) ? $_GET['materias'] : [];
$calificacionFilter = isset($_GET['calificacion']) ? $_GET['calificacion'] : '';
$diasFilter = isset($_GET['dias']) ? $_GET['dias'] : [];
$horaFilter = isset($_GET['hora']) ? $_GET['hora'] : '';

try {
    // Base SQL para tutores
    $sqlTutores = "
        SELECT 
            u.id_usuario, 
            u.nombre, 
            u.apellidos, 
            t.calificacion_promedio
        FROM usuarios u
        JOIN tutores t ON u.id_usuario = t.id_tutor
        WHERE u.rol = 'tutor'";

    // Agregar filtro de búsqueda si se ingresó un término
    if ($search) {
        $sqlTutores .= " AND (u.nombre LIKE :search OR u.apellidos LIKE :search)";
    }

    // Filtrar por materias
    if (!empty($materiasFilter)) {
        $sqlTutores .= " AND u.id_usuario IN (
            SELECT id_tutor FROM tutoresmaterias tm
            JOIN materias m ON tm.id_materia = m.id_materia
            WHERE m.nombre_materia IN (" . implode(",", array_map(function($materia) use ($conn) {
            return $conn->quote($materia); }, $materiasFilter)) . ")
        )";
    }

    // Filtrar por calificación
    if ($calificacionFilter) {
        $sqlTutores .= " AND t.calificacion_promedio >= :calificacion";
    }

    // Filtrar por días disponibles
    if (!empty($diasFilter)) {
        $sqlTutores .= " AND u.id_usuario IN (
            SELECT id_tutor FROM disponibilidad d
            WHERE d.dia_semana IN (" . implode(",", array_map(function($dia) use ($conn) {
            return $conn->quote($dia); }, $diasFilter)) . ")
        )";
    }

    // Preparar la consulta de tutores
    $stmtTutores = $conn->prepare($sqlTutores);

    // Vincular los parámetros
    if ($search) {
        $stmtTutores->bindValue(':search', "%$search%");
    }
    if ($calificacionFilter) {
        $stmtTutores->bindValue(':calificacion', $calificacionFilter);
    }
    $stmtTutores->execute();
    $tutores = $stmtTutores->fetchAll(PDO::FETCH_ASSOC);

    // Obtener las materias de cada tutor
    $sqlMaterias = "
        SELECT 
            tm.id_tutor, 
            m.nombre_materia
        FROM tutoresmaterias tm
        JOIN materias m ON tm.id_materia = m.id_materia";
    $stmtMaterias = $conn->prepare($sqlMaterias);
    $stmtMaterias->execute();
    $materias = $stmtMaterias->fetchAll(PDO::FETCH_ASSOC);

    // Obtener la disponibilidad de cada tutor
    $sqlDisponibilidad = "
        SELECT 
            d.id_tutor, 
            GROUP_CONCAT(DISTINCT d.dia_semana ORDER BY FIELD(d.dia_semana, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo')) AS dias
        FROM disponibilidad d
        GROUP BY d.id_tutor";
    $stmtDisponibilidad = $conn->prepare($sqlDisponibilidad);
    $stmtDisponibilidad->execute();
    $disponibilidad = $stmtDisponibilidad->fetchAll(PDO::FETCH_ASSOC);

    // Organizar materias y disponibilidad por tutor ID
    $materiasPorTutor = [];
    foreach ($materias as $materia) {
        $materiasPorTutor[$materia['id_tutor']][] = $materia['nombre_materia'];
    }

    $disponibilidadPorTutor = [];
    foreach ($disponibilidad as $disp) {
        $disponibilidadPorTutor[$disp['id_tutor']] = $disp['dias'];
    }
} catch (PDOException $e) {
    die("Error al obtener datos: " . $e->getMessage());
}

$conn = null;
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disponibilidad de Tutores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Disponibilidad de Tutores</h1>

    <!-- Barra de búsqueda -->
    <form class="mb-4" method="GET" action="">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o apellidos" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </div>
    </form>
    <!-- Formulario de filtros -->
<form id="filterForm" method="GET">
    <div class="row">
        <!-- Materias -->
        <div class="col-md-6 mb-3">
            <h6>Materias</h6>
            <div class="scrollable-checkboxes">
                <?php foreach ($materias as $materia): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="materias[]" value="<?= $materia['nombre_materia'] ?>" <?= in_array($materia['nombre_materia'], $materiasFilter) ? 'checked' : '' ?>>
                        <label class="form-check-label"><?= htmlspecialchars($materia['nombre_materia']) ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Calificación mínima -->
        <div class="col-md-6 mb-3">
            <h6>Calificación mínima</h6>
            <select class="form-select" name="calificacion">
                <option value="" selected>Seleccione...</option>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <option value="<?= $i ?>" <?= $calificacionFilter == $i ? 'selected' : '' ?>><?= $i ?> o más</option>
                <?php endfor; ?>
            </select>
        </div>

        <!-- Días disponibles -->
        <div class="col-md-6 mb-3">
            <h6>Días disponibles</h6>
            <?php foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="dias[]" value="<?= $dia ?>" <?= in_array($dia, $diasFilter) ? 'checked' : '' ?>>
                    <label class="form-check-label"><?= $dia ?></label>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Hora mínima -->
        <div class="col-md-6 mb-3">
            <h6>Hora mínima</h6>
            <input type="time" class="form-control" name="hora" value="<?= htmlspecialchars($horaFilter) ?>">
        </div>
    </div>

    <button type="submit" class="btn btn-success">Aplicar Filtros</button>
</form>

     

    <div class="row">
        <?php if ($tutores): ?>
            <?php foreach ($tutores as $tutor): ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <!-- Nombre del Tutor -->
                            <h4 class="card-title"><?= htmlspecialchars($tutor['nombre'] . ' ' . $tutor['apellidos']) ?></h4>
                            
                            <!-- Calificación -->
                            <p class="mb-2">Calificación: <?= number_format($tutor['calificacion_promedio'], 1) ?>/5</p>
                            
                            <!-- Materias -->
                            <h6>Materias:</h6>
                            <p>
                                <?php
                                $materiasTutor = $materiasPorTutor[$tutor['id_usuario']] ?? [];
                                for ($i = 0; $i < min(3, count($materiasTutor)); $i++) {
                                    echo htmlspecialchars($materiasTutor[$i]);
                                    if ($i < 2 && $i < count($materiasTutor) - 1) {
                                        echo ', ';
                                    }
                                }
                                if (count($materiasTutor) > 3) {
                                    echo '...';
                                }
                                ?>
                            </p>
                            
                            <!-- Disponibilidad -->
                            <h6>Días disponibles:</h6>
                            <p>
                                <?= htmlspecialchars($disponibilidadPorTutor[$tutor['id_usuario']] ?? 'No disponible') ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No se encontraron tutores que coincidan con el término de búsqueda.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>