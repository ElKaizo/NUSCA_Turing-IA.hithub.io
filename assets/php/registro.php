<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("conexion.php");
    $nombre = isset($_POST['name']) ? $_POST['name'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    try {
        $pdo = new PDO('mysql:host=' . $direccionservidor . ';dbname=' . $BD, $usuarioBD, $passBD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verificar si el correo electrónico ya está registrado
        $sql_verificar = "SELECT COUNT(*) as total FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql_verificar);
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['total'] > 0) {
            // El correo electrónico ya está registrado, redireccionar a una página específica
            header("location:../../pages/registro.html?error=1");
            exit; // Detener la ejecución del script
        }

        // Si el correo no está registrado, proceder con la inserción
        $nuevoPass = password_hash($password, PASSWORD_DEFAULT);
        $sql_insertar = "INSERT INTO `users` (`name`, `email`, `pass`) VALUES (:nombre, :email, :password);";
        $stmt_insertar = $pdo->prepare($sql_insertar);
        $stmt_insertar->execute([
            ':nombre' => $nombre,
            ':email' => $email,
            ':password' => $nuevoPass,
        ]);

        // Redireccionar a la página de éxito o al inicio, según corresponda
        header("location: ../../index.php");
        exit; // Detener la ejecución del script después de redireccionar

    } catch (PDOException $e) {
        echo "Hubo un error de conexión: " . $e->getMessage();
    }
}
?>
