<?php
/* UPDATE: busca um jogador e salva seus novos dados. */
require_once 'conexao.php';
require_once 'jogador_helpers.php';

// O id vem pela URL: jogadores_editar.php?id=3
$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM jogadores WHERE id = :id');
$stmt->execute([':id' => $id]);
$jogador = $stmt->fetch();

if (!$jogador) die('Jogador não encontrado.');

$erro = '';
$nome = $jogador['nome'];
$posicao = $jogador['posicao'];
$numero = $jogador['numero_camisa'];
$descricao = $jogador['descricao'];
$fotoAtual = $jogador['foto'];
$posicoes = ['GOL', 'ZAG', 'LE', 'LD', 'VOL', 'MEI', 'PE', 'PD', 'ATA'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $posicao = trim($_POST['posicao'] ?? '');
    $numero = trim($_POST['numero_camisa'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');

    if ($nome === '' || $posicao === '' || !is_numeric($numero) || $numero < 1 || $numero > 99) {
        $erro = 'Preencha nome, posição e um número de camisa entre 1 e 99.';
    } else {
        $foto = salvarFoto($_FILES['foto'] ?? null);

        if ($foto['erro']) {
            $erro = $foto['erro'];
        } else {
            $removerFoto = isset($_POST['remover_foto']);
            $novaFoto = $foto['nome'];

            if (!$novaFoto && !$removerFoto) $novaFoto = $fotoAtual;
            if ($removerFoto && !$foto['nome']) $novaFoto = null;

            $sql = 'UPDATE jogadores SET nome = :nome, posicao = :posicao,
                    numero_camisa = :numero, foto = :foto, descricao = :descricao
                    WHERE id = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome' => $nome,
                ':posicao' => $posicao,
                ':numero' => (int) $numero,
                ':foto' => $novaFoto,
                ':descricao' => $descricao === '' ? 'Jogador do Timao FC' : $descricao,
                ':id' => $id,
            ]);

            if ($fotoAtual && ($foto['nome'] || $removerFoto)) apagarFoto($fotoAtual);
            header('Location: jogadores_listar.php?editado=1');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EA FC GAMES — Editar jogador</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <header>
        <h1><span class="logo-icon" aria-hidden="true">🎮</span> EA FC GAMES</h1>
        <p>Timao FC · gestão do elenco</p>
        <button type="button" class="nav-toggle" id="nav-toggle" aria-expanded="false" aria-controls="menu-principal"><span class="nav-toggle-label">Menu</span></button>
        <nav>
            <ul id="menu-principal">
                <li><a href="../index.html">Home</a></li>
                <li><a href="../pages/elenco.php">Elenco</a></li>
                <li><a href="jogadores_listar.php">Painel</a></li>
                <li><a href="jogadores_cadastrar.php">Cadastrar</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <p class="eyebrow">Timao FC · editar atleta</p>
            <h2>Editar jogador</h2>
            <p>Atualize o perfil e salve. A vitrine do elenco usa os dados novos automaticamente.</p>

            <?php if ($erro !== ''): ?><p class="form-feedback erro" role="alert">⚠ <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>

            <form action="?id=<?= $id ?>" method="post" enctype="multipart/form-data" data-player-form>
                <label for="nome">Nome do jogador</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?>" maxlength="100" required>

                <label for="posicao">Posição</label>
                <select id="posicao" name="posicao" required>
                    <?php foreach ($posicoes as $item): ?>
                        <option value="<?= $item ?>" <?= $posicao === $item ? 'selected' : '' ?>><?= $item ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="numero_camisa">Número da camisa</label>
                <input type="number" id="numero_camisa" name="numero_camisa" value="<?= htmlspecialchars($numero, ENT_QUOTES, 'UTF-8') ?>" min="1" max="99" required>

                <label for="foto">Trocar foto</label>
                <div class="file-field">
                    <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp,image/gif">
                    <span class="file-hint">Opcional · JPG, PNG, WEBP ou GIF · até 5 MB</span>
                    <?php if ($fotoAtual): ?><label><input type="checkbox" name="remover_foto" value="1"> Remover foto atual</label><?php endif; ?>
                </div>

                <label class="full" for="descricao">Descrição rápida</label>
                <textarea class="full" id="descricao" name="descricao" maxlength="255"><?= htmlspecialchars($descricao, ENT_QUOTES, 'UTF-8') ?></textarea>

                <div class="form-actions full">
                    <button type="submit">Salvar alterações</button>
                    <a class="btn-ghost" href="jogadores_listar.php">Cancelar</a>
                </div>
            </form>
        </section>
    </main>

    <footer><p>&copy; <span id="ano-atual">2026</span> EA FC GAMES · Timao FC</p></footer>
    <script src="../js/script.js"></script>
</body>
</html>
