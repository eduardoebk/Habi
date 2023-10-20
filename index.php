
<?php
session_start();
ob_start();
include_once 'conexao.php';
    
?>

<!DOCTYPE html>
<html>
   
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
            <link rel="stylesheet" href="style.css">
            <script crossorigin="anonymous" src="https://unpkg.com/typeit@8.7.1/dist/index.umd.js" defer></script>
           <script src="script.js" defer></script>
           <script src="https://kit.fontawesome.com/d35b0515c5.js" crossorigin="anonymous"></script>
           <title>Cadastrar HABI</title>
    </head>

    <body >
        
        <div class="gradient">
            
        <div class="nav-bar">
            <div class="class-img-navbar">
            <img src="images/habi2.png" class="img-navbar">
            </div>
            <nav >
            <ul class="tr-navbar">
                <li><a href="#">Quem Somos</a></li>
                <li><a href="#">Como funcionamos</a></li>
                <li><a href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Login</a></li>
            <button type="button" class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#exampleModal">Cadastrar</button>
            </ul>
           
        </nav>
            
        </div>


       
<!-- Modal LOGIN -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
       
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     
       
      <form id="login_form"  method="POST" action="">   
  
      <?php
    
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    if (!empty($dados['SendLogin'])) {
        //var_dump($dados);
        $query_usuario = "SELECT id, nome, usuario, senha_usuario 
                        FROM usuarios 
                        WHERE usuario =:usuario  
                        LIMIT 1";
        $result_usuario = $conn->prepare($query_usuario);
        $result_usuario->bindParam(':usuario', $dados['usuario'], PDO::PARAM_STR);
        $result_usuario->execute();

        if(($result_usuario) AND ($result_usuario->rowCount() != 0)){
            $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
            //var_dump($row_usuario);
            if(password_verify($dados['senha_usuario'], $row_usuario['senha_usuario'])){
                $_SESSION['id'] = $row_usuario['id'];
                $_SESSION['nome'] = $row_usuario['nome'];
                header("Location: dashboard.php");
            }else{
                $_SESSION['msg'] = "<div  class='alert alert-danger' role='alert'>
                Error: Usuario ou senha incorretos! 
</div>";
            }
        }else{
            $_SESSION['msg'] = "<div  class='alert alert-danger  role='alert'>
Error: Usuario ou senha incorretos! 
</div>";
        }

        
    }

    if(isset($_SESSION['msg'])){
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>
               <div id="inputs">
                  
                 
                   
                   <!-- EMAIL -->
                   <div class="input-box">
                       <label for="email">
                           E-mail
                           <div class="input-field">
                               <i class="fa-solid fa-envelope"></i>
                               <input type="email" id="email" name="usuario" value="<?php if(isset($dados['usuario'])) ?>">
                           </div>
                       </label>
                   </div>
                   
                   <!-- PASSWORD -->
                   <div class="input-box">
                       <label for="password">
                           Senha
                           <div class="input-field">
                               <i class="fa-solid fa-key"></i>
                               <input type="password" id="password" name="senha_usuario"  value="<?php if(isset($dados['senha_usuario'])) ?>">
                           </div>
                       </label>
                       
                       <!-- FORGOT PASSWORD -->
                       
                   </div>
               </div>
   
               <!-- LOGIN BUTTON -->
               <button type="submit"  value="Acessar"  id="login_button" name="SendLogin" class="btn btn-primary">
                   Entrar
               </button>
              
               
           </form>
      
    </div>
  </div>
</div>






        
  
  <!-- Modal cadstro -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
       
        <form id="login_form" name="cad-usuario" method="POST" action="">
           
        <?php
        //Receber os dados do formulário
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        //Verificar se o usuário clicou no botão
        if (!empty($dados['CadUsuario'])) {
            //var_dump($dados);

            $empty_input = false;

            $dados = array_map('trim', $dados);
            if (in_array("", $dados)) {
                $empty_input = true;
                echo "<p style='color: #f00;'>Erro: Necessário preencher todos campos!</p>";
            } elseif (!filter_var($dados['usuario'], FILTER_VALIDATE_EMAIL)) {
                $empty_input = true;
                echo "<p style='color: #f00;'>Erro: Necessário preencher com e-mail válido!</p>";
            }

            if (!$empty_input) {
                $dados['senha_usuario'] = password_hash($dados['senha_usuario'], PASSWORD_DEFAULT);

                $query_usuario = "INSERT INTO usuarios (nome, usuario, senha_usuario) VALUES (:nome, :usuario, :senha_usuario) ";
                $cad_usuario = $conn->prepare($query_usuario);
                $cad_usuario->bindParam(':nome', $dados['nome'], PDO::PARAM_STR);
                $cad_usuario->bindParam(':usuario', $dados['usuario'], PDO::PARAM_STR);
                $cad_usuario->bindParam(':senha_usuario', $dados['senha_usuario'], PDO::PARAM_STR);
                $cad_usuario->execute();
                if ($cad_usuario->rowCount()) {
                    echo "<p style='color: green;'>Usuário cadastrado com sucesso!</p>";
                    unset($dados);
                } else {
                    echo "<p style='color: #f00;'>Erro: Usuário não cadastrado com sucesso!</p>";
                }
            }
        }
        ?>
            
            <div id="inputs">
               
                <div class="input-box">
                    <label for="name">
                        Name
                        <div class="input-field">
                            <i class="fa-solid fa-user"></i>
                            <input type="text" id="name" name="nome"  value="<?php
            if (isset($dados['nome'])) {
                echo $dados['nome'];
            }
            ?>">
                        </div>
                    </label>
                </div>
                
                <!-- EMAIL -->
                <div class="input-box">
                    <label for="email">
                        E-mail
                        <div class="input-field">
                            <i class="fa-solid fa-envelope"></i>
                            <input type="email" id="email" name="usuario" value="<?php
            if (isset($dados['usuario'])) {
                echo $dados['usuario'];
            }
            ?>">
                        </div>
                    </label>
                </div>
                
                <!-- PASSWORD -->
                <div class="input-box">
                    <label for="password">
                        Senha
                        <div class="input-field">
                            <i class="fa-solid fa-key"></i>
                            <input type="password" id="password" name="senha_usuario" value="<?php
            if(isset($dados['senha_usuario'])) 
            {
                //echo $dados['senha_usuario'];
            }?>">
                        </div>
                    </label>
                    
                    <!-- FORGOT PASSWORD -->
                    <div id="forgot_password">
                        <a href="recuperar_senha.php">
                            Esqueceu a senha?Clique aqui
                        </a>
                    </div>
                </div>
            </div>

            <!-- LOGIN BUTTON -->
            <button type="submit" value="Cadastrar" id="login_button" name="CadUsuario">
                Cadastrar
            </button>
            
        </form>
    

        


      </div>
    </div>
  </div>

<div class="corpo-primeira-parte">
        <div class="corpo-body">
            <h1>Eae jogador,curte jogar um <span class="span-interclasse"></span><br>
                agora isso e possivel.Faça isso com <br><span class="span-habi"> Habi</span>
                
        </div>
        <div class="class-btn-corpo">
            <input class="btn btn-primary" type="button" value="Conheça">
            
        </div>
</div>

   <div class="crp">
    <div class="enfeitar-img-linhas">
      <img src="images/linhas.png" class="img-linhas">
      </div>
    </div>
    


</div>
<footer class="footer">
    <div class="container">
        <div class="row">
           
           
            <div class="footer-col">
                <h4>Siga-Nos</h4>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>
    
   

   

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
        

</body>
        </html>

