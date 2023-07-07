<?php
session_start();
include_once './conexao.php';

//Verifica se o usuario clicou no botao.
$SendCadImg = filter_input(INPUT_POST,'SendCadImg',FILTER_SANITIZE_STRING);

if($SendCadImg){
    //Recebe os dados do formulario
    $nome = filter_input(INPUT_POST,'nome',FILTER_SANITIZE_STRING);
    $nome_imagem = $_FILES['imagem'] ['name'];
    //var_dump($_FILES['imagem']);

    //Inserir no Banco de Dados
    $result_img= "INSERT INTO imagens( imagem)  VALUES (:imagem)";
    $insert_msg= $conn->prepare($result_img);
    
    $insert_msg-> bindParam(':imagem',$nome_imagem);

    if($insert_msg->execute()){
        $_SESSION['msg'] = "<p style='color: green;'>Imagem enviada para o Banco de Dados!</p>";
    header("Location: dashboard.php");

    $ultimo_id = $conn->lastInsertId();


    //Diretorio onde o arquivo vai ser salvo
    $diretorio= 'Imagens/'.$ultimo_id;
    }


     if (!file_exists($diretorio)) {
        mkdir($diretorio, 0777, true);
    }

    move_uploaded_file($_FILES['imagem']['tmp_name'], $diretorio . $nome_imagem);

} else {
    $_SESSION['msg'] = "<p>Erro ao salvar</p>";
    //header("Location: index.php");
    
}
// Obtém todas as imagens do banco de dados
$result_imagens = $conn->query("SELECT * FROM imagens");

// Verifica se existem imagens
if ($result_imagens && $result_imagens->rowCount() > 0) {
    $imagens = $result_imagens->fetchAll(PDO::FETCH_ASSOC);
}
?>
