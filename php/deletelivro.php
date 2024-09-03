<?php
require_once 'db.php';
require_once 'authenticate.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT LVR_FOTO FROM sebo_livros WHERE LVR_ID = ?");
    $stmt->execute([$id]);
    $livro = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($livro) {
        $stmt = $pdo->prepare("DELETE FROM sebo_livros WHERE LVR_ID = ?");
        $stmt->execute([$id]);

        if ($livro['LVR_FOTO'] && file_exists($livro['LVR_FOTO'])) {
            unlink($livro['LVR_FOTO']);
        }

        header('Location: meuslivros.php');
        exit;
    } else {
        die("Livro não encontrado.");
    }
} else {
    die("ID do livro não fornecido.");
}

?>