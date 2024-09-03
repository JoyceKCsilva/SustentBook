<?php
require_once 'db.php';
session_start();

if (isset($_SESSION['USR_ID'])) {
    $USR_ID = $_SESSION['USR_ID'];

    $stmtMeusLivros = $pdo->prepare("SELECT * FROM sebo_livros WHERE LVR_ID_USUARIO = ?");
    $stmtMeusLivros->execute([$USR_ID]);
    $meusLivros = $stmtMeusLivros->fetchAll();

    $stmtExplorar = $pdo->prepare("
    SELECT livros.*, usuarios.USR_TELEFONE
    FROM sebo_livros AS livros
    JOIN sebo_usuarios AS usuarios ON livros.LVR_ID_USUARIO = usuarios.USR_ID
    WHERE livros.LVR_ID_USUARIO != ?
    ");
    $stmtExplorar->execute([$USR_ID]);
    $explorarLivros = $stmtExplorar->fetchAll();
    
}

?>