<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    header('Location: login.php');
    exit();
}

require_once 'php/listar_produtos.php'; // Função que conecta ao banco e busca produtos

$user_name = $_SESSION['user_name'];
$user_type = $_SESSION['user_type'];
$vend_codigo = $_SESSION['vend_codigo'] ?? null;

$produtos = listarProdutos($vend_codigo);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>


    <link rel="stylesheet" href="css/listar_produto.css">
    <meta charset="UTF-8" />
    <title>Bem-vindo</title>


</head>
<body>



    <h1>Bem-vindo, <?php echo htmlspecialchars($user_name); ?>!</h1>
        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'carrinho_adicionado'): ?>
             <p style="color: green;">Item adicionado ao carrinho com sucesso!</p>
        <?php endif; ?>


                <?php if ($user_type === 'cliente'): ?>
                   <p><strong>Menu do Cliente:</strong> comprar, alugar, etc.</p>
                        <?php elseif ($user_type === 'vendedor'): ?>
                              <p><strong>Menu do Vendedor:</strong> adicionar produtos, gerenciar, etc.</p>
                                <a href="php/selecionar_categoria.php">Adicionar Produto</a>
                    <?php endif; ?>

    <hr>

    <h2>Produtos Disponíveis</h2>

   <?php foreach ($produtos as $produto): ?>
    <div class="produto">
        <?php if (!empty($produto['prod_imagem'])): ?>
            <img src="<?= htmlspecialchars($produto['prod_imagem']) ?>" alt="<?= htmlspecialchars($produto['prod_nome']) ?>" />
        <?php else: ?>
            <p><em>Sem imagem disponível</em></p>
        <?php endif; ?>

        <div class="detalhes">
            <h2><?= htmlspecialchars($produto['prod_nome']) ?></h2>
            <p><strong>Descrição:</strong> <?= nl2br(htmlspecialchars($produto['prod_descricao'])) ?></p>
            <p><strong>Tipo:</strong> <?= htmlspecialchars($produto['prod_tipo']) ?></p>
            <p><strong>Valor:</strong> R$ <?= number_format($produto['prod_valor'], 2, ',', '.') ?></p>
            <p><strong>Disponível:</strong> <?= htmlspecialchars($produto['prod_disponivel']) ?></p>

            <form action="php/add_carrinho.php" method="post">
                <input type="hidden" name="prod_codigo" value="<?= $produto['prod_codigo'] ?>" />
                <input type="hidden" name="car_tipo" value="<?= $produto['prod_tipo'] ?>" />

                <?php if ($produto['prod_tipo'] === 'locacao'): ?>
                    <label>Início da locação:</label>
                    <input type="date" name="data_inicio" required />
                    <label>Fim da locação:</label>
                    <input type="date" name="data_fim" required />
                <?php else: ?>
                    <label>Quantidade:</label>
                    <input type="number" name="quantidade" value="1" min="1" required />
                <?php endif; ?>

                <button type="submit">Adicionar ao Carrinho</button>
            </form>
        </div>
    </div>
<?php endforeach; ?>


    <br>
    <a href="php/logout.php">Sair</a>
    <br>

    <a href="meu_carrinho.php">Carrinho</a>
</body>
</html>
