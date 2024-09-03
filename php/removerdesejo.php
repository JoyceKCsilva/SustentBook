<?php
require_once 'db.php';
session_start();

if (isset($_GET['titulo']) && isset($_GET['autor'])) {
    $user_id = $_SESSION['USR_ID'];
    $titulo = $_GET['titulo'];
    $autor = $_GET['autor'];

    $stmt = $pdo->prepare("DELETE FROM sebo_desejos WHERE DSJ_USR_ID = ? AND DSJ_TITULO = ? AND DSJ_AUTOR = ?");
    $stmt->execute([$user_id, $titulo, $autor]);

    // Redirecionar de volta para a página da lista de desejos
    header('Location: listadesejos.php');
    exit;
} else {
    // Caso os parâmetros não sejam fornecidos, redirecionar para a lista de desejos
    header('Location: listadesejos.php');
    exit;
}
?>