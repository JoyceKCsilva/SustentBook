<?php
require_once 'php/mostrarlivros.php';
session_start();
if (isset($_SESSION['USR_ID'])) {
    $USR_ID = $_SESSION['USR_ID'];
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SustentBook</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php if (isset($_SESSION['USR_EMAIL'])): ?>
    <div class="wrapper">
            <aside id="sidebar">     
                <div class="data-bs-target">
                    <button class="toggle-btn" type="menu">
                        <img src="../img/logo.svg" alt="logo">
                    </button>
                        <ul class="sidebar-nav">
                            <li class="sidebar-item">
                                <a href="index.php" class="sidebar-link">
                                    <i class="bi bi-house"></i>
                                    <span>Home</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="php/meuperfil.php" class="sidebar-link">
                                    <i class="lni lni-user"></i>
                                    <span>Perfil</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="php/buscar.php" class="sidebar-link">
                                    <i class="lni lni-search-alt"></i>
                                    <span>Procurar</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="php/meuslivros.php" class="sidebar-link">
                                    <i class="lni lni-book"></i>
                                <span>Meus Livros</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="php/listadesejos.php" class="sidebar-link">
                                    <i class="lni lni-list"></i>
                                    <span>Lista de Desejos</span>
                                </a>
                            </li>

                            <div class="sidebar-footer">
                            <a href="php/logout.php" class="sidebar-link">
                                <i class="lni lni-exit"></i>
                                <span>Sair</span>
                            </a>
                        </u>
                </div>
            </aside>

            <div class="main p-3">
                <?php foreach ($explorarLivros as $livro): ?>
                    <div class="book-card">
                        <a href="php/detalheslivro.php?id=<?= htmlspecialchars($livro['LVR_ID']); ?>" class="text-decoration-none">
                            <div class="card h-100">
                                <img src="php/<?= htmlspecialchars($livro['LVR_FOTO']); ?>" class="card-img-top" alt="<?= htmlspecialchars($livro['LVR_TITULO']); ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($livro['LVR_TITULO']); ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($livro['LVR_DESCRICAO']); ?></p>
                                    <p><img src="php/<?php echo htmlspecialchars($livro['USR_FOTO']); ?>" width = '30' heigth = '30'> <?php echo htmlspecialchars($livro['USR_EMAIL']); ?></p>
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
                <?php endforeach; ?>
    </div>

    <?php else: ?>

        <div class="telaboasvindas">
            <div class="boasvindas">
                <div class = "cordlogo">
                    <img src="img/logo.svg">  
                    <h1>Bem vindo ao SustentBOOK</h1>
                </div>
                <p>
                    Encontre seu próximo livro, compartilhe histórias e contribua para um mundo mais sustentável. No SustenBOOK, você pode explorar um vasto acervo de livros usados, postar os seus próprios para venda ou doação, e até criar sua lista de desejos para não perder aquela edição especial. Aqui, cada livro encontra um novo lar, e você ajuda a reduzir o impacto no meio ambiente.
                    <br>
                    Nosso propósito? Conectar leitores apaixonados e dar uma nova vida a livros que merecem ser lidos outra vez. Vamos juntos nessa jornada sustentável de conhecimento e transformação!
                </p>
                <div class = "botoes">
                    <a href="php/login.php">
                        Faça login
                    </a>
                    <a href="php/createuser.php">
                        Cadastre-se
                    </a>
                </div>
            </div>
        </div>
        
            
    <?php endif; ?>
        

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
<script src="script.js"></script>
</body>
</html>
