<?php
require_once 'db.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $USR_NOME = $_POST['USR_NOME'];
    $USR_EMAIL = $_POST['USR_EMAIL'];
    $USR_SENHA = password_hash($_POST['USR_SENHA'],PASSWORD_BCRYPT);
    $USR_TELEFONE = $_POST['USR_TELEFONE'];
    
    
    $stmt = $pdo->prepare("SELECT * FROM sebo_usuarios WHERE USR_EMAIL = ?");
    $stmt->execute([$USR_EMAIL]);
    if ($stmt->rowCount() > 0) {
        echo "Nome de usuário já existe!";
    } else {

    
        $stmt = $pdo->prepare("INSERT INTO sebo_usuarios (USR_NOME, USR_EMAIL, USR_SENHA, USR_TELEFONE) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$USR_NOME, $USR_EMAIL, $USR_SENHA, $USR_TELEFONE])) {
            header('Location: login.php');
        } else {
            echo "erro ao resgistrar usuario";
        }
    }    
    
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/loginstyle.css">
</head>
<body>
    <div class="container">
            <img src="../../img/logo.svg">
            <br>
            <form method="POST" id="signup-form">
                <div class="form-floating mb-3">
                <input type="text" class="form-control" id="USR_NOME" name="USR_NOME" placeholder="text" required>
                <label for="USR_NOME">DIGITE SEU NOME</label>
                </div>
                <div class="form-floating mb-3">
                <input type="text" class="form-control" id="USR_EMAIL" name="USR_EMAIL" placeholder="name@example.com" required>
                <label for="USR_EMAIL"> DIGITE SEU EMAIL</label>
                </div>
                <div class="form-floating mb-3">
                <input type="text" class="form-control" id="USR_TELEFONE" name="USR_TELEFONE" placeholder="text" required>
                <label for="USR_TELEFONE">DIGITE SEU TELEFONE</label>
                </div>
                <div class="form-floating mb-3">
                <input type="password" class="form-control" id="USR_SENHA" name="USR_SENHA" placeholder="Password" required>
                <label for="USR_SENHA">CRIE SUA SENHA</label>
                </div>
                <div class="form-floating mb-3">
                <input type="password" class="form-control" id="CONFIRM_SENHA" name="CONFIRM_SENHA" placeholder="Password" required>
                <label for="CONFIRM_SENHA">CONFIRME SUA SENHA</label>
                </div>
                <button type="submit" class="btn btn-primary">Cadastrar</button>
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

    document.querySelector('form').addEventListener('submit', function(event) {
        let senha = document.getElementById('USR_SENHA').value;
        let confirmSenha = document.getElementById('CONFIRM_SENHA').value;

        if (senha !== confirmSenha) {
            alert('As senhas não coincidem. Por favor, tente novamente.');
            event.preventDefault(); 
        }
    });
</script>
</html>