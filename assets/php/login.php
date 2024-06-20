<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("conexion.php");
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    try {
        $pdo = new PDO('mysql:host=' . $direccionservidor . ';dbname=' . $BD, $usuarioBD, $passBD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM users WHERE email = :email";
        $sentencia = $pdo->prepare($sql);
        $sentencia->execute(['email' => $email]);

        $usuarios = $sentencia->fetchAll(PDO::FETCH_ASSOC);

        $login = false;
        foreach ($usuarios as $user) {
            // Aquí debería utilizar password_verify si las contraseñas están hasheadas
            if (password_verify($password, $user["pass"])) {
                $_SESSION['usuario_id'] =$user['id'];
                $_SESSION['usuario_name'] =$user['name'];
                $_SESSION['usuario_email'] =$user['email'];
                $_SESSION['usuario_admin'] =$user['admin'];
                $login = true;
            }
        }

        if ($login) {
            echo "existe en la bd";
            header("location:../../index.php");
        } else {
            header("location:../../pages/login.html?error=1");
        }

    } catch (PDOException $e) {
        echo "Hubo un error de conexión: " . $e->getMessage();
    }
}
?>
