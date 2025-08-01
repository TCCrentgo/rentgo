<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    exit;
}

$cardNumber = preg_replace('/\D/', '', $data['cardNumber'] ?? '');
$month = $data['month'] ?? '';
$year = $data['year'] ?? '';
$name = trim($data['name'] ?? '');

if(strlen($cardNumber) !== 16 || !is_numeric($cardNumber)) {
    echo json_encode(['success' => false, 'message' => 'Número do cartão inválido']);
    exit;
}

// Gera token simulado (hash aleatório)
$token = bin2hex(random_bytes(16));
$last4 = substr($cardNumber, -4);
$validade = "$year-$month-01";

// Simula cliente logado
session_start();
$cliente_id = $_SESSION['cliente_id'] ?? 1; // exemplo fixo

try {
    $pdo = new PDO('mysql:host=localhost;dbname=rentgo', 'usuario', 'senha');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO cartoes_pagamento_simulado 
      (cliente_id, token, ultimos_digitos, nome_impresso, data_validade, criado_em)
      VALUES (:cliente_id, :token, :ultimos_digitos, :nome_impresso, :data_validade, NOW())");

    $stmt->execute([
      ':cliente_id' => $cliente_id,
      ':token' => $token,
      ':ultimos_digitos' => $last4,
      ':nome_impresso' => $name,
      ':data_validade' => $validade
    ]);

    echo json_encode(['success' => true, 'token' => $token]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
