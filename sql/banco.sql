-- banco.sql - banco do Timao FC
-- Rode este arquivo no phpMyAdmin dentro do MySQL do XAMPP.

CREATE DATABASE IF NOT EXISTS eafcgames
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE eafcgames;

CREATE TABLE IF NOT EXISTS jogadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    posicao VARCHAR(30) NOT NULL,
    numero_camisa TINYINT UNSIGNED NOT NULL DEFAULT 0,
    foto VARCHAR(255) DEFAULT NULL,
    descricao VARCHAR(255) DEFAULT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Limpa os jogadores de exemplo para usar os dados enviados pelo dono do time.
DELETE FROM jogadores;

-- INSERT cria um jogador por linha na tabela.
INSERT INTO jogadores (nome, posicao, numero_camisa, foto, descricao) VALUES
('Hugo Souza', 'GOL', 1, 'img/players/player_3a5922b3eb6f39973d03.png', 'Reflexo rápido, especialista em pênaltis'),
('Gustavo Henrique', 'ZAG', 13, 'img/players/player_a342c656f6b732dce585.png', 'Capitão do clube, líder na saída de bola'),
('Gabriel Paulista', 'ZAG', 3, NULL, 'Forte no jogo aéreo e no desarme'),
('Mateus Bidu', 'LE', 21, NULL, 'Sobe a linha para apoiar o ataque tecnica sensacional'),
('mateusinho', 'LD', 2, NULL, 'Cruzamento preciso e boa recomposição'),
('Raniele', 'VOL', 14, NULL, 'Protege a zaga e distribui o jogo pitibul'),
('Breno Bidon', 'VOL', 7, NULL, 'Jovem promissor tecnico habilidoso'),
('Andre Carrilo', 'VOL', 19, NULL, 'Experiente, tecnico, visao de jogo'),
('Rodrigo Garro', 'MEI', 8, NULL, 'Raçudo, visao de jogo'),
('Yuri  Alberto', 'ATA', 9, NULL, 'Atacante jovem, muito rapido, matador'),
('Memphis Depay', 'ATA', 10, NULL, 'Atacante matador, experiente, habilidoso');
