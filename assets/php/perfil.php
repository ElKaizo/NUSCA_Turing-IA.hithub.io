<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("location: ../../pages/inicio.html");
    exit();
}

// Obtener el ID de usuario de la sesión
$id_user = $_SESSION['usuario_id'];

// Procesar el formulario de actualización si se ha enviado
if (isset($_POST['editar'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['pass']; // Nueva contraseña, si se proporciona

    // Validar que el nombre no esté vacío
    if (!empty($name)) {
        // Incluir archivo de configuración de conexión a la base de datos
        require "conexion.php";
        $conn = mysqli_connect($direccionservidor, $usuarioBD, $passBD, $BD);

        // Verificar la conexión
        if (!$conn) {
            die("La conexión falló: " . mysqli_connect_error());
        }

        // Preparar consulta para actualizar datos del usuario
        $sql = "UPDATE users SET name = ?, email = ?";
        $params = [$name, $email];

        // Agregar la actualización de la contraseña si se proporcionó una nueva
        if (!empty($pass)) {
            $nuevoPass = password_hash($pass, PASSWORD_DEFAULT);
            $sql .= ", pass = ?";
            $params[] = $nuevoPass; // Añadir la nueva contraseña al array de parámetros
        }

        $sql .= " WHERE id = ?";
        $params[] = $id_user; // Añadir el ID de usuario al array de parámetros

        // Preparar y ejecutar la consulta
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(str_repeat("s", count($params)), ...$params);

        if ($stmt->execute()) {
            // Redirigir después de la actualización a una página adecuada
            header("Location: ../../index.php?id=" . $id_user);
            exit(); // Asegurar que no se ejecute más después de la redirección
        } else {
            echo "Error al actualizar el perfil: " . $stmt->error;
        }

        // Cerrar la conexión y liberar recursos
        $stmt->close();
        mysqli_close($conn);
    } else {
        echo '<div class="alert alert-danger mt-3" role="alert">Error: Ingresa el nombre de usuario</div>';
    }
}

// Cargar datos del usuario para mostrar en el formulario
require "conexion.php";
$conn = mysqli_connect($direccionservidor, $usuarioBD, $passBD, $BD);
$sql = $conn->query("SELECT * FROM users WHERE id = '$id_user'");
$row = $sql->fetch_assoc();

if ($row['id'] != $id_user) {
    mysqli_close($conn);
    header("location: cerrar.php");
    exit();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>

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
                        <a href="#" class="nav__link">
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
                    <label for="name" class="form-label">Nombre de usuario</label>
                    <input type="text" class="form-control" name="name" id="name" value="<?= $row['name']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" name="email" id="email" value="<?= $row['email']; ?>"required>
                </div>

                <div class="mb-3">
                    <label for="pass" class="form-label">Nueva Contraseña (opcional)</label>
                    <input type="password" class="form-control" name="pass" id="pass">
                </div>

                <button type="submit" name="editar" class="btn btn-success">Actualizar perfil</button>
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