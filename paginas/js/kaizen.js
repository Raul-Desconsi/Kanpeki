
//* Coleta o id do kaizen pela url
//?---------------------------------------------------------------------//
function coletIdViaUrl() {
    const params = new URLSearchParams(window.location.search);
    const id = params.get("id");
    return id
}
//?---------------------------------------------------------------------//




//* Coleta o kaizen pelo id 
//?---------------------------------------------------------------------//
const kaizenCache = new Map();

async function coletaKaizenPorId() {
    const id = coletIdViaUrl();

    if (kaizenCache.has(id)) {
        return kaizenCache.get(id);
    }

    const promise = (async () => {
        try {
            const response = await fetch('../api/get/kaizen/coletaKaizenPorId.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id })
            });

            const data = await response.json();

            switch (response.status) {

                case 200:
                    return data;

                case 404:
                    alert("Kaizen não encontrado");
                    window.location = 'avaliarKaizen.php';
                    return null;

                default:
                    alert("Erro inesperado.");
                    return null;
            }

        } catch (error) {
            console.error("Erro na requisição:", error);
            alert("Erro de conexão com o servidor.");
            return null;
        }
    })();

    kaizenCache.set(id, promise);

    return promise;
}
//?---------------------------------------------------------------------//


//* Adiciona o formulário de melhoria no HTML
//?---------------------------------------------------------------------//
async function inserirDadosNoFormulario() {
    const resposta = await coletaKaizenPorId();
    const kaizen = resposta.kaizen;

    document.getElementById("tituloFormulario").innerHTML = kaizen.titulo
    document.getElementById("descricaoProblema").innerHTML = kaizen.descricao_problema
    document.getElementById("descricaoMelhoria").innerHTML = kaizen.descricao_melhoria
    document.getElementById("resultadoEsperado").innerHTML = kaizen.descricao_resultado

    document.getElementById("tipoMelhoria").innerHTML = kaizen.tipo_nome
    document.getElementById("urgencia").innerHTML = kaizen.urgencia
    document.getElementById("valorbase").innerHTML = kaizen.valor_base

}
//?---------------------------------------------------------------------//



