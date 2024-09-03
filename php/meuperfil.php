<?php
require 'db.php';
session_start();

if (isset($_SESSION['USR_ID'])) {
    $USR_ID = $_SESSION['USR_ID'];

    $stmt = $pdo->prepare("SELECT * FROM sebo_usuarios WHERE USR_ID = ?");
    $stmt->execute([$USR_ID]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "Usuário não encontrado.";
        exit();
    }

} else {
    die("Erro: ID do usuário não fornecido.");
}

if (empty($user['USR_FOTO'])) {
    $imagem = 'uploads/perfilinicial.png';
} else {
    $imagem = $user['USR_FOTO'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil</title>
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
        <div class="main p-3">
            
            <div class="perfil">
                <h1><?php echo htmlspecialchars($user['USR_NOME']); ?></h1>
                <img src="<?php echo htmlspecialchars($imagem) ?>">
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['USR_EMAIL']); ?></p>
                <p><strong>Telefone:</strong> <?php echo htmlspecialchars($user['USR_TELEFONE']); ?></p>
                <!-- Botão para redirecionar para a página de edição de perfil -->
                <a href="editarperfil.php?id=<?= htmlspecialchars($user['USR_ID']); ?>"">Editar Perfil</a>
            </div>
        </div>
        
    <?php
    // Mostrar mensagens de erro ou sucesso, se existirem
    if (isset($_SESSION['success_message'])) {
        echo '<p style="color: green;">' . htmlspecialchars($_SESSION['success_message']) . '</p>';
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        echo '<p style="color: red;">' . htmlspecialchars($_SESSION['error_message']) . '</p>';
        unset($_SESSION['error_message']);
    }
    ?>

    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
<script src="../script.js"></script>
</body>
</html>
