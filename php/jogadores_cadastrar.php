<?php
/* CREATE: recebe o formulário e cria um jogador no banco. */
require_once 'conexao.php';
require_once 'jogador_helpers.php';

$erro = '';
$nome = '';
$posicao = '';
$numero = '';
$descricao = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // trim remove espaços extras no começo e no final do texto.
    $nome = trim($_POST['nome'] ?? '');
    $posicao = trim($_POST['posicao'] ?? '');
    $numero = trim($_POST['numero_camisa'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');

    $numeroValido = is_numeric($numero) && $numero >= 1 && $numero <= 99;

    if ($nome === '' || $posicao === '' || !$numeroValido) {
        $erro = 'Preencha nome, posição e um número de camisa entre 1 e 99.';
    } else {
        // O arquivo é salvo na pasta e seu caminho vai para a coluna foto.
        $foto = salvarFoto($_FILES['foto'] ?? null);

        if ($foto['erro']) {
            $erro = $foto['erro'];
        } else {
            // :nome e os outros marcadores protegem a consulta contra SQL Injection.
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

            // Depois de salvar, volta para a lista atualizada.
            header('Location: jogadores_listar.php?enviado=1');
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
    <title>EA FC GAMES — Novo jogador</title>
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
                <li><a href="jogadores_cadastrar.php" aria-current="page">Cadastrar</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <p class="eyebrow">Timao FC · novo atleta</p>
            <h2>Adicionar jogador</h2>
            <p>Salve os dados do jogador e ele aparece automaticamente na página do elenco.</p>

            <?php if ($erro !== ''): ?>
                <p class="form-feedback erro" role="alert">⚠ <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>

            <form action="" method="post" enctype="multipart/form-data" data-player-form>
                <label for="nome">Nome do jogador</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?>" placeholder="Ex.: Hugo Souza" maxlength="100" required>

                <label for="posicao">Posição</label>
                <select id="posicao" name="posicao" required>
                    <option value="">Selecione a posição</option>
                    <?php foreach (['GOL', 'ZAG', 'LE', 'LD', 'VOL', 'MEI', 'PE', 'PD', 'ATA'] as $item): ?>
                        <option value="<?= $item ?>" <?= $posicao === $item ? 'selected' : '' ?>><?= $item ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="numero_camisa">Número da camisa</label>
                <input type="number" id="numero_camisa" name="numero_camisa" value="<?= htmlspecialchars($numero, ENT_QUOTES, 'UTF-8') ?>" min="1" max="99" placeholder="Ex.: 10" required>

                <label for="foto">Foto do jogador</label>
                <div class="file-field">
                    <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp,image/gif">
                    <span class="file-hint">Opcional · JPG, PNG, WEBP ou GIF · até 5 MB</span>
                </div>

                <label class="full" for="descricao">Descrição rápida</label>
                <textarea class="full" id="descricao" name="descricao" maxlength="255" placeholder="Ex.: raçudo, veloz e bom de passe..."><?= htmlspecialchars($descricao, ENT_QUOTES, 'UTF-8') ?></textarea>

                <div class="form-actions full">
                    <button type="submit">Salvar jogador</button>
                    <a class="btn-ghost" href="jogadores_listar.php">Voltar</a>
                </div>
            </form>
        </section>
    </main>

    <footer><p>&copy; <span id="ano-atual">2026</span> EA FC GAMES · Timao FC</p></footer>
    <script src="../js/script.js"></script>
</body>
</html>
