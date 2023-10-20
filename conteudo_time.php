<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: purple;
            margin: 0;
            padding: 0;
            
        }
        
        .quadrado {
            background-color: #2ecc71;
            margin-top: 10px;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            flex: 1;
            height:40%;
            width: 300px;
        }
        h5 {
            font-size: 20px;
            color: #fff;
        }
        p {
            margin: 10px 0;
            font-size: 16px;
            color: #fff;
        }
        span {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php
    // Configuração da conexão com o banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "skyfcadastro";

    // Conecte ao banco de dados
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifique a conexão
    if ($conn->connect_error) {
        die("Erro na conexão: " . $conn->connect_error);
    }

    // Consulta SQL para buscar os dados da tabela timesfutsal
    $sql = "SELECT * FROM timesfutsal";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Loop através dos resultados da consulta
        $colors = ['#2ecc71', '#3498db', '#f1c40f']; // Cores para as divs
        $color_index = 0;

        while ($row = $result->fetch_assoc()) {
            // Preencha as posições no código HTML com os dados do banco de dados
            echo '<div class="tudo">';
            echo '<div class="quadrado" style="background-color: ' . $colors[$color_index] . '">';
            echo '<h5>' . $row['nome'] . '</h5>';
            echo '<p><span>Goleiro:</span>' . $row['goleiro'] . '</p>';
            echo '<p><span>Fixo:</span>' . $row['fixo'] . '</p>';
            echo '<p><span>Pivô:</span>' . $row['pivo'] . '</p>';
            echo '<p><span>Ala esquerda:</span>' . $row['alaesq'] . '</p>';
            echo '<p><span>Ala direita:</span>' . $row['aladir'] . '</p>';
            echo '</div>';
            echo '</div>';


            // Avance para a próxima cor
            $color_index = ($color_index + 1) % count($colors);
        }
    } else {
        echo "Nenhum time encontrado.";
    }

    // Feche a conexão com o banco de dados
    $conn->close();
    ?>
</body>
</html>
