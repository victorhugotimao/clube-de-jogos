/*
   JavaScript do Timao FC.
   O arquivo fica separado do HTML e só começa depois que a página carrega.
   Assim conseguimos selecionar os elementos e adicionar eventos com segurança.
*/
document.addEventListener('DOMContentLoaded', function () {
    iniciarMenu();
    colocarAnoAtual();
    validarCadastro();
    controlarFaq();
    filtrarJogadores();
    confirmarExclusao();
    criarBotaoTopo();
});

/* Abre e fecha o menu no celular usando classList.toggle(). */
function iniciarMenu() {
    const botao = document.querySelector('#nav-toggle');
    const menu = document.querySelector('#menu-principal');
    if (!botao || !menu) return;

    botao.addEventListener('click', function () {
        menu.classList.toggle('is-open');
        const aberto = menu.classList.contains('is-open');
        botao.setAttribute('aria-expanded', aberto);
        botao.querySelector('.nav-toggle-label').innerText = aberto ? 'Fechar' : 'Menu';
    });
}

/* Mostra o ano atual no rodapé, sem precisar trocar o HTML todo ano. */
function colocarAnoAtual() {
    const ano = document.querySelector('#ano-atual');
    if (ano) ano.innerText = new Date().getFullYear();
}

/* Valida o formulário antes do POST e mostra o retorno do PHP. */
function validarCadastro() {
    const formulario = document.querySelector('form[data-player-form]');
    if (!formulario) return;

    const feedback = document.createElement('p');
    feedback.classList.add('form-feedback');
    formulario.parentNode.insertBefore(feedback, formulario.nextSibling);

    if (window.location.search.indexOf('enviado=1') !== -1) {
        feedback.innerText = '✓ Jogador cadastrado e publicado no elenco.';
        feedback.classList.add('sucesso');
    } else if (window.location.search.indexOf('erro=1') !== -1) {
        feedback.innerText = '⚠ Não foi possível cadastrar. Confira os dados.';
        feedback.classList.add('erro');
    }

    formulario.addEventListener('submit', function (evento) {
        const campos = formulario.querySelectorAll('[required]');
        let formularioValido = true;

        for (let i = 0; i < campos.length; i++) {
            if (campos[i].value.trim() === '') {
                campos[i].classList.add('campo-invalido');
                formularioValido = false;
            } else {
                campos[i].classList.remove('campo-invalido');
            }
        }

        const numero = formulario.querySelector('input[name="numero_camisa"]');
        if (numero.value < 1 || numero.value > 99) {
            numero.classList.add('campo-invalido');
            formularioValido = false;
        }

        const foto = formulario.querySelector('input[type="file"]');
        if (foto.files.length > 0 && foto.files[0].size > 5 * 1024 * 1024) {
            feedback.innerText = '⚠ A foto precisa ter no máximo 5 MB.';
            feedback.className = 'form-feedback erro';
            foto.classList.add('campo-invalido');
            formularioValido = false;
        }

        if (!formularioValido) {
            evento.preventDefault();
            if (feedback.innerText === '') {
                feedback.innerText = '⚠ Confira os campos destacados antes de salvar.';
            }
            feedback.className = 'form-feedback erro';
        }
    });

    const camposDoFormulario = formulario.querySelectorAll('input, select, textarea');
    for (let i = 0; i < camposDoFormulario.length; i++) {
        camposDoFormulario[i].addEventListener('input', function () {
            camposDoFormulario[i].classList.remove('campo-invalido');
        });
    }
}

/* Abre ou fecha todas as perguntas da página FAQ. */
function controlarFaq() {
    const perguntas = document.querySelectorAll('main details');
    if (perguntas.length === 0) return;

    const botao = document.createElement('button');
    botao.type = 'button';
    botao.classList.add('btn-primary', 'btn-faq-toggle');
    botao.innerText = 'Abrir todas as perguntas';
    perguntas[0].parentNode.insertBefore(botao, perguntas[0]);

    botao.addEventListener('click', function () {
        let algumaFechada = false;
        for (let i = 0; i < perguntas.length; i++) {
            if (!perguntas[i].open) algumaFechada = true;
        }
        for (let i = 0; i < perguntas.length; i++) {
            perguntas[i].open = algumaFechada;
        }
        botao.innerText = algumaFechada ? 'Fechar todas as perguntas' : 'Abrir todas as perguntas';
    });
}

/* Busca e filtro são feitos no navegador, sem nova consulta ao banco. */
function filtrarJogadores() {
    const busca = document.querySelector('#busca-jogador');
    const filtro = document.querySelector('#filtro-posicao');
    const cards = document.querySelectorAll('#player-grid .player-card');
    const contador = document.querySelector('#contador-elenco');
    if (!busca || !filtro) return;

    function atualizarLista() {
        const texto = busca.value.toLowerCase();
        const posicao = filtro.value.toLowerCase();
        let quantidade = 0;

        for (let i = 0; i < cards.length; i++) {
            const nome = cards[i].getAttribute('data-name');
            const posicaoDoCard = cards[i].getAttribute('data-position');
            const encontrouNome = nome.indexOf(texto) !== -1;
            const encontrouPosicao = posicao === '' || posicaoDoCard === posicao;

            if (encontrouNome && encontrouPosicao) {
                cards[i].style.display = 'block';
                quantidade++;
            } else {
                cards[i].style.display = 'none';
            }
        }

        if (contador) contador.innerText = quantidade + (quantidade === 1 ? ' jogador encontrado' : ' jogadores encontrados');
    }

    busca.addEventListener('input', atualizarLista);
    filtro.addEventListener('change', atualizarLista);
}

/* Pede confirmação antes do link de exclusão chegar ao PHP. */
function confirmarExclusao() {
    const links = document.querySelectorAll('.confirm-exclusao');
    for (let i = 0; i < links.length; i++) {
        links[i].addEventListener('click', function (evento) {
            const nome = links[i].getAttribute('data-player-name');
            if (!window.confirm('Excluir ' + nome + '? Essa ação não pode ser desfeita.')) {
                evento.preventDefault();
            }
        });
    }
}

/* Botão simples para voltar ao topo depois de rolar a página. */
function criarBotaoTopo() {
    const botao = document.createElement('button');
    botao.type = 'button';
    botao.classList.add('btn-topo');
    botao.innerText = '↑';
    botao.setAttribute('aria-label', 'Voltar ao topo');
    document.body.appendChild(botao);

    window.addEventListener('scroll', function () {
        if (window.scrollY > 420) botao.classList.add('visivel');
        else botao.classList.remove('visivel');
    });

    botao.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}
