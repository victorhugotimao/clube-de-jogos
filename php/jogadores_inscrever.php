<?php
/* CREATE público: recebe o formulário da página Cadastro. */
require_once 'conexao.php';
require_once 'jogador_helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/contato.html');
    exit;
}

// $_POST traz os campos de texto enviados pelo formulário.
$nome = trim($_POST['nome'] ?? '');
$posicao = trim($_POST['posicao'] ?? '');
$numero = trim($_POST['numero_camisa'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');

if ($nome === '' || $posicao === '' || !is_numeric($numero) || $numero < 1 || $numero > 99) {
    header('Location: ../pages/contato.html?erro=1');
    exit;
}

$foto = salvarFoto($_FILES['foto'] ?? null);
if ($foto['erro']) {
    header('Location: ../pages/contato.html?erro=1');
    exit;
}

// INSERT cria uma nova linha na tabela jogadores.
$sql = 'INSERT INTO jogadores (nome, posicao, numero_camisa, foto, descricao)
        VALUES (:nome, :posicao, :numero, :foto, :descricao)';
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':nome' => $nome,
    ':posicao' => $posicao,
    ':numero' => (int) $numero,
    ':foto' => $foto['nome'],
    ':descricao' => $descricao === '' ? 'Jogador do Timao FC' : $descricao,
]);

header('Location: ../pages/contato.html?enviado=1');
exit;
?>
