<?php
require_once 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $USR_EMAIL = $_POST['USR_EMAIL'];
    $USR_SENHA = $_POST['USR_SENHA'];

    $stmt = $pdo->prepare("SELECT * FROM sebo_usuarios WHERE USR_EMAIL = ?");
    $stmt->execute([$USR_EMAIL]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($USR_SENHA, $user['USR_SENHA'])) {
        $_SESSION['USR_ID'] = $user['USR_ID'];
        $_SESSION['USR_EMAIL'] = $user['USR_EMAIL'];
        header('Location: ../index.php');
        exit;
    } else {
        $erro = 'EMAIL OU SENHA INCORRETOS';
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/loginstyle.css">
</head>

<body>
    <div class="container">
        <img src="../img/logo.svg">
        <br><br>
        <form method="POST">
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="USR_EMAIL" name="USR_EMAIL" placeholder="name@example.com" required>
                <label for="USR_EMAIL">Email</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="USR_SENHA" name="USR_SENHA" placeholder="Password" required>
                <label for="USR_SENHA">Senha</label>
            </div>
            <div id="mensagemErro" style="color: red;"><?php echo htmlspecialchars($erro); ?></div>
            <br>
            <button type="submit" class="btn btn-primary">Entrar</button>
            
            
        </form>
        <br>
        <a href="createuser.php">CLIQUE AQUI SE AINDA NÃO POSSUI UMA CONTA</a>
    </div>
</body>
<script>

    document.querySelector('form').addEventListener('submit', function(event) {
        let senha = document.getElementById('USR_SENHA').value;
        let email = document.getElementById('USR_EMAIL').value;
        if (email === '' || senha === '') {
            event.preventDefault();
            document.getElementById('mensagemErro').innerText = 'Por favor, preencha todos os campos.';
        }
    });
</script>
</html>