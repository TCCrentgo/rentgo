<?php 
session_start();
include_once("conexao.php");

// Mostrar erros para debug (remover em produção)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Função auxiliar para sanitizar strings
function sanitize_input($input) {
    return trim(strip_tags($input));
}

// Sanitiza os dados recebidos do formulário
$vend_nome     = sanitize_input($_POST['vend_nome'] ?? '');
$vend_email    = filter_var($_POST['vend_email'] ?? '', FILTER_SANITIZE_EMAIL);
$vend_senha    = sanitize_input($_POST['vend_senha'] ?? '');
$vend_cpf_cnpj      = sanitize_input($_POST['vend_cpf_cnpj'] ?? '');
$vend_telefone = sanitize_input($_POST['vend_telefone'] ?? '');
$vend_endereco = sanitize_input($_POST['vend_endereco'] ?? '');
$vend_datanasc = sanitize_input($_POST['vend_datanasc'] ?? '');

// Verifica se os campos obrigatórios foram preenchidos
if (empty($vend_nome) || empty($vend_email) || empty($vend_senha)) {
    echo "Preencha todos os campos obrigatórios.";
    exit;
}

// Criptografa a senha com MD5 (atenção: não é seguro em produção)
$vend_senha = md5($vend_senha);

try {
    // Prepara o INSERT com PDO
    $sql = "INSERT INTO vendedor (vend_nome, vend_email, vend_senha, vend_cpf_cnpj, vend_telefone, vend_endereco, vend_datanasc)
            VALUES (:vend_nome, :vend_email, :vend_senha, :vend_cpf_cnpj, :vend_telefone, :vend_endereco, :vend_datanasc)";
    
    $stmt = $conn->prepare($sql);
    
    // Faz o bind dos parâmetros
    $stmt->bindParam(':vend_nome', $vend_nome);
    $stmt->bindParam(':vend_email', $vend_email);
    $stmt->bindParam(':vend_senha', $vend_senha);
    $stmt->bindParam(':vend_cpf_cnpj', $vend_cpf_cnpj);
    $stmt->bindParam(':vend_telefone', $vend_telefone);
    $stmt->bindParam(':vend_endereco', $vend_endereco);
    $stmt->bindParam(':vend_datanasc', $vend_datanasc);

    // Executa
    if ($stmt->execute()) {
        echo "sucesso";
    } else {
        echo "erro ao inserir dados.";
    }

} catch (PDOException $e) {
    echo "erro: " . $e->getMessage();
}
?>
