<?php
session_start();
require 'db.php';

$pasta = 'fotousuario/';

if (isset($_SESSION['USR_ID'])) {
    $USR_ID = $_SESSION['USR_ID'];

    $stmt = $pdo->prepare("SELECT * FROM sebo_usuarios WHERE USR_ID = ?");
    $stmt->execute([$USR_ID]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("Usuário não encontrado.");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $USR_NOME = $_POST['USR_NOME'];
        $USR_EMAIL = $_POST['USR_EMAIL'];
        $USR_TELEFONE = $_POST['USR_TELEFONE'];
        $USR_SENHA = $_POST['USR_SENHA'];

        $imagem_antiga = !empty($user['USR_FOTO']) ? $user['USR_FOTO'] : '';

        if (!empty($USR_SENHA)) {
            $stmt = $pdo->prepare("UPDATE sebo_usuarios SET USR_NOME = ?, USR_EMAIL = ?, USR_TELEFONE = ?, USR_SENHA = ? WHERE USR_ID = ?");
            $updateSuccess = $stmt->execute([
                $USR_NOME,
                $USR_EMAIL,
                $USR_TELEFONE,
                password_hash($USR_SENHA, PASSWORD_BCRYPT),
                $USR_ID
            ]);
        } else {
            $stmt = $pdo->prepare("UPDATE sebo_usuarios SET USR_NOME = ?, USR_EMAIL = ?, USR_TELEFONE = ? WHERE USR_ID = ?");
            $updateSuccess = $stmt->execute([
                $USR_NOME,
                $USR_EMAIL,
                $USR_TELEFONE,
                $USR_ID
            ]);
        }

        if ($updateSuccess) {
            if (isset($_FILES["USR_FOTO"]) && $_FILES["USR_FOTO"]["error"] == 0) {
                $arquivo = $_FILES["USR_FOTO"];
                
                $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
                $novoNomeDoArquivo = uniqid() . ".$extensao";
                $path = $pasta . $novoNomeDoArquivo;
                

                if (move_uploaded_file($arquivo['tmp_name'], $path)) {
                    $stmt = $pdo->prepare("UPDATE sebo_usuarios SET USR_FOTO = ? WHERE USR_ID = ?");
                    $stmt->execute([$path, $USR_ID]);

                    if ($imagem_antiga && file_exists($imagem_antiga)) {
                        unlink($imagem_antiga);
                    }
                } else {
                    die("Falha ao mover o arquivo.");
                }
            }

            header('Location: meuperfil.php');
            exit();
        } else {
            die("Erro ao atualizar os dados do usuário.");
        }
    }
} else {
    die("Erro: Usuário não autenticado.");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
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
            <div class="containergeral">
                <h1>Editar Informações</h1>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-floating mb-3">
                        <input type="text" name="USR_NOME" class="form-control" value="<?php echo htmlspecialchars($user['USR_NOME']); ?>" required>
                        <label for="USR_NOME">Novo nome:</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="USR_EMAIL" name="USR_EMAIL" value="<?php echo htmlspecialchars($user['USR_EMAIL']); ?>" required>
                        <label for="USR_EMAIL">Novo Email:</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="USR_TELEFONE" name="USR_TELEFONE" value="<?php echo htmlspecialchars($user['USR_TELEFONE']); ?>">
                        <label for="USR_TELEFONE">Novo Telefone:</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="USR_SENHA" name="USR_SENHA">
                        <label for="USR_SENHA">Nova Senha:</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="file" class="form-control" id="USR_FOTO" name="USR_FOTO" accept="image/*">
                        <label for="USR_FOTO">Nova Foto de Perfil:</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                </form>
            </div>
        </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
<script src="../script.js"></script>
</body>
</html>

