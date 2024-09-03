<?php
require_once 'db.php';
session_start();

$user_id = $_SESSION['USR_ID'];

$stmt = $pdo->prepare("SELECT DSJ_TITULO, DSJ_AUTOR FROM sebo_desejos WHERE DSJ_USR_ID = ?");
$stmt->execute([$user_id]);
$desejos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$livros_venda = [];

foreach ($desejos as $desejo) {
    $stmt2 = $pdo->prepare("SELECT LVR_PRECO, LVR_ID_USUARIO, LVR_ID FROM sebo_livros WHERE LVR_TITULO = ? AND LVR_ID_USUARIO != ?");
    $stmt2->execute([$desejo['DSJ_TITULO'], $user_id]);
    $livros_venda[$desejo['DSJ_TITULO']] = $stmt2->fetchAll(PDO::FETCH_ASSOC);
}

$user_data = [];
foreach ($livros_venda as $titulo => $livros) {
    foreach ($livros as $livro) {
        $seller_id = $livro['LVR_ID_USUARIO'];
        $stmt3 = $pdo->prepare("SELECT USR_FOTO, USR_EMAIL FROM sebo_usuarios WHERE USR_ID = ?");
        $stmt3->execute([$seller_id]);
        $user_data[$titulo][$seller_id] = $stmt3->fetch(PDO::FETCH_ASSOC);
    }
}
?>




<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha lista de desejos</title>
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
                <?php if (isset($_SESSION['USR_EMAIL'])): ?>
                

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
            <?php else: ?>
                <li class="sidebar-item">
                    <a href="login.php" class="sidebar-link">
                    <i class="lni lni-enter"></i>
                        <span>login</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="createuser.php" class="sidebar-link">
                    <i class="bi bi-person-add"></i>
                        <span>cadastro</span>
                    </a>
                </li>
            <?php endif; ?>
        </aside>

        <div>
            <form action="add-desejo.php" method="POST">
                <input type="text" id="DSJ_TITULO" name="DSJ_TITULO" placeholder="Título do livro" required>
                <input type="text" id="DSJ_AUTOR" name="DSJ_AUTOR" placeholder="Autor do livro" required>
                <button type="submit">Adicionar à Lista de Desejos</button>
            </form>
        </div>

        <div>
        <h1>Minha Lista de Desejos</h1>
<?php if (empty($desejos)): ?>
    <p>Você ainda não adicionou nenhum livro à sua lista de desejos.</p>
<?php else: ?>
    <?php foreach ($desejos as $desejo): ?>
        <div class="desejo">
            <h3><?= htmlspecialchars($desejo['DSJ_TITULO']); ?> - <?= htmlspecialchars($desejo['DSJ_AUTOR']); ?></h3>
            <?php if (!empty($livros_venda[$desejo['DSJ_TITULO']])): ?>
                <ul>
                    <?php foreach ($livros_venda[$desejo['DSJ_TITULO']] as $livro): ?>
                        <li>
                            <?php $seller_id = $livro['LVR_ID_USUARIO']; ?>
                            <?php $seller_data = $user_data[$desejo['DSJ_TITULO']][$seller_id]; ?>
                            <img src="<?= htmlspecialchars($seller_data['USR_FOTO']); ?>" alt="Foto do vendedor" width="30" height="30">
                            <a href="detalheslivro.php?id=<?= $livro['LVR_ID']; ?>"><?= htmlspecialchars($seller_data['USR_EMAIL']); ?> está vendendo este livro por R$ <?= htmlspecialchars($livro['LVR_PRECO']); ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Nenhum usuário está vendendo este livro no momento.</p>
            <?php endif; ?>
            
            <!-- Link para remover o livro da lista de desejos -->
            <a href="removerdesejo.php?titulo=<?= urlencode($desejo['DSJ_TITULO']); ?>&autor=<?= urlencode($desejo['DSJ_AUTOR']); ?>" onclick="return confirm('Tem certeza que deseja remover este livro da sua lista de desejos?');">Remover</a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>


    </div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
<script src="../script.js"></script>
</body>
</html>