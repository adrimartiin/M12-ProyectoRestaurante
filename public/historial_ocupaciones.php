<?php
session_start();
include_once '../private/search_historial.php';

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Ocupaciones</title>
    <link rel="stylesheet" href="../css/historial.css">
    <link rel="stylesheet" href="../css/gestion_mesas.css">
</head>
<body>
    <div class="navbar">
        <a href="../index.php">
            <img src="../img/icon.png" class="icon" alt="Icono">
        </a>
        <div class="user-info">
            <div class="dropdown">
                <i class="fas fa-caret-down" style="font-size: 16px; margin-right: 10px;"></i>
                <div class="dropdown-content">
                    <a href="../private/logout.php">Cerrar Sesión</a>
                </div>
            </div>
            <span><?php echo $_SESSION['nombre_usuario']; ?></span>
        </div>
    </div>
    <br><br>
    <div class="container">
        <h1>Historial de Ocupaciones</h1>
        <form method="GET" id="filterForm">
            <label for="camarero">Camarero:</label>
            <input type="text" name="camarero" id="camarero" placeholder="Nombre del camarero" 
                value="<?php echo isset($_GET['camarero']) ? htmlspecialchars($_GET['camarero']) : ''; ?>">

            <label for="mesa">Mesa:</label>
            <input type="number" name="mesa" id="mesa" placeholder="ID de la mesa" 
                value="<?php echo isset($_GET['mesa']) ? htmlspecialchars($_GET['mesa']) : ''; ?>">

            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" id="fecha" 
                value="<?php echo isset($_GET['fecha']) ? htmlspecialchars($_GET['fecha']) : ''; ?>">

            <button type="submit">Filtrar</button>
        </form>
        <div id="form_error" class="red"></div>

        <?php if (!empty($_GET) && $noResultsMessage): ?>
            <p class="red"><?php echo $noResultsMessage; ?></p>
        <?php endif; ?>

        <table>
            <tr>
                <th>ID Ocupación</th>
                <th>Camarero</th>
                <th>Mesa</th>
                <th>Fecha Ocupación</th>
                <th>Fecha Desocupación</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_ocupacion']) ?></td>
                    <td><?= htmlspecialchars($row['nombre_camarero']) ?></td>
                    <td><?= htmlspecialchars($row['id_mesa']) ?></td>
                    <td><?= htmlspecialchars($row['fecha_hora_ocupacion']) ?></td>
                    <td><?= htmlspecialchars($row['fecha_hora_desocupacion'] ?? 'Sin desocupar') ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
    <script src="../js/validation_historial.js"></script>
</body>
</html>
