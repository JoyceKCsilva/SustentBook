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
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    
<div class="wrapper">
        <aside id="sidebar">     
            <div class="data-bs-target">
                <button class="toggle-btn" type="menu">
                    <img src="../img/logo.svg" alt="logo">
                </button>
                    <ul class="sidebar-nav">
                        <li class="sidebar-item">
                            <a href="../index.php" class="sidebar-link">
                                <i class="bi bi-house"></i>
                                <span>Perfil</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="meuperfil.php" class="sidebar-link">
                                <i class="lni lni-user"></i>
                                <span>Perfil</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="buscar.php" class="sidebar-link">
                                <i class="lni lni-search-alt"></i>
                                <span>Procurar</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="meuslivros.php" class="sidebar-link">
                                <i class="lni lni-book"></i>
                            <span>Meus Livros</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="listadesejos.php" class="sidebar-link">
                                <i class="lni lni-list"></i>
                                <span>Lista de Desejos</span>
                            </a>
                        </li>

                        <div class="sidebar-footer">
                        <a href="logout.php" class="sidebar-link">
                            <i class="lni lni-exit"></i>
                            <span>Sair</span>
                        </a>
                    </u>
                </div>

            </div>
        </aside> 

        <div class="main p-3">
            <div class="containergeral">
                <h3>Alterar Livro</h3>
                <form method="POST" enctype = "multipart/form-data" >
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="LVR_TITULO" name="LVR_TITULO" placeholder="text" value="<?= htmlspecialchars($livro['LVR_TITULO']) ?>" required>
                        <label for="LVR_TITULO">DIGITE  O TITULO DO LIVRO:</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="LVR_AUTOR" name="LVR_AUTOR" placeholder="text" value="<?= htmlspecialchars($livro['LVR_AUTOR']) ?>" required>
                        <label for="LVR_AUTOR">QUAL O AUTOR DESTE LIVRO?</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control"  id="LVR_SINOPSE" name="LVR_SINOPSE" placeholder="text" value="<?= htmlspecialchars($livro['LVR_SINOPSE']) ?>" required>
                        <label for="LVR_SINOPSE">AGORA ESCREVA SUA SINOPSE:</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="LVR_DESCRICAO" name="LVR_DESCRICAO" placeholder="text" value="<?= htmlspecialchars($livro['LVR_DESCRICAO']) ?>" required>
                        <label for="LVR_DESCRICAO">DESCREVA O ESTADO DO LIVRO:</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="INT" id="LVR_PRECO" class="form-control" name="LVR_PRECO" placeholder="text" value="<?= htmlspecialchars($livro['LVR_PRECO']) ?>" required>
                        <label for="LVR_PRECO">QUANTO VOCÊ ACHA QUE VALE ESTE LIVRO?</label>
                    </div>
                    <div class="form-floating mb-3">
                        <!-- <label for="LVR_FOTO" >ANEXE UMA IMAGEM DO LIVRO:</label> -->
                        
                        <input type="file" class="form-control" id="LVR_FOTO" name="LVR_FOTO" accept="image/*" required>
                    </div>
                    <br>
                    <div class="add">
                        <button type="submit">POSTAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            let nome = document.getElementById('USR_NOME').value;
            if (nome.trim() === '') {
            event.preventDefault(); 
            }
        });
    </script>
</body>
</html>