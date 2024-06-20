<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("location: pages/inicio.html");
    exit();
} else {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.min.css">
    <link rel="icon" href="../media/img/icon.jpg" type="image/x-icon">
    <!--CSS-->
    <link rel="stylesheet" href="assets/css/barrasup.css">
    <link rel="stylesheet" href="assets/css/tamanos.css">
    <link rel="stylesheet" href="assets/css/barrainf.css">
    <link rel="stylesheet" href="assets/css/plataform.css">
</head>
<body>

    <!--Menu superior-->
    <header class="header" id="header">
        <nav class="nav container">
            <img class="logo" src="media/img/logo.png" alt="">
            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <li class="nav__item">
                        <a href="#" class="nav__link">
                            <i class="ri-arrow-right-up-line"></i>
                            <span>Plataforma</span>
                        </a>
                    </li>
                    <li class="nav__item">
                        <a href="assets/php/cerrar.php" class="nav__link">
                            <i class="ri-arrow-right-up-line"></i>
                            <span>Cerrar Sesión</span>
                        </a>
                    </li>
                    <li class="nav__item">
                        <a href="assets/php/perfil.php" class="nav__link">
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
    <!--formulario-->
    <div class="promo">
        <div class="content">
            <?php
                require "assets/php/conexion.php";
                // Conexión a la base de datos usando mysqli
                $conn = mysqli_connect($direccionservidor, $usuarioBD, $passBD, $BD);

                // Verifica si la conexión fue exitosa
                if (!$conn) {
                    die("Conexión fallida: " . mysqli_connect_error());
                }

                // Realiza la consulta
                $query = $conn->query("SELECT * FROM task where id_user = '" . $_SESSION['usuario_id'] . "' ORDER BY id DESC");
                $query2 = $conn->query("SELECT * FROM users WHERE id = '" . $_SESSION['usuario_id'] . "'");

                if ($query2) {
                    // Verifica si se encontró alguna fila
                    if ($query2->num_rows > 0) {
                        $row = $query2->fetch_assoc();  // Obtiene la primera fila como array asociativo
                        $name = $row['name'];  // Accede al campo 'name' del resultado
                    } else {
                        echo "No se encontraron resultados para el usuario con ID {$_SESSION['usuario_id']}";
                    }
                } else {
                    echo "Error en la consulta: " . $conn->error;
                }

                // Cierra la conexión
                $conn->close();
            ?>

            <!-- Muestra el mensaje de bienvenida -->
            <h1>Bienvenido <?php echo $name; ?></h1>

            <!-- Botón para nueva tarea fuera del bucle -->
            <div class="row">
                <a href="assets/php/new-tarea.php">
                    <button class="btn btn-warning">Nueva tarea</button>
                </a>
                <br><br>
            </div>

            <!-- Tabla de tareas -->
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Titulo</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Etiqueta</th>
                        <th scope="col">Fecha inicial</th>
                        <th scope="col">Fecha final</th>
                        <th scope="col">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if ($query) {
                            $num_act = 1;
                            foreach ($query as $value) {
                    ?>
                    <tr>
                        <th scope="row"><?= $num_act++; ?></th>
                        <td><?= htmlspecialchars($value['name_act']); ?></td>
                        <td><?= htmlspecialchars($value['descripcion']); ?></td>
                        <td><?= htmlspecialchars($value['tag']); ?></td>
                        <td><?= htmlspecialchars($value['fecha_inicio']); ?></td>
                        <td><?= htmlspecialchars($value['fecha_fin']); ?></td>
                        <td>
                            <?php if ($value['done'] == 0) { ?>
                                <p class="red">En curso</p>
                            <?php } else { ?>
                                <p class="green">Finalizado</p>
                            <?php } ?>
                        </td>
                        <td>
                            <a href="assets/php/drop-tarea.php?id=<?= $value['id']; ?>">
                                <button class="btn btn-danger">Eliminar</button>
                            </a>
                            <a href="assets/php/edit-tarea.php?id=<?= $value['id']; ?>&idU=<?= $value['id_user']; ?>">
                                <button class="btn btn-info">Editar</button>
                            </a>
                        </td>
                    </tr>
                    <?php
                            }
                        } else {
                            echo "Error en la consulta: " . $conn->error;
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>


      <br><br><br>
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

<?php
}