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
$cli_nome     = sanitize_input($_POST['cli_nome'] ?? '');
$cli_email    = filter_var($_POST['cli_email'] ?? '', FILTER_SANITIZE_EMAIL);
$cli_senha    = sanitize_input($_POST['cli_senha'] ?? '');
$cli_cpf      = sanitize_input($_POST['cli_cpf'] ?? '');
$cli_telefone = sanitize_input($_POST['cli_telefone'] ?? '');
$cli_endereco = sanitize_input($_POST['cli_endereco'] ?? '');
$cli_datanasc = sanitize_input($_POST['cli_datanasc'] ?? '');

// Verifica se os campos obrigatórios foram preenchidos
if (empty($cli_nome) || empty($cli_email) || empty($cli_senha) || empty($cli_cpf)) {
    echo "Preencha todos os campos obrigatórios.";
    exit;
}

// Criptografa a senha com MD5 (atenção: não é seguro em produção)
$cli_senha = md5($cli_senha);

try {
    // Verifica se já existe um registro com o mesmo email ou CPF
    $check_sql = "SELECT COUNT(*) FROM cliente WHERE cli_email = :cli_email OR cli_cpf = :cli_cpf";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bindParam(':cli_email', $cli_email);
    $check_stmt->bindParam(':cli_cpf', $cli_cpf);
    $check_stmt->execute();
    $count = $check_stmt->fetchColumn();

    if ($count > 0) {
        echo "Já existe um usuário cadastrado com este e-mail ou CPF.";
        exit;
    }

    // Prepara o INSERT com PDO
    $sql = "INSERT INTO cliente (cli_nome, cli_email, cli_senha, cli_cpf, cli_telefone, cli_endereco, cli_datanasc)
            VALUES (:cli_nome, :cli_email, :cli_senha, :cli_cpf, :cli_telefone, :cli_endereco, :cli_datanasc)";
    
    $stmt = $conn->prepare($sql);
    
    // Faz o bind dos parâmetros
    $stmt->bindParam(':cli_nome', $cli_nome);
    $stmt->bindParam(':cli_email', $cli_email);
    $stmt->bindParam(':cli_senha', $cli_senha);
    $stmt->bindParam(':cli_cpf', $cli_cpf);
    $stmt->bindParam(':cli_telefone', $cli_telefone);
    $stmt->bindParam(':cli_endereco', $cli_endereco);
    $stmt->bindParam(':cli_datanasc', $cli_datanasc);

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
