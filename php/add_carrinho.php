<?php
session_start();
require_once 'conexao.php'; // conexao com o banco

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'cliente') {
    header('Location: ../login.php');
    exit();
}

$cli_codigo = $_SESSION['user_id'];
$prod_codigo = $_POST['prod_codigo'];

// Detectar se é locação (ambas datas presentes e válidas)
$data_inicio = $_POST['data_inicio'] ?? null;
$data_fim = $_POST['data_fim'] ?? null;

$is_locacao = !empty($data_inicio) && !empty($data_fim);

try {
    if ($is_locacao) {
        // Locação
        $car_tipo = 'locacao';

        // Validação básica: data fim não pode ser menor que início
        if ($data_inicio > $data_fim) {
            throw new Exception("Data de fim não pode ser anterior à data de início.");
        }

        $stmt = $conn->prepare("
            INSERT INTO carrinho (cli_codigo, prod_codigo, car_tipo, car_data_inicio_locacao, car_data_fim_locacao)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$cli_codigo, $prod_codigo, $car_tipo, $data_inicio, $data_fim]);

    } else {
        // Compra
        $car_tipo = 'compra';
        $quantidade = $_POST['quantidade'] ?? 1;

        $stmt = $conn->prepare("
            INSERT INTO carrinho (cli_codigo, prod_codigo, car_tipo, car_quantidade)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$cli_codigo, $prod_codigo, $car_tipo, $quantidade]);
    }

    header('Location: ../index.php?msg=carrinho_adicionado');
} catch (Exception $e) {
    die("Erro ao adicionar ao carrinho: " . $e->getMessage());
}
