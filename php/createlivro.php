<?php
require_once 'db.php';
require_once 'authenticate.php';
session_start();
$pasta = 'uploads/';

if (isset($_SESSION['USR_ID'])) {
    $USR_ID = $_SESSION['USR_ID'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $LVR_TITULO = $_POST['LVR_TITULO'];
        $LVR_AUTOR = $_POST['LVR_AUTOR'];
        $LVR_SINOPSE = $_POST['LVR_SINOPSE'];
        $LVR_DESCRICAO = $_POST['LVR_DESCRICAO'];
        $LVR_PRECO = $_POST['LVR_PRECO'];

        if (isset($_FILES["LVR_FOTO"])) {
            $arquivo = $_FILES["LVR_FOTO"];
            
            if ($arquivo['error']) {
                die("FALHA AO ENVIAR ARQUIVO");
            }
        
            if ($arquivo["size"] > 1048576) {
                die("ARQUIVO MUITO GRANDE! MAX: 1MB");
            }
        
            $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
            $novoNomeDoArquivo = uniqid() . ".$extensao";
        
            if ($extensao != "jpg" && $extensao != "png") {
                die("TIPO DE ARQUIVO NÃO ACEITO");
            }

            $path = $pasta . $novoNomeDoArquivo;

            if (move_uploaded_file($arquivo['tmp_name'], $path)) {
                $stmt = $pdo->prepare("INSERT INTO sebo_livros (LVR_TITULO, LVR_AUTOR, LVR_SINOPSE, LVR_DESCRICAO, LVR_PRECO, LVR_FOTO, LVR_ID_USUARIO) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $_POST['LVR_TITULO'],
                    $_POST['LVR_AUTOR'],
                    $_POST['LVR_SINOPSE'],
                    $_POST['LVR_DESCRICAO'],
                    $_POST['LVR_PRECO'],
                    $path,
                    $_SESSION['USR_ID']
                ]);
                header('Location: meuslivros.php');
            } else {
                echo "Failed to move file: " . $arquivo['tmp_name'] . " to " . $pasta . $novoNomeDoArquivo . "." . $extensao;
                die("FALHA AO MOVER O ARQUIVO");
            }
        }    
    }  
    
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar livro</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container">
            <img src="../../img/logo.svg">
            <br>
            <form method="POST" enctype = "multipart/form-data" >
                <label for="LVR_TITULO">DIGITE  O TITULO DO LIVRO:</label>
                <input type="text" id="LVR_TITULO" name="LVR_TITULO" required>
                <br>
                <label for="LVR_AUTOR">QUAL O AUTOR DESTE LIVRO?</label>
                <input type="text" id="LVR_AUTOR" name="LVR_AUTOR" required>
                <br>
                <label for="LVR_SINOPSE">AGORA ESCREVA SUA SINOPSE:</label>
                <input type="text" id="LVR_SINOPSE" name="LVR_SINOPSE" required>
                <br>
                <label for="LVR_DESCRICAO">DESCREVA O ESTADO DO LIVRO:</label>
                <input type="text" id="LVR_DESCRICAO" name="LVR_DESCRICAO" required>
                <br>
                <label for="LVR_PRECO">QUANTO VOCÊ ACHA QUE VALE ESTE LIVRO?</label>
                <input type="INT" id="LVR_PRECO" name="LVR_PRECO" required>
                <br>
                <label for="LVR_FOTO">ANEXE UMA IMAGEM DO LIVRO:</label>
                <input type="file" id="LVR_FOTO" name="LVR_FOTO" accept="image/*" required>
                <br>
                <div class="add">
                    <button type="submit">POSTAR</button>
                </div>
            </form>
    </div>
</body>
<script>
    document.querySelector('form').addEventListener('submit', function(event) {
        let nome = document.getElementById('USR_NOME').value;
        if (nome.trim() === '') {
            event.preventDefault(); 
        }
    });
</script>
</html>
