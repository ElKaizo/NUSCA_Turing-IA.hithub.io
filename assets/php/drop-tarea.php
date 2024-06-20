<?php

include "conexion.php";

$conn = mysqli_connect($direccionservidor, $usuarioBD, $passBD, $BD);
$id = $_GET['id'];

$eliminar = $conn->query("DELETE FROM task WHERE id = '$id'");

header("Location: ../../index.php");

?>