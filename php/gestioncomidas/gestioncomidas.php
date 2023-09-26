<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comida semanal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="../../assets/estilo/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Varela+Round&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz@6..12&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image/x-icon" href="../../assets/img/favicon.png">
</head>

<body>
    <?php
    require_once("../../bd/bd.php");
    require_once("../funciones.php");
    $con = conectar::conexion();

    nav();
    ?>
    <main>
    <section class="container comidas">
        <h1>Comidas</h1>
        <?php
        $datos = $con->query("select id, nombre, categoria, foto, estado from comida");

        $j = 0;
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
                <div class='card $categoria'>
                    <img src='../../assets/img/comidas/$foto' class='card-img-top' alt='comida'>
                    <div class='card-body'>
                        <h5 class='card-title'>$nombre</h5>
                    </div>
                </div>
            ";
            }
        }
        ?>
    </section>
    </main>
    <?php
    footer();
    $con->close();
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>



</html>