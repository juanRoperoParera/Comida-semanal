<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comida semanal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/estilo/style.css?v=2">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Varela+Round&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz@6..12&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png">
    <script type="text/javascript" src="js/app.js" defer></script>
</head>

<body>
    <?php
    require_once("bd/bd.php");
    require_once("php/funciones.php");
    $con = conectar::conexion();

    navIndex();
    ?>
    <main>
        <div class="container-xl calendario">
            <h1>CALENDARIO</h1>
            <?php


            if (isset($_GET['mes']) && isset($_GET['anio'])) {
                $mes = (int)$_GET['mes'];
                $anio = (int)$_GET['anio'];
            } else {
                $mes = date('n');
                $anio = date('Y');
            }
            echo "<div class='alreves'>";
            echo "<div>";
            echo '<a class="boton" href="?mes=' . ($mes - 1) . '&anio=' . $anio . '"><i class="fa-solid fa-arrow-left"></i></a>';
            echo '<a class="boton" href="?mes=' . ($mes + 1) . '&anio=' . $anio . '"><i class="fa-solid fa-arrow-right"></i></a>';
            echo "</div>";
            mostrarCalendario($mes, $anio);

            echo "<div class='infoDia'>";
            if (isset($_GET['dia'])) {
                $dia = $_GET['dia'];
                if ($mes < 10 && $dia < 10) {
                    echo "<h3>Comida para el 0$dia/0$mes/$anio</h3>";
                } elseif ($mes < 10) {
                    echo "<h3>Comida para el $dia/0$mes/$anio</h3>";
                } elseif ($dia < 10) {
                    echo "<h3>Comida para el 0$dia/$mes/$anio</h3>";
                } else {
                    echo "<h3>Comida para el $dia/$mes/$anio</h3>";
                }

                $fecha_str = sprintf("%02d/%02d/%04d", $dia, $mes, $anio);
                $fecha = date_create_from_format('d/m/Y', $fecha_str);
                $fechaFormateada = $fecha->format('Y-m-d');

                $datos = "select comida from menu_diario where fecha='$fechaFormateada'";

                $result = $con->query($datos);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {

                        $datos = $con->query("select id, nombre, categoria, foto, estado, descripcion from comida where id=$row[comida]");

                        $j = 0;
                        while ($fila = $datos->fetch_array(MYSQLI_ASSOC)) {
                            $comida[$j]['id'] = $fila['id'];
                            $comida[$j]['nombre'] = $fila['nombre'];
                            $comida[$j]['categoria'] = $fila['categoria'];
                            $comida[$j]['foto'] = $fila['foto'];
                            $comida[$j]['estado'] = $fila['estado'];
                            $comida[$j]['descripcion'] = $fila['descripcion'];

                            $j++;
                        }

                        if ($j == 0) {
                            echo "<p>No hay comidas</p>";
                        } else {
                            for ($i = 0; $i < $j; $i++) {
                                $id = $comida[$i]["id"];
                                $nombre = $comida[$i]["nombre"];
                                $categoria = $comida[$i]["categoria"];
                                $foto = $comida[$i]["foto"];
                                $estado = $comida[$i]["estado"];
                                $descripcion = $comida[$i]["descripcion"];

                                echo "
                            <div class='card $categoria dia'>
                                <img src='assets/img/comidas/$foto' class='card-img-top' alt='...'>
                                <div class='card-body'>
                                    <h5 class='card-title'>$nombre</h5>
                                    <p class='card-text'>$descripcion</p>
                                </div>
                            </div>
                        ";
                            }
                        }
                    }
                } else {
                    echo "<p>No hay comida para ese dia</p>";
                }
            } else {
                $hoy = date('j');
                $mesActual = date('n');

                if ($mes == $mesActual) {
                    echo "<h3>Comida para hoy</h3>";

                    $fecha_str = sprintf("%02d/%02d/%04d", $hoy, $mes, $anio);
                    $fecha = date_create_from_format('d/m/Y', $fecha_str);
                    $fechaFormateada = $fecha->format('Y-m-d');

                    $datos = "select comida from menu_diario where fecha='$fechaFormateada'";

                    $result = $con->query($datos);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $datos = $con->query("select id, nombre, categoria, foto, estado, descripcion from comida where id=$row[comida]");

                            $j = 0;
                            while ($fila = $datos->fetch_array(MYSQLI_ASSOC)) {
                                $comida[$j]['id'] = $fila['id'];
                                $comida[$j]['nombre'] = $fila['nombre'];
                                $comida[$j]['categoria'] = $fila['categoria'];
                                $comida[$j]['foto'] = $fila['foto'];
                                $comida[$j]['estado'] = $fila['estado'];
                                $comida[$j]['descripcion'] = $fila['descripcion']; 

                                $j++;
                            }

                            if ($j == 0) {
                                echo "<p>No hay comidas</p>";
                            } else {
                                for ($i = 0; $i < $j; $i++) {
                                    $id = $comida[$i]["id"];
                                    $nombre = $comida[$i]["nombre"];
                                    $categoria = $comida[$i]["categoria"];
                                    $foto = $comida[$i]["foto"];
                                    $estado = $comida[$i]["estado"];
                                    $descripcion = $comida[$i]["descripcion"];

                                    echo "
                                <div class='card $categoria dia'>
                                    <img src='assets/img/comidas/$foto' class='card-img-top' alt='...'>
                                    <div class='card-body'>
                                        <h5 class='card-title'>$nombre</h5>
                                        <p class='card-text'>$descripcion</p>
                                    </div>
                                </div>
                            ";
                                }
                            }
                        }
                    } else {
                        echo "<p>No hay comida para hoy</p>";
                    }
                }
            }
            echo "<h4>Tiempo de hoy</h4><div class='meteo'>
            </div>";

            echo "</div>";
            ?>
        </div>
    </main>
    <?php
    footer();
    $con->close();
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>

</html>