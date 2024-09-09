<?php
require_once 'db.php';
require_once 'authenticate.php';
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("
    SELECT livros.*, usuarios.USR_TELEFONE 
    FROM sebo_livros AS livros
    JOIN sebo_usuarios AS usuarios ON livros.LVR_ID_USUARIO = usuarios.USR_ID
    WHERE livros.LVR_ID = ?
    ");
    $stmt->execute([$id]);
    $livro = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$livro) {
        echo "Livro não encontrado.";
    }

} else {
    die("Erro: ID do livro não fornecido.");
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do livro</title>
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
        </aside>

        <div class="main p-3">
            <div class="containergeral">
                <div class = 'detalheslivro'>
                    <h2>Detalhes do livro</h2>
                        <?php if ($livro): ?>
                            <img src="<?= htmlspecialchars($livro['LVR_FOTO']); ?>" alt="Imagem do Livro">
                            <p><strong>TÍTULO:</strong> <?= htmlspecialchars($livro['LVR_TITULO']) ?></p>
                            <p><strong>AUTOR:</strong> <?= htmlspecialchars($livro['LVR_AUTOR']) ?></p>
                            <p><strong>SINOPSE:</strong> <?= nl2br(htmlspecialchars($livro['LVR_SINOPSE'])) ?></p>
                            <p><strong>DESCRIÇÃO:</strong> <?= nl2br(htmlspecialchars($livro['LVR_DESCRICAO'])) ?></p>
                            <p><strong>PREÇO:</strong> R$ <?= htmlspecialchars(number_format($livro['LVR_PRECO'], 2, ',', '.')) ?></p>
                        <?php if($_SESSION['USR_ID'] == $livro['LVR_ID_USUARIO']): ?>
                            <a href="updatelivro.php?id=<?= $livro['LVR_ID'] ?>">EDITAR</a>
                            <a href="deletelivro.php?id=<?= $livro['LVR_ID'] ?>">EXCLUIR</a>
                        <?php else: ?>
                            <p><strong>CONTATO:</strong> 
                            <?php
                                $telefone = htmlspecialchars($livro['USR_TELEFONE']);
                                $titulo = htmlspecialchars($livro['LVR_TITULO']);
                                $mensagem = urlencode("Olá, estou interessado no livro '$titulo' que você postou no SustenBOOK.");
                            ?>
                            <a href="https://wa.me/<?= $telefone ?>?text=<?= $mensagem ?>" target="_blank">
                                Enviar mensagem pelo WhatsApp
                            </a>
                        </p>
                        <?php endif; ?>
                        
                    <?php else: ?>
                        <p>Livro não encontrado.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
   
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
<script src="../script.js"></script>     
</body>
</html>