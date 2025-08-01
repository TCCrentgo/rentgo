<?php
session_start();
require 'conexao.php'; // Certifique-se de que essa conexão está funcionando corretamente

if (!isset($_SESSION['user_id'])) {
    die("Você precisa estar logado para cadastrar produtos.");
}

$cat_codigo = $_GET['cat_codigo'] ?? '';

// Buscar marcas do banco
try {
    $stmt = $conn->query("SELECT marca_codigo, marca_nome FROM marca");
    $marcas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar marcas: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Cadastro de Produto</title>
</head>
<body>
    <h1>Cadastro de Produto</h1>

    <form action="cadastro_produto_salvar.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="cat_codigo" value="<?= htmlspecialchars($cat_codigo) ?>">

        <label for="marcaSelect">Marca:</label>
        <select id="marcaSelect" name="marca_codigo" required>
            <option value="">Selecione a marca</option>
            <?php foreach ($marcas as $marca): ?>
                <option value="<?= htmlspecialchars($marca['marca_codigo']) ?>">
                    <?= htmlspecialchars($marca['marca_nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label for="modeloSelect">Modelo:</label>
        <select id="modeloSelect" name="modelo_codigo" required>
            <option value="">Selecione o modelo</option>
        </select>
        <br><br>

        <label for="descricao">Descrição:</label><br>
        <textarea name="descricao" id="descricao" required></textarea>
        <br><br>

        <label for="valor">Valor (R$):</label>
        <input type="number" name="valor" id="valor" step="0.01" required>
        <br><br>

        <label for="imagem">Imagem do Produto:</label>
        <input type="file" name="imagem" id="imagem" accept="image/*" required>
        <br><br>

        <button type="submit">Cadastrar Produto</button>
    </form>

    <script>
    document.getElementById('marcaSelect').addEventListener('change', function () {
        const marcaCodigo = this.value;
        const modeloSelect = document.getElementById('modeloSelect');

        if (!marcaCodigo) {
            modeloSelect.innerHTML = '<option value="">Selecione o modelo</option>';
            return;
        }

        fetch('get_modelos.php?marca_codigo=' + marcaCodigo)
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">Selecione o modelo</option>';
                data.forEach(modelo => {
                    options += `<option value="${modelo.modelo_codigo}">${modelo.modelo_nome}</option>`;
                });
                modeloSelect.innerHTML = options;
            })
            .catch(error => {
                console.error('Erro ao carregar modelos:', error);
            });
    });
    </script>
</body>
</html>
