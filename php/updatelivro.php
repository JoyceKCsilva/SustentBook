<?php
require_once 'db.php';
require_once 'authenticate.php';

$pasta = 'uploads/';

if (isset($_SESSION['USR_ID'])) {
    $USR_ID = $_SESSION['USR_ID'];

    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM sebo_livros WHERE LVR_ID = ?");
    $stmt->execute([$id]);
    $livro = $stmt->fetch();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $LVR_TITULO = $_POST['LVR_TITULO'];
        $LVR_AUTOR = $_POST['LVR_AUTOR'];
        $LVR_SINOPSE = $_POST['LVR_SINOPSE'];
        $LVR_DESCRICAO = $_POST['LVR_DESCRICAO'];
        $LVR_PRECO = $_POST['LVR_PRECO'];

        $imagem_antiga = $livro['LVR_FOTO'];
        
        if ($livro) {
            if ($livro['LVR_ID_USUARIO'] == $USR_ID) {
                $stmt = $pdo->prepare("UPDATE sebo_livros SET LVR_TITULO = ?, LVR_AUTOR = ?, LVR_SINOPSE = ?, LVR_DESCRICAO = ?, LVR_PRECO = ? WHERE LVR_ID = ?");
                $stmt->execute([
                    $LVR_TITULO,
                    $LVR_AUTOR,
                    $LVR_SINOPSE,
                    $LVR_DESCRICAO,
                    $LVR_PRECO,
                    $id
                ]);

                if (isset($_FILES["LVR_FOTO"]) && $_FILES["LVR_FOTO"]["error"] == 0) {
                    $arquivo = $_FILES["LVR_FOTO"];
                    
                    $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
                    $novoNomeDoArquivo = uniqid() . ".$extensao";
                    $path = $pasta . $novoNomeDoArquivo;

                    if (move_uploaded_file($arquivo['tmp_name'], $path)) {
                        $stmt = $pdo->prepare("UPDATE sebo_livros SET LVR_FOTO = ? WHERE LVR_ID = ?");
                        $stmt->execute([$path, $id]);

                        if ($imagem_antiga && file_exists($imagem_antiga)) {
                            unlink($imagem_antiga);
                        }
                    } else {
                        die("FALHA AO MOVER O ARQUIVO");
                    }
                }

                header('Location: meuslivros.php');
                exit;
            } else {
                die("Você não é o dono deste livro");
            }
        } else {
            die("Livro não encontrado");
        }
    }
}

?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar informações</title>
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
                <input type="text" id="LVR_TITULO" name="LVR_TITULO" value="<?= htmlspecialchars($livro['LVR_TITULO']) ?>" required>
                <br>
                <label for="LVR_AUTOR">QUAL O AUTOR DESTE LIVRO?</label>
                <input type="text" id="LVR_AUTOR" name="LVR_AUTOR" value="<?= htmlspecialchars($livro['LVR_AUTOR']) ?>" required>
                <br>
                <label for="LVR_SINOPSE">AGORA ESCREVA SUA SINOPSE:</label>
                <input type="text" id="LVR_SINOPSE" name="LVR_SINOPSE" value="<?= htmlspecialchars($livro['LVR_SINOPSE']) ?>" required>
                <br>
                <label for="LVR_DESCRICAO">DESCREVA O ESTADO DO LIVRO:</label>
                <input type="text" id="LVR_DESCRICAO" name="LVR_DESCRICAO" value="<?= htmlspecialchars($livro['LVR_DESCRICAO']) ?>" required>
                <br>
                <label for="LVR_PRECO">QUANTO VOCÊ ACHA QUE VALE ESTE LIVRO?</label>
                <input type="INT" id="LVR_PRECO" name="LVR_PRECO" value="<?= htmlspecialchars($livro['LVR_PRECO']) ?>" required>
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
</html>