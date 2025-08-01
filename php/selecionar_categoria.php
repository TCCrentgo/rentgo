<?php
// Dados fixos para este exemplo
$categorias = [
    ['cat_codigo' => 1, 'cat_nome' => 'Roupas'],
    ['cat_codigo' => 2, 'cat_nome' => 'Eletrônicos'],
    ['cat_codigo' => 3, 'cat_nome' => 'Automóveis'],
    ['cat_codigo' => 4, 'cat_nome' => 'Imóveis'],
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Selecionar Categoria</title>
</head>
<body>
    <h1>Selecione a Categoria do Produto</h1>
    <ul>
        <?php foreach ($categorias as $cat): ?>
            <li><a href="cadastro_produto.php?cat_codigo=<?= $cat['cat_codigo'] ?>"><?= htmlspecialchars($cat['cat_nome']) ?></a></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>