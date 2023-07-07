<?php
session_start();
ob_start();
include_once 'conexao.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './lib/vendor/autoload.php';
$mail = new PHPMailer(true);

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="images/habi2" type="image/x-ico">
    <title>Recuperar Senha HABI</title>
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
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    if (!empty($dados['SendRecupSenha'])) {
        //var_dump($dados);
        $query_usuario = "SELECT id, nome, usuario 
                    FROM usuarios 
                    WHERE usuario =:usuario  
                    LIMIT 1";
        $result_usuario = $conn->prepare($query_usuario);
        $result_usuario->bindParam(':usuario', $dados['usuario'], PDO::PARAM_STR);
        $result_usuario->execute();

        if (($result_usuario) and ($result_usuario->rowCount() != 0)) {
            $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
            $chave_recuperar_senha = password_hash($row_usuario['id'], PASSWORD_DEFAULT);
            //echo "Chave $chave_recuperar_senha <br>";

            $query_up_usuario = "UPDATE usuarios 
                        SET recuperar_senha =:recuperar_senha 
                        WHERE id =:id 
                        LIMIT 1";
            $result_up_usuario = $conn->prepare($query_up_usuario);
            $result_up_usuario->bindParam(':recuperar_senha', $chave_recuperar_senha, PDO::PARAM_STR);
            $result_up_usuario->bindParam(':id', $row_usuario['id'], PDO::PARAM_INT);

            if ($result_up_usuario->execute()) {
                $link = "http://localhost/form_login/atualizar_senha.php?chave=$chave_recuperar_senha";
                //echo " http://localhost/login_skyf/atualizar_senha.php?chave=$chave_recuperar_senha";

                
                try {
                    
                    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                    $mail->CharSet = 'UTF-8';
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = "suporteskyf@gmail.com";
                    $mail->Password   = "zbxizppoxibkmkoj";
                    /*$mail->SMTPSecure = 'ssl';*/
                    $mail->Port       = 587;

                    $mail->setFrom("suporteskyf@gmail.com", "Suporte HABI");
                    $mail->addAddress($row_usuario['usuario'], $row_usuario['nome']);

                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = 'Recuperar senha';
                    $mail->Body    = 'Prezado(a) ' . $row_usuario['nome'] .".<br><br>Você solicitou alteração de senha.<br><br>Para continuar o processo de recuperação de sua senha, clique no link abaixo ou cole o endereço no seu navegador: <br><br><a href='" . $link . "'>" . $link . "</a><br><br>Se você não solicitou essa alteração, nenhuma ação é necessária. Sua senha permanecerá a mesma até que você ative este código.<br><br>";
                    $mail->AltBody = 'Prezado(a) ' . $row_usuario['nome'] ."\n\nVocê solicitou alteração de senha.\n\nPara continuar o processo de recuperação de sua senha, clique no link abaixo ou cole o endereço no seu navegador: \n\n" . $link . "\n\nSe você não solicitou essa alteração, nenhuma ação é necessária. Sua senha permanecerá a mesma até que você ative este código.\n\n";
                    /*$mail->SMTPOptions = array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        ));*/
                    $mail->send();

                    $_SESSION['msg'] = "<p style='color: green'>Enviado e-mail com instruções para recuperar a senha. Acesse a sua caixa de e-mail para recuperar a senha!</p>";
                    header("Location: index.php");
                } catch (Exception $e) {
                    echo "Erro: E-mail não enviado sucesso. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                echo  "<p style='color: #ff0000'>Erro: Tente novamente!</p>";
            }
        } else {
            echo "<p style='color: #ff0000'>Erro: Usuário não encontrado!</p>";
        }
    }

    if (isset($_SESSION['msg_rec'])) {
        echo $_SESSION['msg_rec'];
        unset($_SESSION['msg_rec']);
    }

    ?>

<div class="img">
     <img src="images/habi2.png">
    </div>
     <div class="tudo">
    <main class="container">
       
        <h2>Recuperar Senha</h2>
        <form method="POST" action="">
        <?php
        $usuario = "";
        if (isset($dados['usuario'])) {
            $usuario = $dados['usuario'];
        } ?>

            <div class="input-field">
                <input type="text" name="usuario" id="username"
                    placeholder="exemplo:ebk@gmail.com" value="<?php echo $usuario; ?>">
                <div class="underline"></div>
            </div>
          

            <input type="submit" value="Recuperar" name="SendRecupSenha">
         
        </form>
        <br>
        Lembrou? <a href="index.php">clique aqui</a> para logar
        
    </main>
</div>
  
    -->
</body>

</html>













