<?php

function navIndex(){
    echo "<header class='header'>
    <a href='#' class='logo'><img src='assets/img/logo.png' alt='logo'></a>
    <input class='menu-btn' type='checkbox' id='menu-btn' />
    <label class='menu-icon' for='menu-btn'><span class='navicon'></span></label>
    <ul class='menu'>
        <li><a href='#'>CALENDARIO</a></li>
        <li><a href='php/gestioncomidas/gestioncomidas.php'>COMIDAS</a></li>
    </ul>
</header>";
}
function nav(){
    echo "<header class='header'>
    <a href='../../index.php' class='logo'><img src='../../assets/img/logo.png' alt='logo'></a>
    <input class='menu-btn' type='checkbox' id='menu-btn' />
    <label class='menu-icon' for='menu-btn'><span class='navicon'></span></label>
    <ul class='menu'>
        <li><a href='../../index.php'>CALENDARIO</a></li>
        <li><a href='#'>COMIDAS</a></li>
    </ul>
</header>";
}

function footer(){
    echo "    <footer>
    <p>Comida Semanal</p>
</footer>";
}

function mostrarCalendario($mes, $anio)
{
    $primerDiaMes = new DateTime("$anio-$mes-01");
    $ultimoDiaMes = new DateTime("$anio-$mes-01");
    $ultimoDiaMes->modify('last day of this month');

    $diasSemana = array('Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom');
    $meses = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

    echo '<h2>' . $meses[$mes] . ' ' . $anio . '</h2>';
    echo "</div>";
    echo '<table>';
    echo '<tr>';
    foreach ($diasSemana as $dia) {
        echo '<th>' . $dia . '</th>';
    }
    echo '</tr>';

    $diaActual = 1;
    $diaSemana = ($primerDiaMes->format('N') + 6) % 7; // Ajustamos el día de inicio para que sea lunes
    $diasEnMes = $ultimoDiaMes->format('j');
    $hoy = date('j');
    $mesActual = date('n');
    if (isset($_GET['dia'])) {
        $seleccionado = $_GET['dia'];
    }


    for ($fila = 0; $fila < 6; $fila++) {
        echo '<tr>';
        for ($col = 0; $col < 7; $col++) {
            if ($fila === 0 && $col < $diaSemana) {
                echo '<td></td>';
            } else {
                if ($diaActual <= $diasEnMes) {
                    if (isset($_GET['dia']) && $diaActual == $seleccionado) {
                        echo "<td class='seleccionado'>  
                                <a href='index.php?mes=$mes&anio=$anio&dia=$diaActual'>$diaActual </a>
                             </td>";
                    } elseif ($diaActual   == $hoy && $mesActual == $mes) {
                        echo "<td class='marcado'>  
                                <a href='index.php?mes=$mes&anio=$anio&dia=$diaActual'>$diaActual </a>
                             </td>";
                    } else {
                        echo "<td>  
                                <a href='index.php?mes=$mes&anio=$anio&dia=$diaActual'>$diaActual </a>
                            </td>";
                    }

                    $diaActual++;
                } else {
                    echo '<td></td>';
                }
            }
        }
        echo '</tr>';
        if ($diaActual > $diasEnMes) {
            break;
        }
    }

    echo '</table>';
}

function generarSemana()
{
    $con = conectar::conexion();

    $pescado = [];
    $carne = [];
    $pasta = [];
    $ensalada = [];
    $datos = $con->query("select id, nombre, categoria, foto, estado from comida");

    $j = 0;
    while ($fila = $datos->fetch_array(MYSQLI_ASSOC)) {
        $id = $fila['id'];
        $categoria = $fila['categoria'];
        $estado = $fila['estado'];

        if ($estado > 0) {
            if ($categoria == "Pescado") {
                array_push($pescado, $id);
            } elseif ($categoria == "Carne") {
                array_push($carne, $id);
            } elseif ($categoria == "Pasta") {
                array_push($pasta, $id);
            } elseif ($categoria == "Ensalada") {
                array_push($ensalada, $id);
            }
        }
        $j++;
    }

    $numeroPescado =  mt_rand(0, count($pescado) - 1);
    $aleatorioPescado = $pescado[$numeroPescado];

    $numeroCarne =  mt_rand(0, count($carne) - 1);
    $aleatorioCarne1 = $carne[$numeroCarne];

    do{
        $numeroCarne2 =  mt_rand(0, count($carne) - 1);
    }while($numeroCarne2 == $numeroCarne);
    $aleatorioCarne2 = $carne[$numeroCarne2];
    
    $numeroPasta =  mt_rand(0, count($pasta) - 1);
    $aleatorioPasta = $pasta[$numeroPasta];

    $numeroEnsalada =  mt_rand(0, count($ensalada) - 1);
    $aleatorioEnsalada1 = $ensalada[$numeroEnsalada];

    do{
        $numeroEnsalada2 =  mt_rand(0, count($ensalada) - 1);
    }while($numeroEnsalada2 == $numeroEnsalada);
    $aleatorioEnsalada2 = $ensalada[$numeroEnsalada2];

    $semanaResultante = array($aleatorioPescado,$aleatorioCarne1,$aleatorioCarne2,$aleatorioPasta,$aleatorioEnsalada1);
    shuffle($semanaResultante);

    return $semanaResultante;
}

function insertar_comidas_mes($numeroMes,$anio){
    $con = conectar::conexion();
    $fechaBusqueda = date("Y-m", strtotime("$anio-$numeroMes"));
    $datos = $con->query("select count(*) numero from menu_diario where DATE_FORMAT(fecha, '%Y-%m') = '$fechaBusqueda'");
    $fila = $datos->fetch_array(MYSQLI_ASSOC);
    $numero = $fila['numero'];
    
    if($numero == 0){
        $numeroDias = cal_days_in_month(CAL_GREGORIAN, $numeroMes, $anio);

        $semanaResultante = generarSemana();
        $j=0;
    
        for($i=1; $i<=$numeroDias; $i++){
            $finde=false;
            $fecha = date("Y-m-d", strtotime("$anio-$numeroMes-$i"));
            $nombreDia = date("l", strtotime($fecha));
            if($nombreDia == "Saturday" || $nombreDia == "Sunday"){
                $semanaResultante = generarSemana();
                $j=-1;
                $finde = true;
            }
            if($finde !== true){
                $insert = "INSERT INTO menu_diario (fecha,comida) VALUES ('$fecha','$semanaResultante[$j]')";
                $datos = $con->query($insert);
            }      
            $j++;
        }
    }  
}
