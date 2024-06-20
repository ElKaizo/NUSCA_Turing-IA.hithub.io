<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("location: ../../pages/inicio.html");
    exit();
}

// Verificar si se ha enviado el formulario de edición
if (isset($_POST['editar'])) {
    $id_tarea = $_GET['id']; // Asegúrate de tener el ID de la tarea
    $name_act = $_POST['name_act'];
    $descripcion = $_POST['descripcion'];
    $tag = $_POST['tag'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $done = $_POST['done'];

    // Verificar que el campo title no esté vacío
    if (!empty($name_act)) {
        // Incluir archivo de configuración de conexión a la base de datos
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

        // Realizar la actualización en la base de datos
        $query = $conn->query("UPDATE task SET
            name_act = '$name_act', 
            descripcion = '$descripcion', 
            tag = '$tag', 
            fecha_inicio = '$fecha_inicio', 
            fecha_fin = '$fecha_fin', 
            done = '$done'
            WHERE id = '$id_tarea'");

        // Verificar si la consulta se ejecutó correctamente
        if ($query) {
            mysqli_close($conn);
            // Redirigir después de la actualización a una página adecuada
            header("Location: ../../index.php?id=" . $id_tarea);
            exit(); // Asegurar que no se ejecute más después de la redirección
        } else {
            echo "Error al actualizar la tarea: " . $conn->error;
        }
    } else {
        ?>
        <div class="alert alert-danger mt-3" role="alert">
            Error: Ingresa el título de la tarea
        </div>
        <?php
    }
}

// Cargar datos de la tarea para mostrar en el formulario
if (isset($_GET['id']) && isset($_GET['idU'])) {
    $id_tarea = $_GET['id'];
    $id_user = $_SESSION['usuario_id'];
    require "conexion.php";
    $conn = mysqli_connect($direccionservidor, $usuarioBD, $passBD, $BD);
    $sql = $conn->query("SELECT * FROM task WHERE id = '$id_tarea'");
    $row = $sql->fetch_assoc();

    if ($row['id_user'] == $id_user) {
        $fecha_fin = $row['fecha_fin'];
        $fecha_inicio = $row['fecha_inicio'];
        mysqli_close($conn);
    } else {
        header("location: cerrar.php");
        exit();
    }
} else {
    header("location: cerrar.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar tarea</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
    <link rel="icon" href="../../media/img/icon.jpg" type="image/x-icon">
    <link rel="stylesheet" href="../css/barrasup.css">
    <link rel="stylesheet" href="../css/tamanos.css">
    <link rel="stylesheet" href="../css/barrainf.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/inicio.css">
    <link rel="stylesheet" href="../css/edit-tarea.css">
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
    <div class="form-container">
    <div class="form-row">
        <form action="" method="POST">
            <div class="mb-3 mt-3">
                <label for="exampleFormControlTextarea1" class="form-label">Titulo</label>
                <textarea class="form-control" name="name_act" id="exampleFormControlTextarea1" rows="3"><?= $row['name_act']; ?></textarea>
            </div>

            <div class="mb-3 mt-3">
                <label for="exampleFormControlTextarea1" class="form-label">Descripcion</label>
                <textarea class="form-control" name="descripcion" id="exampleFormControlTextarea1" rows="3"><?= $row['descripcion']; ?></textarea>
            </div>

            <div class="mb-3 mt-3">
                <label for="exampleFormControlTextarea1" class="form-label">Etiqueta</label>
                <textarea class="form-control" name="tag" id="exampleFormControlTextarea1" rows="3"><?= $row['tag']; ?></textarea>
            </div>

            <div class="mb-3 mt-3">
                <label for="fecha_inicio" class="form-label">Fecha inicial</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo $fecha_inicio; ?>">
            </div>

            <div class="mb-3 mt-3">
                <label for="fecha_fin" class="form-label">Fecha final</label>
                <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo $fecha_fin; ?>">
            </div>

            <div class="mb-3 mt-3">
                <label for="done" class="form-label">Estado</label>
                <select id="done" name="done">
                    <option value="0" <?= $row['done'] == 0 ? 'selected' : ''; ?>>En curso</option>
                    <option value="1" <?= $row['done'] == 1 ? 'selected' : ''; ?>>Finalizada</option>
                </select>
            </div>

            <button type="submit" name="editar" class="btn btn-success">Editar tarea</button>
        </form>
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