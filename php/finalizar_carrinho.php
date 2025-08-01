<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'cliente') {
    header('Location: ../login.php');
    exit();
}

$cli_codigo = $_SESSION['user_id'];

try {
    $conn->beginTransaction();

    // Criar pedido
    $stmt = $conn->prepare("INSERT INTO pedido (ped_data, ped_status, ped_tipo, cli_codigo) VALUES (NOW(), 'processando', 'compra/locacao', ?)");
    $stmt->execute([$cli_codigo]);
    $pedido_id = $conn->lastInsertId();

    // Buscar itens do carrinho
    $stmt = $conn->prepare("
        SELECT * FROM carrinho WHERE cli_codigo = ? AND car_status = 'ativo'
    ");
    $stmt->execute([$cli_codigo]);
    $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($itens as $item) {
        // Adicionar item ao pedido
        $stmtItem = $conn->prepare("
            INSERT INTO itens_pedido (itemPed_quantidade, itemPed_precoUnitario, pedido_ped_codigo, prod_codigo)
            SELECT ?, prod_valor, ?, prod_codigo
            FROM produtos_servicos
            WHERE prod_codigo = ?
        ");
        $qtd = ($item['car_tipo'] === 'compra') ? $item['car_quantidade'] : 1;
        $stmtItem->execute([$qtd, $pedido_id, $item['prod_codigo']]);

        // Se for locação, registrar agendamento e pagamento
        if ($item['car_tipo'] === 'locacao') {
            $stmtAg = $conn->prepare("INSERT INTO agendamentos_locacao (agen_datainicio, agen_datafim) VALUES (?, ?)");
            $stmtAg->execute([$item['car_data_inicio_locacao'], $item['car_data_fim_locacao']]);
            $agendamento_id = $conn->lastInsertId();

            $stmtPag = $conn->prepare("INSERT INTO pagamentos (pag_metodo, pag_data, pag_status, pedido_ped_codigo, agen_codigo)
                                       VALUES ('cartao_credito', NOW(), 'pendente', ?, ?)");
            $stmtPag->execute([$pedido_id, $agendamento_id]);
        }
    }

    // Finalizar o carrinho
    $stmt = $conn->prepare("UPDATE carrinho SET car_status = 'finalizado' WHERE cli_codigo = ?");
    $stmt->execute([$cli_codigo]);

    $conn->commit();
    header("Location: ../meu_carrinho.php?msg=pedido_sucesso");

} catch (PDOException $e) {
    $conn->rollBack();
    die("Erro ao finalizar carrinho: " . $e->getMessage());
}
