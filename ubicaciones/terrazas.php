<?php
session_start();
include_once '../db/conexion.php';
if (!isset($_SESSION['nombre_sala'])) {
    echo "<h3>No se ha seleccionado ninguna sala</h3>";
    exit();
}

// Recupera el nombre de la sala de la sesión y obtiene el id de esa sala
$nombre_sala = $_SESSION['nombre_sala'];
$id_sala = '';

try {
    $sql = "SELECT id_sala FROM tbl_sala WHERE nombre_sala = ?";
    $stmt1 = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt1, $sql)) {
        mysqli_stmt_bind_param($stmt1, "s", $nombre_sala); 
        mysqli_stmt_execute($stmt1);
        $result = mysqli_stmt_get_result($stmt1);
        $sala = mysqli_fetch_assoc($result);
        $id_sala = $sala['id_sala'];
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
 // Se cierra la consulta
 mysqli_stmt_close($stmt1);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lora:wght@400;500&display=swap" rel="stylesheet">
    <title>Gestión de Mesas</title>
    <style>
        /* Estilos CSS */
        * {
            box-sizing: border-box;
        }

        .container {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
        }

        .container-dentro {
            width: 90%;
            height: 90%;
            background-color: #ccc;
            padding: 20px;
            overflow-y: auto;
            border-radius: 8px;
        }

        .titulo {
            font-family: 'Playfair Display', serif;
            text-align: center;
            margin-bottom: 20px;
        }

        .card-mesa {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .mesa {
            background-color: rgb(3, 252, 40);
            margin: 10px;
            padding: 15px;
            border-radius: 5px;
            width: calc(33% - 20px); 
            box-sizing: border-box;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .mesa:hover {
            background-color: rgb(0, 255, 0); 
        }

        .mesa h2 {
            margin: 0;
            font-size: 18px;
        }

        .mesa p {
            font-size: 16px;
            margin: 10px 0;
        }

        .mesa input[type="submit"] {
            background-color: #333;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%; 
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .mesa input[type="submit"]:hover {
            background-color: #555;
        }

        /* Falta responsive */
       
    </style>
</head>

<body>
    <div class="container">
        <div class="container-dentro">
            <h1 class="titulo">Gestionar Mesa de la Sala: <?php echo htmlspecialchars($_SESSION['nombre_sala']); ?></h1>
            <div class="card-mesa">
                <?php
                // Consulta para obtener las mesas y el número de sillas de cada una en la sala
                $queryMesas = "SELECT id_mesa, num_sillas_mesa, estado_mesa FROM tbl_mesa WHERE id_sala = ?";
                $stmt2 = mysqli_prepare($conn, $queryMesas);
                mysqli_stmt_bind_param($stmt2, "i", $id_sala);
                mysqli_stmt_execute($stmt2);
                $result = mysqli_stmt_get_result($stmt2);

                // Verificar si hay mesas en la sala y mostrarlas
                if (mysqli_num_rows($result) > 0) {
                    while ($mesa = mysqli_fetch_assoc($result)) {
                        echo "<div class='mesa'>";
                        echo "<h2>Mesa: " . htmlspecialchars($mesa['id_mesa']) . "</h2>";
                        echo "<p>Número de sillas: " . htmlspecialchars($mesa['num_sillas_mesa']) . "</p>";
                        echo "<input type='submit' value='" . ucfirst(htmlspecialchars($mesa['estado_mesa'])) . "' name='estado_mesa' id='estado_mesa'>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>Error! No se ha seleccionado ninguna mesa previamente.</p>";
                }
                // Se cierra la consulta
                mysqli_stmt_close($stmt2);
                ?>
            </div>
        </div>
    </div>
</body>
</html>