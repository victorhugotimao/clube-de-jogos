<?php
/* READ: busca todos os jogadores e mostra uma tabela. */
require_once 'conexao.php';

$stmt = $pdo->query('SELECT * FROM jogadores ORDER BY numero_camisa ASC, nome ASC');
$jogadores = $stmt->fetchAll();

$mensagem = '';
if (isset($_GET['enviado'])) $mensagem = 'Jogador cadastrado com sucesso.';
if (isset($_GET['editado'])) $mensagem = 'Jogador atualizado com sucesso.';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EA FC GAMES — Painel Timao FC</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <header>
        <h1><span class="logo-icon" aria-hidden="true">🎮</span> EA FC GAMES</h1>
        <p>Timao FC · painel do elenco</p>
        <button type="button" class="nav-toggle" id="nav-toggle" aria-expanded="false" aria-controls="menu-principal"><span class="nav-toggle-label">Menu</span></button>
        <nav>
            <ul id="menu-principal">
                <li><a href="../index.html">Home</a></li>
                <li><a href="../pages/elenco.php">Elenco</a></li>
                <li><a href="jogadores_listar.php" aria-current="page">Painel</a></li>
                <li><a href="jogadores_cadastrar.php">Cadastrar</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <p class="eyebrow">Timao FC · área de organização</p>
            <div class="section-heading">
                <div>
                    <h2>Painel do elenco</h2>
                    <p>O lugar para deixar a escalação em dia. O que você altera aqui aparece na página pública do time.</p>
                </div>
                <a class="btn-primary" href="jogadores_cadastrar.php">+ Cadastrar</a>
            </div>

            <?php if ($mensagem !== ''): ?><p class="form-feedback sucesso" role="status">✓ <?= $mensagem ?></p><?php endif; ?>

            <div class="admin-summary">
                <span>Jogadores no banco</span>
                <strong><?= count($jogadores) ?></strong>
                <a href="../pages/elenco.php">Ver elenco →</a>
            </div>

            <div class="admin-table-wrap">
                <table>
                    <caption>Lista atual do Timao FC</caption>
                    <thead>
                        <tr>
                            <th>Jogador</th>
                            <th>Camisa</th>
                            <th>Posição</th>
                            <th>Descrição</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($jogadores) === 0): ?>
                            <tr><td colspan="5">Nenhum jogador cadastrado ainda.</td></tr>
                        <?php else: ?>
                            <?php foreach ($jogadores as $jogador): ?>
                                <?php
                                $foto = '../img/players/placeholder.svg';
                                if (!empty($jogador['foto'])) $foto = '../' . ltrim($jogador['foto'], '/');
                                $numero = (int) $jogador['numero_camisa'];
                                ?>
                                <tr>
                                    <td>
                                        <div class="admin-player">
                                            <img src="<?= htmlspecialchars($foto, ENT_QUOTES, 'UTF-8') ?>" alt="">
                                            <span><?= htmlspecialchars($jogador['nome'], ENT_QUOTES, 'UTF-8') ?></span>
                                        </div>
                                    </td>
                                    <td><strong><?= $numero > 0 ? '#' . $numero : '—' ?></strong></td>
                                    <td><span class="player-pos"><?= htmlspecialchars($jogador['posicao'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                    <td><?= htmlspecialchars($jogador['descricao'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <div class="admin-actions">
                                            <a class="btn-secondary" href="jogadores_editar.php?id=<?= (int) $jogador['id'] ?>">Editar</a>
                                            <a class="btn-danger confirm-exclusao" data-player-name="<?= htmlspecialchars($jogador['nome'], ENT_QUOTES, 'UTF-8') ?>" href="jogadores_excluir.php?id=<?= (int) $jogador['id'] ?>">Excluir</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer><p>&copy; <span id="ano-atual">2026</span> EA FC GAMES · Timao FC</p></footer>
    <script src="../js/script.js"></script>
</body>
</html>
