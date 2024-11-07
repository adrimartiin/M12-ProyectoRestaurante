<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

include_once '../actions/gestion_comedor.php';

$errorEnFormulario = isset($error) && $error !== '';

// Ordenar las mesas de menor a mayor
usort($mesasDisponibles, function($a, $b) {
    return $a['num_sillas_mesa'] - $b['num_sillas_mesa'];
});
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesas Disponibles</title>
    <link rel="stylesheet" href="../css/mesas_comedor.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="mesas-container">
        <?php if (!empty($mesasDisponibles)): ?>
            <?php foreach ($mesasDisponibles as $mesa): ?>
                <div class="mesa <?php echo $mesa['estado_mesa'] === 'libre' ? 'libre' : 'ocupada'; ?>" onclick="openPopup(<?php echo $mesa['id_mesa']; ?>)">
                    <i class="fas fa-table"></i>
                    <p><?php echo htmlspecialchars($mesa['num_sillas_mesa']); ?> Sillas</p>
                    <div class="sillas">
                        <?php for ($i = 0; $i < $mesa['num_sillas_mesa']; $i++): ?>
                            <i class="fas fa-chair" style="color: <?php echo $mesa['estado_mesa'] === 'libre' ? 'green' : 'red'; ?>;"></i>
                        <?php endfor; ?>
                    </div>
                    <?php if ($mesa['estado_mesa'] === 'ocupada'): ?>
                        <button class="desocupar" onclick="confirmDesocupar(<?php echo $mesa['id_mesa']; ?>)">Desocupar</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay mesas disponibles para mostrar.</p>
        <?php endif; ?>
    </div>

    <div id="popup" class="popup" style="display: <?php echo $errorEnFormulario ? 'block' : 'none'; ?>;">
        <button class="close-btn" onclick="closePopup()">
            <i class="fas fa-times"></i>
        </button>
        <br><br>
        <form method="post" id="form-ocupar">
            <input type="hidden" name="id_mesa" id="mesa_id">
            <input type="text" name="nombre_cliente" placeholder="Nombre del Cliente" required>
            <input type="number" name="num_personas" placeholder="Número de Personas" min="1" required>
            <button type="submit" name="accion" value="ocupar">Ocupar</button>
        </form>
        <?php if ($errorEnFormulario): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>

    <div id="overlay" class="overlay" style="display: <?php echo $errorEnFormulario ? 'block' : 'none'; ?>;"></div>

    <script src="../js/form_modal.js"></script>
    <script src="../js/sweet_alert.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
