<?php
require_once 'db.php';
session_start();

$livros = [];

if (isset($_GET['query']) && !empty($_GET['query'])) {
    $query = $_GET['query'];
    $query = "%$query%";
    
    $sql = "
        SELECT livros.*, usuarios.USR_EMAIL, usuarios.USR_FOTO 
        FROM sebo_livros AS livros
        LEFT JOIN sebo_usuarios AS usuarios ON livros.LVR_ID_USUARIO = usuarios.USR_ID
        WHERE livros.LVR_TITULO LIKE :query 
        OR livros.LVR_AUTOR LIKE :query
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':query', $query, PDO::PARAM_STR);
    
    $stmt->execute();
    
    $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar</title>
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
                        </div>
                    </ul>
                <?php else: ?>
                    <li class="sidebar-item">
                        <a href="login.php" class="sidebar-link">
                            <i class="lni lni-enter"></i>
                            <span>Login</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="createuser.php" class="sidebar-link">
                            <i class="bi bi-person-add"></i>
                            <span>Cadastro</span>
                        </a>
                    </li>
                <?php endif; ?>
        </aside>
        <div class = 'containerbusca'>
            <div class="search-container">
                <form action="buscar.php" method="GET">
                    <input type="text" name="query" placeholder="Procurar livros" value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>" />
                    <button class='buscar' type="submit">Buscar</button>
                </form>
            </div>
    
            <div class="mostraroslivros">
                <?php if (!empty($livros)): ?>
                    <?php foreach ($livros as $livro): ?>
                        <div class="resultados-busca">
                    
                            <div class="book-card">
                                <a href="detalheslivro.php?id=<?= htmlspecialchars($livro['LVR_ID']); ?>" class="text-decoration-none">
                                    <div class="card h-100">
                                        <img src="<?= htmlspecialchars($livro['LVR_FOTO']); ?>" class="card-img-top" >
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($livro['LVR_TITULO']); ?></h5>
                                            <p class="card-text"><?= htmlspecialchars($livro['LVR_DESCRICAO']); ?></p>
                                            <p><?php echo htmlspecialchars($livro['LVR_AUTOR']); ?></p>
                                            <p><img src="<?php echo htmlspecialchars($livro['USR_FOTO']); ?>" width = '30' heigth = '30'> <?php echo htmlspecialchars($livro['USR_EMAIL']); ?></p>
                                            <p>Preço: R$ <?php echo number_format($livro['LVR_PRECO'], 2, ',', '.'); ?></p>
                                            <a href="https://wa.me/<?= htmlspecialchars($livro['USR_TELEFONE']); ?>?text=<?= urlencode("Olá, estou interessado no livro '{$livro['LVR_TITULO']}' que você postou no SustenBOOK."); ?>" target="_blank" class="btn btn-success">
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 24 24"
                                                    width="24"
                                                    height="24"
                                                    >
                                                    <path fill="none" d="M0 0h24v24H0z"></path>
                                                    <path
                                                    fill="currentColor"
                                                    d="M1.946 9.315c-.522-.174-.527-.455.01-.634l19.087-6.362c.529-.176.832.12.684.638l-5.454 19.086c-.15.529-.455.547-.679.045L12 14l6-8-8 6-8.054-2.685z"
                                                    ></path>
                                                </svg>
                                                
                                                <span>Enviar mensagem</span>
                                            </a>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhum resultado encontrado.</p>
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

