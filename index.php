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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

            // Obtener la fecha de hoy
            $hoy = new DateTime();

            // Obtener el primer día de la semana actual
            $inicioSemanaActual = new DateTime('monday this week');

            // Obtener el primer día de la semana siguiente
            $inicioSiguienteSemana = clone $inicioSemanaActual;
            $inicioSiguienteSemana->modify('+1 week');

            // Crear la tabla del calendario
            echo '<table>';
            echo '<tr><th>Lunes</th><th>Martes</th><th>Miércoles</th><th>Jueves</th><th>Viernes</th><th>Sábado</th><th>Domingo</th></tr>';

            // Iterar sobre los días de la semana actual y la siguiente
            for ($i = 0; $i < 14; $i++) {

                // Obtener la fecha actual en formato de día, mes y año
                $fechaActual = $inicioSemanaActual->format('Y-m-d');
                $dia = $inicioSemanaActual->format('d');

                $datos = "select comida from menu_diario where fecha='$fechaActual'";

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

                        // Comprobar si es el día de hoy
                        $claseDia = ($inicioSemanaActual->format('Y-m-d') == $hoy->format('Y-m-d')) ? 'hoy' : '';

                        // Imprimir el día en la tabla con la clase correspondiente
                        echo "<td data-dia=$dia class='$claseDia'></td>";

                        // Avanzar al siguiente día
                        $inicioSemanaActual->modify('+1 day');

                        // Si es domingo, cerrar la fila de la tabla
                        if ($i % 7 == 6) {
                            echo '</tr>';
                        }
                    }
                } else {
                    // Comprobar si es el día de hoy
                    $claseDia = ($inicioSemanaActual->format('Y-m-d') == $hoy->format('Y-m-d')) ? 'hoy' : '';
                    // Imprimir el día en la tabla con la clase correspondiente
                    echo "<td data-dia=$dia class='$claseDia'></td>";

                    // Avanzar al siguiente día
                    $inicioSemanaActual->modify('+1 day');

                    // Si es domingo, cerrar la fila de la tabla
                    if ($i % 7 == 6) {
                        echo '</tr>';
                    }
                }
            }
            echo '</table>';
            echo "<a class='boton' href='index.php?generarSemana=1'>Generar/Modificar semanas</a>";
            echo "<a class='boton guardar' href='index.php?guardar=1'>Guardar</a>";

            if (isset($_GET['generarSemana'])) {
                $datos = $con->query("select id, nombre, categoria, foto, estado from comida");

                $j = 0;
                echo "<div class='seleccionComidas'>";
                while ($fila = $datos->fetch_array(MYSQLI_ASSOC)) {
                    $comida[$j]['id'] = $fila['id'];
                    $comida[$j]['nombre'] = $fila['nombre'];
                    $comida[$j]['categoria'] = $fila['categoria'];
                    $comida[$j]['foto'] = $fila['foto'];
                    $comida[$j]['estado'] = $fila['estado'];

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

                        echo "
                            <div class='comida $categoria' id='$id''>
                                <a class='boton' href=''>+</a>
                                <img src='assets/img/comidas/$foto' class='card-img-top' alt='comida'>
                                <div class='card-body'>
                                    <h5 class='card-title'>$nombre</h5>
                                </div>
                            </div>";
                    }
                }
                echo "</div>";
            }

            if (isset($_GET['guardar'])) {
            }


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