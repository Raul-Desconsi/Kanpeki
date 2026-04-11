//* Coleta os Kaizens pelo filtro de nome e status
//?-------------------------------------------------------------------------------//
async function buscarKaizens(nome,filtro) {

  try {
    const response = await fetch('../api/get/kaizen/coletaKaizenPorNome.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        nome: nome,
        filtro: filtro
      })
    });

    const data = await response.json();

    switch (response.status) {
      case 200:
        return data
    
      default:
        alert("Erro inesperado.");
        break;
    }

  } catch (error) {
    console.error("Erro na requisição:", error);
    alert("Erro de conexão com o servidor.");
  }
}
//?-------------------------------------------------------------------------------//



//* Cria o grid de kaizens passando os parâmetro de pesquisa 
//?------------------------------------------------------------------------------------------------------------------//

async function insereKaizenNoGrid() {

const inputDePesquisa = document.getElementById("pesquisaInput").value || "";
const filtroDePesquisa = document.getElementById("filtroPesquisa").value || "";
const kaizengrid = document.getElementById("kaizengrid");


const resposta =  await buscarKaizens(inputDePesquisa,filtroDePesquisa);
const kaizens = resposta.kaizens

kaizengrid.innerHTML = "";
kaizens.forEach(kaizen => {

var status = "";
var badgeStatus = "";
var badgeUrgencia = "";

    //? Cores dinamicas para  status

    if (kaizen.status == "APROVADO") {
        status = "status-aprovado";
        badgeStatus = "bg-success text-white";

    }else if (kaizen.status == "PENDENTE") {
        status = "status-pendente";
        badgeStatus = "bg-warning text-dark";
    
    } else {
        status = "status-recusado";
        badgeStatus = "bg-danger text-white";
    }

    //? Cores dinamicas para  nível  de urgència
    if (kaizen.urgencia == "ALTO") {
        badgeUrgencia = "bg-danger text-white";

    }else if (kaizen.urgencia == "MÉDIA") {
        badgeUrgencia = "bg-warning text-dark";

    } else {
        badgeUrgencia = "bg-success text-white";
    }

    kaizengrid.innerHTML += `
    
     <div class="card kaizen-card ${status} shadow-sm">
                <div class="row align-items-center">

                    <div class="col-md-9">
                        <div class="row mb-3">
                            <div class="col-12">
                                <span class="label-destaque">Título do Projeto</span>
                                <h4 class="titulo-kaizen fw-bold"> ${kaizen.titulo}
                                </h4>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-3 border-meio">
                                <span class="label-destaque">Responsável</span>
                                <span class="valor-destaque text-muted">${kaizen.nome_funcionario}</span>
                            </div>

                            <div class="col-md-3 border-meio">
                                <span class="label-destaque">Departamento / Setor</span>
                                <span class="valor-destaque text-muted">${kaizen.setor_nome}</span>
                            </div>

                            <div class="col-md-3 border-meio text-md-center">
                                <span class="label-destaque">Nível de Urgência</span>
                                <span class="badge ${badgeUrgencia}  badge-custom shadow-sm">
                                    <i class="fa-solid fa-triangle-exclamation me-1"></i>${kaizen.urgencia}
                                </span>
                            </div>

                            <div class="col-md-3 text-md-center">
                                <span class="label-destaque">Status</span>
                                <span class="badge ${badgeStatus} text-danger badge-custom shadow-sm">
                                    ${kaizen.status}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 text-md-end mt-4 mt-md-0">
                        <button type="button" class="btn btn-outline-success btn-abrir w-100 shadow-sm"
                            onclick="abrirKaizen(${kaizen.id})">
                            Abrir Kaizen <i class="fa-solid fa-external-link ms-2"></i>
                        </button>
                    </div>

                </div>
            </div>
    `
});  
}
//?------------------------------------------------------------------------------------------------------------------//


//* Abre a tela kaizen.php passando o id do kaizen
//?-------------------------------------------------//
function abrirKaizen(id) {
    
     if (!id){ 
        return
    };
    window.location.href=`kaizen.php?id=${id}`; 
}
//?-------------------------------------------------//


//* Espera o documento carregar e chama as funcões 
//?---------------------------------------------------------------------//
document.addEventListener("DOMContentLoaded", (event) => {
insereKaizenNoGrid();
});
//?---------------------------------------------------------------------//
