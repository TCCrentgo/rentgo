<?php
session_start();
include_once("conexao.php");

header('Content-Type: application/json; charset=utf-8');

try {
    // Recebe e sanitiza entrada
    $email = filter_input(INPUT_POST, 'usu_email', FILTER_SANITIZE_EMAIL);
    $senha = filter_input(INPUT_POST, 'usu_senha', FILTER_SANITIZE_STRING);

    if (!$email || !$senha) {
        throw new Exception("Preencha todos os campos.");
    }

    $senha_md5 = md5($senha); // compatível com cadastro

    // Login como cliente
    $sql_cli = "SELECT cli_codigo, cli_nome FROM cliente WHERE cli_email = :email AND cli_senha = :senha LIMIT 1";
    $stmt = $conn->prepare($sql_cli);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha_md5);
    $stmt->execute();
    $cli = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cli) {
        $_SESSION['user_id'] = $cli['cli_codigo'];
        $_SESSION['user_name'] = $cli['cli_nome'];
        $_SESSION['user_type'] = 'cliente';

        echo json_encode(['status' => 'sucesso', 'tipo' => 'cliente']);
        exit;
    }

    // Login como vendedor
    $sql_vend = "SELECT vend_codigo, vend_nome FROM vendedor WHERE vend_email = :email AND vend_senha = :senha LIMIT 1";
    $stmt = $conn->prepare($sql_vend);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senha_md5);
    $stmt->execute();
    $vend = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($vend) {
        $_SESSION['user_id'] = $vend['vend_codigo'];
        $_SESSION['user_name'] = $vend['vend_nome'];
        $_SESSION['user_type'] = 'vendedor';

        echo json_encode(['status' => 'sucesso', 'tipo' => 'vendedor']);
        exit;
    }

    // Nenhum usuário encontrado
    echo json_encode(['status' => 'erro', 'mensagem' => 'Usuário ou senha inválidos.']);
    exit;

} catch (Exception $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => $e->getMessage()]);
    exit;
}
