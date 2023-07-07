<?php
session_start();
ob_start();
include_once 'conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="images/habi2" type="image/x-ico">
    <title>Atualizar Senha</title>
    <style>
        
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap');

* {
    margin: 0;
    border: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
}

body {
    background:linear-gradient(45deg, #000000, #150050, #3F0071, #610094);
    /*background:linear-gradient(45deg, #000000, #000000, #2e2d2f, #ffffff);*/
    
    background-size: 300% 300%;
    animation: colors 9s ease infinite;
    background-repeat: no-repeat;
    min-height: 100vh;
    min-width: 100vw;
 
    
   
    
}

@keyframes colors{
0%{
background-position: 0% 50%;
}
50%{
background-position: 100% 50%;
}
100%{
background-position: 0% 50%;
}
}
.img{
    display: flex;
    justify-content: center;
}
.tudo{
    display: flex;
    
    align-items:center;
    justify-content: center;
}
main.container {
    
    background: white;
    min-width: 30vw;
    min-height: 40vh;
    padding: 2rem;
    box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
}

main h2 {
    font-weight: 600;
    margin-bottom: 2rem;
    position: relative;
}

main h2::before {
    content: '';
    position: absolute;
    height: 4px;
    width: 25px;
    bottom: 3px;
    left: 0;
    border-radius: 8px;
    background: linear-gradient(45deg, #8e2de2, #4a00e0);
}

form {
    display: flex;
    flex-direction: column;
}

.input-field {
    position: relative;
}

form .input-field:first-child {
    margin-bottom: 1.5rem;
}

.input-field .underline::before {
    content: '';
    position: absolute;
    height: 1px;
    width: 100%;
    bottom: -5px;
    left: 0;
    background: rgba(0, 0, 0, 0.2);
}

.input-field .underline::after {
    content: '';
    position: absolute;
    height: 1px;
    width: 100%;
    bottom: -5px;
    left: 0;
    background: linear-gradient(45deg, #8e2de2, #4a00e0);
    transform: scaleX(0);
    transition: all .3s ease-in-out;
    transform-origin: left;
}

.input-field input:focus ~ .underline::after {
    transform: scaleX(1);
}

.input-field input {
    outline: none;
    font-size: 0.9rem;
    color: rgba(0, 0, 0, 0.7);
    width: 100%;
}

.input-field input::placeholder {
    color: rgba(0, 0, 0, 0.5);
}

form input[type="submit"] {
    margin-top: 2rem;
    padding: 0.4rem;
    width: 100%;
    background: #310b55;
    cursor: pointer;
    color: white;
    font-size: 0.9rem;
    font-weight: 300;
    border-radius: 4px;
    transition: all 0.3s ease;
}

form input[type="submit"]:hover {
    letter-spacing: 0.5px;
    background:#3F0071;
}


        </style>
</head>



<body>
  

    <?php
    $chave = filter_input(INPUT_GET, 'chave', FILTER_DEFAULT);


    if (!empty($chave)) {
        //var_dump($chave);

        $query_usuario = "SELECT id 
                            FROM usuarios 
                            WHERE recuperar_senha =:recuperar_senha  
                            LIMIT 1";
        $result_usuario = $conn->prepare($query_usuario);
        $result_usuario->bindParam(':recuperar_senha', $chave, PDO::PARAM_STR);
        $result_usuario->execute();

        if (($result_usuario) and ($result_usuario->rowCount() != 0)) {
            $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            //var_dump($dados);
            if (!empty($dados['SendNovaSenha'])) {
                $senha_usuario = password_hash($dados['senha_usuario'], PASSWORD_DEFAULT);
                $recuperar_senha = 'NULL';

                $query_up_usuario = "UPDATE usuarios 
                        SET senha_usuario =:senha_usuario,
                        recuperar_senha =:recuperar_senha
                        WHERE id =:id 
                        LIMIT 1";
                $result_up_usuario = $conn->prepare($query_up_usuario);
                $result_up_usuario->bindParam(':senha_usuario', $senha_usuario, PDO::PARAM_STR);
                $result_up_usuario->bindParam(':recuperar_senha', $recuperar_senha);
                $result_up_usuario->bindParam(':id', $row_usuario['id'], PDO::PARAM_INT);

                if ($result_up_usuario->execute()) {
                    $_SESSION['msg'] = "<p style='color: green'>Senha atualizada com sucesso!</p>";
                    header("Location: index.php");
                } else {
                    echo "<p style='color: #ff0000'>Erro: Tente novamente!</p>";
                }
            }
        } else {
            $_SESSION['msg_rec'] = "<p style='color: #ff0000'>Erro: Link inválido, solicite novo link para atualizar a senha!</p>";
            header("Location: recuperar_senha.php");
        }
    } else {
        $_SESSION['msg_rec'] = "<p style='color: #ff0000'>Erro: Link inválido, solicite novo link para atualizar a senha!</p>";
        header("Location: recuperar_senha.php");
    }

    ?>

<div class="img">
     <img src="images/habi2.png">
    </div>
     <div class="tudo">
    <main class="container">
       
        <h2>Nova Senha</h2>
        <form method="POST" action="">
        <?php
        $usuario = "";
        if (isset($dados['senha_usuario'])) {
            $usuario = $dados['senha_usuario'];
        } ?>
            <div class="input-field">
                <input type="password" name="senha_usuario" placeholder="Digite a nova senha" value="<?php echo $usuario; ?>">
                <div class="underline"></div>
            </div>
          

            <input type="submit" value="Atualizar" name="SendNovaSenha">
         
        </form>
        <br>
      
        
    </main>
</div>
    

</body>

</html>