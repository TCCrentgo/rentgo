<?php
require 'conexao.php';

header('Content-Type: application/json');

if (!isset($_GET['marca_codigo']) || !is_numeric($_GET['marca_codigo'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['erro' => 'Código da marca inválido']);
    exit;
}

try {
    $marca_codigo = intval($_GET['marca_codigo']);

    $sql = "SELECT modelo_codigo, modelo_nome FROM modelo WHERE marca_codigo = :marca_codigo";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':marca_codigo', $marca_codigo, PDO::PARAM_INT);
    $stmt->execute();
    $modelos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($modelos);
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['erro' => 'Erro no banco de dados: ' . $e->getMessage()]);
}
?>
