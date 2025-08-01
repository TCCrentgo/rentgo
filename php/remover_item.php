<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'cliente') {
    header('Location: ../login.php');
    exit();
}

$car_codigo = $_POST['car_codigo'];

$stmt = $conn->prepare("DELETE FROM carrinho WHERE car_codigo = ? AND cli_codigo = ?");
$stmt->execute([$car_codigo, $_SESSION['user_id']]);

header("Location: ../meu_carrinho.php");
exit();
