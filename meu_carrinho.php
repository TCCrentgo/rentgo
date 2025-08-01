<?php
session_start();
require_once 'php/conexao.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'cliente') {
    header('Location: login.php');
    exit();
}


$cli_codigo = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Buscar itens do carrinho
$sql = "
    SELECT c.car_codigo, c.car_tipo, c.car_quantidade, c.car_data_inicio_locacao, c.car_data_fim_locacao,
           p.prod_nome, p.prod_valor, p.prod_tipo
    FROM carrinho c
    JOIN produtos_servicos p ON c.prod_codigo = p.prod_codigo
    WHERE c.cli_codigo = ? AND c.car_status = 'ativo'
";
$stmt = $conn->prepare($sql);
$stmt->execute([$cli_codigo]);
$itens = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meu Carrinho</title>
    <link rel="stylesheet" href="css/listar_produto.css">
</head>
<body>
    <h1>Olá, <?= htmlspecialchars($user_name) ?>! Este é o seu carrinho:</h1>

    <?php if (empty($itens)): ?>
        <p>Seu carrinho está vazio.</p>
    <?php else: ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Tipo</th>
                    <th>Quantidade</th>
                    <th>Data Início</th>
                    <th>Data Fim</th>
                    <th>Valor Unitário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itens as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['prod_nome']) ?></td>
                        <td><?= htmlspecialchars($item['car_tipo']) ?></td>
                        <td><?= $item['car_tipo'] === 'compra' ? $item['car_quantidade'] : '-' ?></td>
                        <td><?= $item['car_tipo'] === 'locacao' ? $item['car_data_inicio_locacao'] : '-' ?></td>
                        <td><?= $item['car_tipo'] === 'locacao' ? $item['car_data_fim_locacao'] : '-' ?></td>
                        <td>R$ <?= number_format($item['prod_valor'], 2, ',', '.') ?></td>
                        <td>
                            <form action="php/remover_item.php" method="post" style="display:inline;">
                                <input type="hidden" name="car_codigo" value="<?= $item['car_codigo'] ?>">
                                <button type="submit">Remover</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <br>
        <form action="php/finalizar_carrinho.php" method="post">
            <button type="submit" onclick="return confirm('Deseja finalizar o pedido?')">Finalizar Pedido</button>
        </form>
    <?php endif; ?>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'pedido_sucesso'): ?>
    <p style="color: green;">Pedido finalizado com sucesso!</p>
    <?php endif; ?>


    <br><a href="index.php">Voltar aos Produtos</a>
</body>
</html>
