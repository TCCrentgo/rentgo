<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Você precisa estar logado para cadastrar produtos.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Método inválido.");
}

$host = "localhost";
$dbname = "rentgo";
$username = "root";
$password = "";

$cat_codigo = $_POST['cat_codigo'] ?? null;
$marca_codigo = $_POST['marca_codigo'] ?? null;
$modelo_codigo = $_POST['modelo_codigo'] ?? null;
$descricao = $_POST['descricao'] ?? null;
$valor = $_POST['valor'] ?? null;

if (!$cat_codigo || !$marca_codigo || !$modelo_codigo || !$descricao || !$valor) {
    die("Todos os campos são obrigatórios.");
}

if (!isset($_FILES['imagem']) || $_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
    die("Erro no upload da imagem.");
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Buscar nome da marca
    $stmtMarca = $pdo->prepare("SELECT marca_nome FROM marca WHERE marca_codigo= ?");
    $stmtMarca->execute([$marca_codigo]);
    $marcaNome = $stmtMarca->fetchColumn();
    if (!$marcaNome) $marcaNome = 'Marca desconhecida';

    // Buscar nome do modelo
    $stmtModelo = $pdo->prepare("SELECT modelo_nome FROM modelo WHERE modelo_codigo = ?");
    $stmtModelo->execute([$modelo_codigo]);
    $modeloNome = $stmtModelo->fetchColumn();
    if (!$modeloNome) $modeloNome = 'Modelo desconhecido';

    // Diretório uploads
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    // Salvar imagem
    $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
    $nomeImagem = uniqid('prod_') . '.' . $ext;
    $caminhoImagem = $uploadDir . $nomeImagem;

    if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoImagem)) {
        die("Falha ao salvar imagem.");
    }
    $caminhoImagemDB = 'php/uploads/' . $nomeImagem;

    $categorias = [
        1 => 'Roupas',
        2 => 'Eletrônicos',
        3 => 'Automóveis',
        4 => 'Imóveis',
    ];

    $prod_tipo = $categorias[$cat_codigo] ?? 'Outros';
    $prod_nome = $marcaNome . " " . $modeloNome;
    $prod_descricao = $descricao;
    $prod_valor = floatval($valor);
    $prod_disponivel = "Sim";
    $prod_datacriacao = date('Y-m-d H:i:s');
    $vend_codigo = $_SESSION['user_id'];

    $sql = "INSERT INTO produtos_servicos
        (prod_nome, prod_descricao, prod_valor, prod_tipo, prod_disponivel, prod_datacriacao, vend_codigo, cat_codigo, prod_imagem)
        VALUES
        (:nome, :descricao, :valor, :tipo, :disponivel, :datacriacao, :vend, :cat, :imagem)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $prod_nome,
        ':descricao' => $prod_descricao,
        ':valor' => $prod_valor,
        ':tipo' => $prod_tipo,
        ':disponivel' => $prod_disponivel,
        ':datacriacao' => $prod_datacriacao,
        ':vend' => $vend_codigo,
        ':cat' => $cat_codigo,
        ':imagem' => $caminhoImagemDB
    ]);

    echo "Produto cadastrado com sucesso! <a href='selecionar_categoria.php'>Cadastrar outro</a>";

} catch (PDOException $e) {
    die("Erro ao salvar produto: " . $e->getMessage());
}
