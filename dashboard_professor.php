<?php
session_start();
ob_start();
include_once 'conexao.php';


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="d_professor.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Area Professor</title>
    <style>
        body{
            background-color:purple;
        }
        .fa-arrow-left{
    margin-right: 10px;
}





        </style>
</head>
<body>
    <nav class="NavBar">

        <div class="conteudo_esquerda">
          
           
            <img src="images/habi2.png" class="imgHabi">
        
        </div>
        <div class="conteudo_direita">
        <div class="image-container" data-bs-toggle="modal" data-bs-target="#exampleModal">
        <?php
if (isset($_SESSION['imagem_perfil'])) {
    $imagem_perfil = "imagem_professores/" . $_SESSION['id'] . "/" . $_SESSION['imagem_perfil'];
    
    // Verifique se a imagem de perfil existe no diretório
    if (file_exists($imagem_perfil)) {
        echo "<img class='profile-image' id='modal-profile-image' src='$imagem_perfil?" . time() . "' alt='Imagem de Perfil'><br>";
    } else {
        // Caso a imagem de perfil não exista, exiba a imagem padrão
        echo "<img class='profile-image' id='modal-profile-image' src='img/img_default.webp' alt='Foto de Perfil'><br>";
    }
} else {
    // Se não houver uma imagem de perfil definida na sessão, exiba a imagem padrão
    echo "<img class='profile-image' id='modal-profile-image' src='img/img_default.webp' alt='Foto de Perfil'><br>";
}
?>



</div>
         
        </div>
         
    </nav>
    

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background-color: #purple;">
      <div class="modal-header" style="background-color: purple;">
        <h5 class="modal-title text-white" id="exampleModalLabel">Atualizar Foto de Perfil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-3">
          <?php
          if (isset($_SESSION['imagem_perfil'])) {
            $imagem_perfil = "imagem_professores/" . $_SESSION['id'] . "/" . $_SESSION['imagem_perfil'];

            if (file_exists($imagem_perfil)) {
              echo "<img class='profile-image rounded-circle' id='modal-profile-image' src='$imagem_perfil?" . time() . "' alt='Imagem de Perfil'>";
            } else {
              echo "<img class='profile-image rounded-circle' id='modal-profile-image' src='img/img_default.webp' alt='Foto de Perfil'>";
            }
          } else {
            echo "<img class='profile-image rounded-circle' id='modal-profile-image' src='img/img_default.webp' alt='Foto de Perfil'>";
          }
          ?>
        </div>
        <form action="upload_imagem.php" method="post" enctype="multipart/form-data" id="form-upload-imagem">
          <div class="mb-3">
            <input type="file" name="nova_imagem" class="form-control" accept="image/jpeg, image/jpg, image/png">
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-primary btn-block" style="background-color: purple;">Enviar Nova Imagem</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


    <div class="sidebar">
        <ul>
            <li class="primeira"><a href="dashboard_professor.php"><i class="fa-solid fa-house" style="color: #FFFF;"></i>Dashboard</a></li>
            <li><a href="#"><i class="fa-solid fa-people-group" style="color: #ffffff;"></i>Times</a></li>
            <li><a href="sair"><i class="fa-solid fa-arrow-left" style="color: #ffffff;"></i>Sair</a></li>
            <!--<li><a href="#">Item 4</a></li>-->
        </ul>
      
    </div>
    <div class="content" id="elemento">
        <div class="textosIniciais">
            <h1>Olá Professor <?php echo $_SESSION['nome']; ?> </h1>
        <h2>Bem Vindo ao nosso sistema <br>de interclasse </h2>
        </div>
        



        <script>
    function loadTimesContent() {
        // Use AJAX para carregar o conteúdo da página de Times
        var elemento = document.getElementById("elemento");
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                elemento.innerHTML = xmlhttp.responseText;
            }
        };

        xmlhttp.open("GET", "conteudo_time.php", true); // Substitua "seuarquivo.php" pelo caminho para o seu arquivo PHP
        xmlhttp.send();
    }

    // Adicione um evento de clique ao link
    var linkTimes = document.querySelector(".sidebar li:nth-child(2) a"); // Seleciona o segundo link da lista
    linkTimes.addEventListener("click", loadTimesContent);


     // Obtém todos os elementos da lista
     const listItems = document.querySelectorAll('.sidebar li');

// Adiciona um ouvinte de evento de clique a cada item da lista
listItems.forEach(item => {
    item.addEventListener('click', () => {
        // Remove a classe "active" de todos os itens
        listItems.forEach(otherItem => otherItem.classList.remove('active'));

        // Adiciona a classe "active" apenas ao item clicado
        item.classList.add('active');
    });
});


</script>
</body>
</html>