async function adicionarFormularioDeAvaliacao() {


    const nivelUsuario = document.getElementById("nivelUsuario").textContent;
    const avaliacaoGrid = document.getElementById("avaliacaoGrid")
    const resposta = await coletaKaizenPorId();
    const kaizen = resposta.kaizen;


    avaliacaoGrid.innerHTML = "";

    if (kaizen.status == "APROVADO" || kaizen.status == "REPROVADO" || nivelUsuario == "1") {

        avaliacaoGrid.innerHTML = ` <div class="container my-5">

            <div class="card border-pink-soft rounded-3 shadow p-4">

                <div class="card-header text-center bg-white border-0">
                    <h4 class="fw-bold mb-0">
                        <i class="fa-solid fa-star me-2"></i>
                        Avaliar formulário
                    </h4>
                </div>

                <div class="card-body">
                    <div class="row mb-5 justify-content-center">

                        <div class="col-md-3 text-center">
                            <label class="form-label fw-semibold mb-2">Valor Base</label>
                            <input id="baseValor" disabled type="number" class="form-control" placeholder="0">
                        </div>

                        <div class="col-1 d-flex justify-content-center align-items-center text-center">
                            <i class="fa-solid mt-4 fa-plus"></i>
                        </div>

                        <div class="col-md-3 text-center">
                            <label class="form-label fw-semibold mb-2">Valor da Avaliação</label>
                            <input id="avaliacaoValor" type="number" class="form-control" placeholder="">
                        </div>

                        <div class="col-1 d-flex justify-content-center align-items-center text-center">
                            <i class="fa-solid mt-4 fa-equals"></i>
                        </div>
                        <div class="col-md-3 text-center">
                            <label class="form-label fw-semibold mb-2">Somatória</label>
                            <input id="totalValor" disabled type="number" class="form-control" placeholder="">
                        </div>
                    </div>

                    <div class="row mb-5 justify-content-center">
                        <label class="form-label fw-semibold mb-2">Observação</label>
                        <div class="col-md-12 text-center">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                </div>
                                <textarea id="observacao" class="form-control" aria-label="With textarea"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`

    } else {

        avaliacaoGrid.innerHTML = ` <div class="container my-5">

            <div class="card border-pink-soft rounded-3 shadow p-4">

                <div class="card-header text-center bg-white border-0">
                    <h4 class="fw-bold mb-0">
                        <i class="fa-solid fa-star me-2"></i>
                        Avaliar formulário
                    </h4>
                </div>

                <div class="card-body">
                    <div class="row mb-5 justify-content-center">

                        <div class="col-md-3 text-center">
                            <label class="form-label fw-semibold mb-2">Valor Base</label>
                            <input id="baseValor" value="${kaizen.valor_base}" disabled type="number" class="form-control" placeholder="0">
                        </div>

                        <div class="col-1 d-flex justify-content-center align-items-center text-center">
                            <i class="fa-solid mt-4 fa-plus"></i>
                        </div>

                        <div class="col-md-3 text-center">
                            <label class="form-label fw-semibold mb-2">Valor da Avaliação</label>
                            <input  onchange="fazerCalculo(${kaizen.valor_base},value)" id="avaliacaoValor" type="number" class="form-control" placeholder="">
                        </div>

                        <div class="col-1 d-flex justify-content-center align-items-center text-center">
                            <i class="fa-solid mt-4 fa-equals"></i>
                        </div>
                        <div class="col-md-3 text-center">
                            <label class="form-label fw-semibold mb-2">Somatória</label>
                            <input id="totalValor" disabled type="number" class="form-control" placeholder="">
                        </div>
                    </div>

                    <div class="row mb-5 justify-content-center">
                        <label class="form-label fw-semibold mb-2">Observação</label>
                        <div class="col-md-12 text-center">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                </div>
                                <textarea id="observacao" class="form-control" aria-label="With textarea"></textarea>
                            </div>
                        </div>
                    </div>

                    <hr class="mb-5">

                    <!-- Botões -->
                    <div class="row d-flex - justify-content-center g-4">
                        <div class="col-md-6">
                            <button type="button" onclick="cadastrarAvaliacao('APROVADO')" class="btn btn-outline-success  w-100 py-2">
                                Aprovar
                            </button>
                        </div>

                        <div class="col-md-6">
                            <button type="button" onclick="cadastrarAvaliacao('REPROVADO')" class="btn btn-outline-danger  w-100 py-2">
                                Recusar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

    }
}


function fazerCalculo(valorBase, valor) {
    var calc = (parseFloat(valorBase) + parseFloat(valor))
    document.getElementById("totalValor").value = calc
}


async function cadastrarAvaliacao(status) {
    
    const id = coletIdViaUrl();
    const resposta = await coletaKaizenPorId();
    const kaizen = resposta.kaizen;

    const avaliacaoValor = document.getElementById("totalValor").value
    const observacao = document.getElementById("observacao").value


    if (!id || !kaizen  || !avaliacaoValor || !observacao) {
        window.alert("Preencha a avaliação antes de prosseguir !")
        return
    }
  
             //! Fazer a parte da API  
    try {
        const response = await fetch('../api/insert/kaizen/criarAvaliacao.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id: id,
                crcha: kaizen.funcionario_cracha  ,
                avaliacao: avaliacaoValor,
                status: status,
                observacao: observacao,
               
            })
        });

            
        switch (response.status) {
            case 201:
                window.alert("Cadastro concluído !!")
                location.reload()
                break;

            default:
                alert("Erro inesperado.");
                break;
        }

    } catch (error) {
        console.error("Erro na requisição:", error);
        alert("Erro de conexão com o servidor.");
    }
}






//* Espera o documento carregar e chama as funcões 
//?---------------------------------------------------------------------//
document.addEventListener("DOMContentLoaded", (event) => {
    inserirDadosNoFormulario()
    adicionarFormularioDeAvaliacao()
});
//?---------------------------------------------------------------------//


