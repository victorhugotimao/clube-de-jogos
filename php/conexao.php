<?php
/*
   conexao.php
   Este arquivo faz a ligação do PHP com o MySQL do XAMPP.
   Os outros arquivos usam require_once para reaproveitar esta conexão.
*/

$servidor = 'localhost';
$banco = 'eafcgames';
$usuario = 'root';
$senha = '';

try {
    // PDO permite conversar com o MySQL e usar prepared statements.
    $pdo = new PDO(
        "mysql:host=$servidor;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha
    );

    // Se houver erro em uma consulta, o PHP avisa em vez de continuar silenciosamente.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $erro) {
    die('Não foi possível conectar ao banco. Ligue o MySQL e importe o arquivo SQL.');
}
?>
