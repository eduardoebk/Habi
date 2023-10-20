<?php

include_once 'conexao.php';
session_start();

$id = $_SESSION['id'];

$query_usuario = "SELECT * FROM usuarios WHERE id = :id LIMIT 1";
$result_usuario = $conn->prepare($query_usuario);
$result_usuario->bindParam(':id', $id, PDO::PARAM_INT); // Cria o parâmetro
$result_usuario->execute();

if ($result_usuario->rowCount() != 0) {
    $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);

    $_SESSION['turma'] = $row_usuario['Turma'];
    $_SESSION['turno'] = $row_usuario['Turno'];
    $_SESSION['escola'] = $row_usuario['Escola'];
    $_SESSION['usuario'] = $row_usuario['usuario'];

    header("Location: dashboard.php");

    $_SESSION['msg'] = "<div id='liveAlertPlaceholder'></div>";
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>
Error: Usuario ou senha incorretos!
</div>";
}
?>