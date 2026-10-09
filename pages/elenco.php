<?php
require_once '../php/conexao.php';

/*
   Fotos manuais: coloque cada arquivo em img/players/ e escreva o caminho aqui.
   A foto salva pelo cadastro tem prioridade; esta lista serve como segunda opção.
   Exemplo: 'Gabriel Paulista' => '../img/players/gabriel-paulista.png'
*/
$fotosManuais = [
    // 'Gabriel Paulista' => '../img/players/gabriel-paulista.png',
    // 'Mateus Bidu' => '../img/players/mateus-bidu.png',
    // 'mateusinho' => '../img/players/mateusinho.png',
    // 'Raniele' => '../img/players/raniele.png',
    // 'Breno Bidon' => '../img/players/breno-bidon.png',
    // 'Andre Carrilo' => '../img/players/andre-carrilo.png',
    // 'Rodrigo Garro' => '../img/players/rodrigo-garro.png',
    // 'Yuri  Alberto' => '../img/players/yuri-alberto.png',
    // 'Memphis Depay' => '../img/players/memphis-depay.png',
];

$stmt = $pdo->query('SELECT * FROM jogadores ORDER BY numero_camisa ASC, nome ASC');
$jogadores = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EA FC GAMES — Elenco</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    <header>
        <h1><span class="logo-icon" aria-hidden="true">🎮</span> EA FC GAMES</h1>
        <p>Timao FC · resenha, raça e Pro Clubs</p>
        <button type="button" class="nav-toggle" id="nav-toggle" aria-expanded="false" aria-controls="menu-principal"><span class="nav-toggle-label">Menu</span></button>
        <nav>
            <ul id="menu-principal">
                <li><a href="../index.html">Home</a></li>
                <li><a href="sobre.html">Sobre</a></li>
                <li><a href="elenco.php" aria-current="page">Elenco</a></li>
                <li><a href="regras.html">Regras</a></li>
                <li><a href="calendario.html">Calendário</a></li>
                <li><a href="resultados.html">Resultados</a></li>
                <li><a href="conquistas.html">Conquistas</a></li>
                <li><a href="faq.html">FAQ</a></li>
                <li><a href="contato.html">Cadastro</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <p class="eyebrow">Timao FC · elenco da temporada</p>
            <div class="section-heading">
                <div>
                    <h2>Quem veste a camisa</h2>
                    <p>Os jogadores vêm do banco de dados. Se preferir, também dá para indicar cada foto manualmente no próprio arquivo PHP.</p>
                </div>
                <a class="btn-primary" href="contato.html">Entrar no time</a>
            </div>

            <div class="roster-toolbar">
                <label class="sr-only" for="busca-jogador">Buscar jogador</label>
                <input id="busca-jogador" type="search" placeholder="Buscar por nome..." autocomplete="off">
                <label class="sr-only" for="filtro-posicao">Filtrar por posição</label>
                <select id="filtro-posicao">
                    <option value="">Todas as posições</option>
                    <option value="gol">GOL</option>
                    <option value="zag">ZAG</option>
                    <option value="le">LE</option>
                    <option value="ld">LD</option>
                    <option value="vol">VOL</option>
                    <option value="mei">MEI</option>
                    <option value="pe">PE</option>
                    <option value="pd">PD</option>
                    <option value="ata">ATA</option>
                </select>
                <span class="roster-count" id="contador-elenco"><?= count($jogadores) ?> jogadores encontrados</span>
            </div>

            <div class="player-grid" id="player-grid">
                <?php if (!$jogadores): ?>
                    <p class="empty-state">Nenhum jogador cadastrado ainda. Salve o primeiro nome do Timao FC para ele aparecer aqui.</p>
                <?php else: ?>
                    <?php foreach ($jogadores as $jogador): ?>
                        <?php
                        $fotoUrl = '../img/players/placeholder.svg';
                        if (!empty($jogador['foto'])) {
                            $fotoUrl = '../' . ltrim($jogador['foto'], '/');
                        } elseif (isset($fotosManuais[$jogador['nome']])) {
                            $fotoUrl = $fotosManuais[$jogador['nome']];
                        }
                        $numeroCamisa = (int) $jogador['numero_camisa'];
                        ?>
                        <article class="player-card" data-name="<?= htmlspecialchars(strtolower($jogador['nome']), ENT_QUOTES, 'UTF-8') ?>" data-position="<?= htmlspecialchars(strtolower($jogador['posicao']), ENT_QUOTES, 'UTF-8') ?>">
                            <div class="player-avatar">
                                <!-- O src pode vir do banco ou da lista de fotos manuais acima. -->
                                <!-- Jeito manual: use em $fotosManuais o caminho ../img/players/nome-da-foto.png. -->
                                <img class="player-photo" src="<?= htmlspecialchars($fotoUrl, ENT_QUOTES, 'UTF-8') ?>" alt="Foto de <?= htmlspecialchars($jogador['nome'], ENT_QUOTES, 'UTF-8') ?>" onerror="this.onerror=null;this.src='../img/players/placeholder.svg';">
                                <span class="player-number"><?= $numeroCamisa > 0 ? $numeroCamisa : '—' ?></span>
                            </div>
                            <div class="player-card-body">
                                <span class="player-pos"><?= htmlspecialchars($jogador['posicao'], ENT_QUOTES, 'UTF-8') ?></span>
                                <h3><?= htmlspecialchars($jogador['nome'], ENT_QUOTES, 'UTF-8') ?></h3>
                                <p><?= htmlspecialchars($jogador['descricao'] ?? 'Jogador do Timao FC', ENT_QUOTES, 'UTF-8') ?></p>
                                <div class="player-meta"><span>camisa <strong><?= $numeroCamisa > 0 ? '#' . $numeroCamisa : '—' ?></strong></span><span>ativo</span></div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <section class="split-section">
            <article class="feature-card"><div><span class="mini-label">Chega junto</span><h3>Seu perfil pode estar aqui</h3><p>Cadastre nome, posição, camisa e foto. A resenha começa antes do primeiro jogo.</p></div><a class="btn-ghost" href="contato.html">Cadastrar jogador →</a></article>
            <article class="feature-card"><div><span class="mini-label">Área da comissão</span><h3>Elenco sempre alinhado</h3><p>Edite informações, troque fotos ou retire quem mudou de clube.</p></div><a class="btn-ghost" href="../php/jogadores_listar.php">Abrir painel →</a></article>
        </section>
    </main>

    <footer><p>&copy; <span id="ano-atual">2026</span> EA FC GAMES · Timao FC</p></footer>
    <script src="../js/script.js"></script>
</body>
</html>
