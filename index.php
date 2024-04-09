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
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
            <h1>Planificacion de dietas</h1>
            <?php

            if (isset($_GET['codigo']) && $_GET['codigo']!=='') {
                // Obtener la fecha de hoy
                setlocale(LC_TIME, 'spanish');
                $hoy = new DateTime();

                // Obtener el primer día de la semana actual
                $inicioSemanaActual = new DateTime('monday this week');

                $inicioSiguienteSemana = clone $inicioSemanaActual;
                $inicioSiguienteSemana->modify('+1 week');

                // Crear la tabla del calendario
                echo '<table>';
                echo '<tr><th>Lunes</th><th>Martes</th><th>Miercoles</th><th>Jueves</th><th>Viernes</th><th>Sabado</th><th>Domingo</th></tr>';

                // Iterar sobre los días de la semana actual y la siguiente

                $comidas = 0;
                for ($i = 0; $i < 14; $i++) {

                    // Obtener la fecha actual en formato de día, mes y año
                    $fechaActual = $inicioSemanaActual->format('Y-m-d');
                    $dia = $inicioSemanaActual->format('d');
                    $mes = $inicioSemanaActual->format('m');
                    $ano = $inicioSemanaActual->format('Y');

                    if ($i == 0) {
                        $dias_en_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
                    }

                    if ($i == 0) {
                        $primerDiaSinComida = $dia;
                    }

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
                                for ($k = 0; $k < $j; $k++) {
                                    $id = $comida[$k]["id"];
                                    $nombre = $comida[$k]["nombre"];
                                    $categoria = $comida[$k]["categoria"];
                                    $foto = $comida[$k]["foto"];
                                    $estado = $comida[$k]["estado"];
                                    $descripcion = $comida[$k]["descripcion"];

                                    $descripcionCorta = trim(substr($descripcion, 0, 75)) . "...";
                                    echo "
                                <td>
                                    <div class='comida $categoria'>
                                        <a href='php/gestioncomidas/quitardieta.php?idComida=$id&dia=$dia&mes=$mes&ano=$ano' class='cerrar'>X</a>
                                        <img src='assets/img/comidas/$foto' class='card-img-top' alt='...'>
                                        <i>$dia / $mes / $ano</i>
                                        <div class='card-body'>
                                            <p class='card-text'>$descripcionCorta<a class='' href=''>Ver más</a></p>
                                        </div>
                                    </div>
                                </td>
                        ";
                                }
                                $comidas++;
                            }
                            $primerDiaSinComida = $dia + 1;

                            // Avanzar al siguiente día
                            $inicioSemanaActual->modify('+1 day');

                            // Si es domingo, cerrar la fila de la tabla
                            if ($i % 7 == 6) {
                                echo '</tr>';
                            }
                        }
                    } else {
                        // Imprimir el día en la tabla con la clase correspondiente

                        /*                     if (isset($primerDiaSinComida) && $dia == $primerDiaSinComida) { */
                        if (isset($_GET['seleccionado'])) {
                            if ($_GET[('dia')] == $dia && $_GET[('mes')] == $mes && $_GET[('ano')] == $ano) {
                                $diaAñadir = $_GET[('dia')];
                                $mesAñadir = $_GET[('mes')];
                                $anoAñadir = $_GET[('ano')];
                                echo "<td><a class='añadir seleccionado' href='index.php?generarSemana=1&seleccionado=1&dia=$dia&mes=$mes&ano=$ano'>+</a></td>";
                            } else {
                                echo "<td><a class='añadir' href='index.php?generarSemana=1&seleccionado=1&dia=$dia&mes=$mes&ano=$ano'>+</a></td>";
                            }
                        } else {
                            echo "<td><a class='añadir' href='index.php?generarSemana=1&seleccionado=1&dia=$dia&mes=$mes&ano=$ano'>+</a></td>";
                        }

                        /*  } else {
                        echo "<td></td>";
                    } */

                        // Avanzar al siguiente día
                        $inicioSemanaActual->modify('+1 day');

                        // Si es domingo, cerrar la fila de la tabla
                        if ($i % 7 == 6) {
                            echo '</tr>';
                        }
                    }
                }
                echo '</table>';

                if ($comidas < 14 && isset($_GET['generarSemana'])) {
                    echo "<p>* Selecciona una comida</p>";
                    $datos = $con->query("select id, nombre, categoria, foto, estado from comida");

                    $j = 0;
                    echo "<div class='seleccionComidas' id='1'>";
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

                            if ($primerDiaSinComida >= $dias_en_mes) {
                                $primerDiaSinComida = 1;
                            }
                            echo "
                            <div class='comida $categoria' id='$id''>";
                            echo "
                                <img src='assets/img/comidas/$foto' class='card-img-top' alt='comida'>
                                <div class='card-body'>
                                    <h5 class='card-title'>$nombre</h5>
                                </div>";
                            if (isset($diaAñadir)) {
                                echo "<a class='boton' href='php/gestioncomidas/añadirdieta.php?idComida=$id&dia=$diaAñadir&mes=$mesAñadir&ano=$anoAñadir'>+</a>";
                            }
                            echo "</div>";
                        }
                    }
                    echo "</div>";
                }
            } else {
                echo "<p>* Introduce el codigo del menu semanal</p>";
                echo "<form class='introducirCodigo' action='#' method='GET'>
                    <input type='number' name='codigo' placeholder='EJ: 45324'>
                    <input type='submit' name='buscar' value='Buscar'>
                </form>";
                if(isset($_GET['codigo']) && $_GET['codigo'] == ''){
                    echo "<p class='alerta'><i class='fa-solid fa-triangle-exclamation'></i> Por favor, introduce un código para continuar.</p>";
                }
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