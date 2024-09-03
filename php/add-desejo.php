<?php
require_once 'db.php';  
session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $DSJ_TITULO = $_POST['DSJ_TITULO'];
    $DSJ_AUTOR = $_POST['DSJ_AUTOR'];


    if (isset($_SESSION['USR_ID'])) {
        $USER_ID = $_SESSION['USR_ID'];  

        $stmt = $pdo->prepare("INSERT INTO sebo_desejos (DSJ_USR_ID, DSJ_TITULO, DSJ_AUTOR) VALUES (?, ?, ?)");
        $stmt->execute([$USER_ID, $DSJ_TITULO, $DSJ_AUTOR]);

        header('Location: listadesejos.php');
        exit();
    } else {
        header('Location: login.php');
        exit();
    }
}
?>


