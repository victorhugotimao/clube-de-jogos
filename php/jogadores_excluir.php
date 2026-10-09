<?php
/* DELETE: remove um jogador usando o id enviado pela URL. */
require_once 'conexao.php';
require_once 'jogador_helpers.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    // Primeiro pegamos a foto para apagar o arquivo junto com o registro.
    $busca = $pdo->prepare('SELECT foto FROM jogadores WHERE id = :id');
    $busca->execute([':id' => $id]);
    $foto = $busca->fetchColumn();

    $stmt = $pdo->prepare('DELETE FROM jogadores WHERE id = :id');
    $stmt->execute([':id' => $id]);
    apagarFoto($foto);
}

header('Location: jogadores_listar.php');
exit;
?>
