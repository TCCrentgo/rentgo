<?php
function listarProdutos($vend_codigo = null) {
    $host = "localhost";
    $dbname = "rentgo";
    $username = "root";
    $password = "";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($vend_codigo !== null) {
            $stmt = $pdo->prepare("SELECT * FROM produtos_servicos WHERE vend_codigo = ? ORDER BY prod_datacriacao DESC");
            $stmt->execute([$vend_codigo]);
        } else {
            $stmt = $pdo->query("SELECT * FROM produtos_servicos ORDER BY prod_datacriacao DESC");
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Erro ao acessar produtos: " . $e->getMessage());
    }
}
?>
