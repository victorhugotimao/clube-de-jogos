<?php
/*
   Funções pequenas usadas pelos formulários de cadastro e edição.
   A foto fica na pasta img/players e o banco guarda somente o caminho.
*/

function salvarFoto($arquivo)
{
    // Se a pessoa não escolheu arquivo, não há nada para salvar.
    if (!$arquivo || $arquivo['error'] === UPLOAD_ERR_NO_FILE) {
        return ['nome' => null, 'erro' => null];
    }

    if ($arquivo['error'] !== UPLOAD_ERR_OK || $arquivo['size'] > 5 * 1024 * 1024) {
        return ['nome' => null, 'erro' => 'A foto deve ter no máximo 5 MB.'];
    }

    // getimagesize confirma que o arquivo é uma imagem de verdade.
    $dadosDaImagem = @getimagesize($arquivo['tmp_name']);
    $tiposPermitidos = [
        IMAGETYPE_JPEG => 'jpg',
        IMAGETYPE_PNG => 'png',
        IMAGETYPE_WEBP => 'webp',
        IMAGETYPE_GIF => 'gif',
    ];

    if (!$dadosDaImagem || !isset($tiposPermitidos[$dadosDaImagem[2]])) {
        return ['nome' => null, 'erro' => 'Envie uma imagem JPG, PNG, WEBP ou GIF.'];
    }

    $pasta = __DIR__ . '/../img/players/';
    if (!is_dir($pasta)) mkdir($pasta, 0755, true);

    // Um novo nome evita que uma foto substitua outra sem querer.
    $nomeArquivo = uniqid('jogador_') . '.' . $tiposPermitidos[$dadosDaImagem[2]];
    $caminhoCompleto = $pasta . $nomeArquivo;

    if (move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
        return ['nome' => 'img/players/' . $nomeArquivo, 'erro' => null];
    }

    return ['nome' => null, 'erro' => 'Não foi possível salvar a foto.'];
}

function apagarFoto($foto)
{
    if (!$foto || basename($foto) === 'placeholder.svg') return;

    $caminho = __DIR__ . '/../img/players/' . basename($foto);
    if (is_file($caminho)) unlink($caminho);
}
?>
