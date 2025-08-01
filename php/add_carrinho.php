<?php
session_start();
require_once 'conexao.php'; // conexao com o banco

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'cliente') {
    header('Location: ../login.php');
    exit();
}

$cli_codigo = $_SESSION['user_id'];
$prod_codigo = $_POST['prod_codigo'];
$car_tipo = $_POST['car_tipo'];

try {
    if ($car_tipo === 'locacao') {
        $data_inicio = $_POST['data_inicio'];
        $data_fim = $_POST['data_fim'];

        $stmt = $conn->prepare("
            INSERT INTO carrinho (cli_codigo, prod_codigo, car_tipo, car_data_inicio_locacao, car_data_fim_locacao)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$cli_codigo, $prod_codigo, $car_tipo, $data_inicio, $data_fim]);

    } else {
        $quantidade = $_POST['quantidade'] ?? 1;

        $stmt = $conn->prepare("
            INSERT INTO carrinho (cli_codigo, prod_codigo, car_tipo, car_quantidade)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$cli_codigo, $prod_codigo, $car_tipo, $quantidade]);
    }

    header('Location: ../index.php?msg=carrinho_adicionado');
} catch (PDOException $e) {
    die("Erro ao adicionar ao carrinho: " . $e->getMessage());
}
?>