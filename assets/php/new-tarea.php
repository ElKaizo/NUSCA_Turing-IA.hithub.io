<?php 
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("location: ../../pages/inicio.html");
    exit();
}

$id_user = $_SESSION['usuario_id'];

// Procesamiento del formulario de inserción de tarea
if (isset($_POST['insertar'])) {
    $name_act = $_POST['name_act'];
    $descripcion = $_POST['descripcion'];
    $tag = $_POST['tag'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $done = $_POST['done'];

    // Verificar que el campo title no esté vacío
    if (!empty($name_act)) {
        require "conexion.php";
        $conn = mysqli_connect($direccionservidor, $usuarioBD, $passBD, $BD);

        // Verificar la conexión
        if (!$conn) {
            die("La conexión falló: " . mysqli_connect_error());
        }

        // Escapar las variables para evitar inyecciones SQL (opcional pero recomendado)
        $name_act = mysqli_real_escape_string($conn, $name_act);
        $descripcion = mysqli_real_escape_string($conn, $descripcion);
        $tag = mysqli_real_escape_string($conn, $tag);
        $fecha_inicio = mysqli_real_escape_string($conn, $fecha_inicio);
        $fecha_fin = mysqli_real_escape_string($conn, $fecha_fin);
        $done = mysqli_real_escape_string($conn, $done);

        // Realizar la inserción en la base de datos
        $query = $conn->query("INSERT INTO task (id_user, name_act, descripcion, tag, fecha_inicio, fecha_fin, done) 
                               VALUES ('$id_user', '$name_act', '$descripcion', '$tag', '$fecha_inicio', '$fecha_fin', '$done')");

        if ($query) {
            mysqli_close($conn);
            // Redirigir después de la inserción a una página adecuada
            header("Location: ../../index.php");
            exit();
        } else {
            echo "Error al insertar la tarea: " . $conn->error;
        }
    } else {
        ?>
        <div class="alert alert-danger mt-3" role="alert">
            Error: El campo título de la tarea no puede estar vacío.
        </div>
        <?php
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva tarea</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
    <link rel="icon" href="../../media/img/icon.jpg" type="image/x-icon">
    <link rel="stylesheet" href="../css/barrasup.css">
    <link rel="stylesheet" href="../css/tamanos.css">
    <link rel="stylesheet" href="../css/barrainf.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/inicio.css">
    <link rel="stylesheet" href="../css/new-tarea.css">
</head>
<body>
    <!--Menu superior-->
    <header class="header" id="header">
        <nav class="nav container">
            <img class="logo" src="../../media/img/logo.png" alt="">
            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <li class="nav__item">
                        <a href="../../index.php" class="nav__link">
                            <i class="ri-arrow-right-up-line"></i>
                            <span>Plataforma</span>
                        </a>
                    </li>
                    <li class="nav__item">
                        <a href="cerrar.php" class="nav__link">
                            <i class="ri-arrow-right-up-line"></i>
                            <span>Cerrar Sesión</span>
                        </a>
                    </li>
                    <li class="nav__item">
                        <a href="perfil.php" class="nav__link">
                            <i class="ri-arrow-right-up-line"></i>
                            <span>perfil</span>
                        </a>
                    </li>
                </ul>
                <div class="nav__close" id="nav-close">
                    <i class="ri-close-large-line"></i>
                </div>
                <div class="nav__social">
                    <a href="https://www.instagram.com/turing.ia_/" target="_blank" class="nav__social-link">
                        <i class="ri-instagram-line"></i>
                    </a>
                    <a href="https://www.facebook.com/turing.mx?locale=es_LA" target="_blank" class="nav__social-link">
                        <i class="ri-facebook-fill"></i>
                    </a>
                    <a href="https://www.turing-ia.com/" target="_blank" class="nav__social-link">
                        <i class="ri-dribbble-line"></i>
                    </a>
                    <a href="https://www.linkedin.com/company/turing-inteligencia-artificial/" target="_blank" class="nav__social-link">
                        <i class="ri-linkedin-box-line"></i>
                    </a>
                </div>
            </div>
            <div class="nav__toggle" id="nav-toggle">
                <i class="ri-menu-line"></i>
            </div>
        </nav>
    </header>
    
    <div class="form-container "> 
        <div class="container">
            <div class="row">
                <form action="" method="POST">

                    <div class="mb-3 mt-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Titulo</label>
                        <textarea class="form-control" name="name_act" id="exampleFormControlTextarea1" rows="3"></textarea>
                    </div>

                    <div class="mb-3 mt-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Descripcion</label>
                        <textarea class="form-control" name="descripcion" id="exampleFormControlTextarea1" rows="3"></textarea>
                    </div>

                    <div class="mb-3 mt-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Etiqueta</label>
                        <textarea class="form-control" name="tag" id="exampleFormControlTextarea1" rows="3"></textarea>
                    </div>

                    <div class="mb-3 mt-3">
                        <label for="fecha_inicio" class="form-label">Fecha inicial</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" value="">
                    </div>

                    <div class="mb-3 mt-3">
                        <label for="fecha_fin" class="form-label">Fecha final</label>
                        <input type="date" id="fecha_fin" name="fecha_fin" value="">
                    </div>

                    <div class="mb-3 mt-3">
                        <label for="done" class="form-label">Estado</label>
                        <select id="done" name="done">
                            <option value="0">En curso</option>
                            <option value="1">Finalizada</option>
                        </select>
                    </div>

                    <button type="submit" name="insertar" class="btn btn-primary">Crear tarea</button>
                </form>
            </div>
    </div>




    </div>


       <!--Menu inferior-->
       <div class="footer">
        <div class="contacto">
            <h1>Turing Inteligencia Artificial</h1>
            <p>Av. Insurgentes Sur 674 Del Valle Norte, Benito Juárez C.P 03103, Ciudad de México Oficina 12, 4° Piso.</p>
            <h2>Teléfono de contacto:</h2>
            <p>+52 (722) 936-96-65</p>
            <h2>Correo electrónico:</h2>
            <p>contacto@turing-ia.com</p>
        </div>
        <div class="ubicacion">
            <h1>Mapa del sitio</h1>
            <ul>
                <li>
                    <a href="https://www.turing-ia.com/index.php">
                        <span>Inicio</span>
                    </a>
                </li>
                <li>
                    <a href="https://www.turing-ia.com/nosotros.php">
                        <span>Nosotros</span>
                    </a>
                </li>
                <li>
                    <a href="https://www.turing-ia.com/services.php">
                        <span>Servicios</span>
                    </a>
                </li>
                <li>
                    <a href="https://www.turing-ia.com/tableau.php">
                        <span>Tableau</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="actividades">
            <h1>Nuestros servicios</h1>
            <ul>
                <li>
                    <a href="https://www.turing-ia.com/ba.php">
                        <span>Implementación de Proyectos (Business Analyst)</span>
                    </a>
                </li>
                <li>
                    <a href="https://www.turing-ia.com/workshop.php">
                        <span>Workshop (Blue Print)</span>
                    </a>
                </li>
                <li>
                    <a href="https://www.turing-ia.com/capacitacion.php">
                        <span>Capacitación</span>
                    </a>
                </li>
                <li>
                    <a href="https://www.turing-ia.com/vsoftware.php">
                        <span>Venta de Software</span>
                    </a>
                </li>
                <li>
                    <a href="https://www.turing-ia.com/dsoftware.php">
                        <span>Desarrollo de Aplicaciones</span>
                    </a>
                </li>
                <li>
                    <a href="https://www.turing-ia.com/scorecard.php">
                        <span>Balanced Scorecard</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!--JS menu hamburguesa-->
    <script src="../assets/js/main.js"></script>
</body>
</html>