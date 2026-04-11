


//* Coleta todos os tipos de melhoria
//?---------------------------------------------------------------------//
async function coletaTodosTiposDeKaizen() {

  try {
    const response = await fetch('../api/get/kaizen/coletarTiposKaizen.php', {
      method: 'GET',
      headers: { 'Content-Type': 'application/json' },
    });
    const data = await response.json();
    if (response.status) {
        return data
    }

  } catch (error) {
    console.error("Erro na requisição:", error);
    alert("Erro de conexão com o servidor.");
  }
}
//?---------------------------------------------------------------------//




//* Insere os tipos de melhoria do kaizen
//?---------------------------------------------------------------------//
async function inserirTipoEmDropdown() {
    
const tipoDropdown = document.getElementById("tipoDropdown")
const resposta = await  coletaTodosTiposDeKaizen()
const tipos = resposta.tipos

 tipoDropdown.innerHTML = "";
   tipoDropdown.innerHTML = "<option selected value='' disabled>Selecione o nível de Melhoria</option>"; 

tipos.forEach(tipo => {
    tipoDropdown.innerHTML += `
    <option value="${tipo.id}"> ${tipo.nome} </option>
    `
});
}
//?---------------------------------------------------------------------//





//* Coleta todas as de urgência
//?---------------------------------------------------------------------//
async function coletaTodasUrgenciasDoKaizen() {

  try {
    const response = await fetch('../api/get/kaizen/coletaUrgenciasKaizen.php', {
      method: 'GET',
      headers: { 'Content-Type': 'application/json' },
    });
    const data = await response.json();
    if (response.status) {
        return data
    }

  } catch (error) {
    console.error("Erro na requisição:", error);
    alert("Erro de conexão com o servidor.");
  }
}
//?---------------------------------------------------------------------//




//* Insere os tipos de melhoria do kaizen
//?---------------------------------------------------------------------//
async function inserirUrgenciaNoDropdown() {
    
const urgenciaDropdown = document.getElementById("urgenciaDropdown")
const resposta = await  coletaTodasUrgenciasDoKaizen()
const urgencias = resposta.urgencia

 urgenciaDropdown.innerHTML = "";
   urgenciaDropdown.innerHTML = "<option selected value='' disabled>Selecione o nível de Urgência</option>"; 

urgencias.forEach(urgencia => {
    urgenciaDropdown.innerHTML += `
    <option value="${urgencia}"> ${urgencia} </option>
    `
});
}
//?---------------------------------------------------------------------//



//*  Cria o kaizen
//?---------------------------------------------------------------------//
async function criarKaizen() {
  

const titulo = document.getElementById("tituloMelhoriaId").value || "";
const problema = document.getElementById("problemaInput").value || "";
const melhoria = document.getElementById("melhoriaInput").value || "";
const resultado = document.getElementById("resultadoInput").value || "";
const tipo = document.getElementById("tipoDropdown").value || "";
const urgencia = document.getElementById("urgenciaDropdown").value || "";


  try {
    const response = await fetch('../api/insert/kaizen/criarKaizen.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        titulo: titulo,
        melhoria: melhoria,
        problema: problema,
        resultado: resultado,
        tipo: tipo,
        urgencia: urgencia,
      })
    });

   
    switch (response.status) {
      case 201:
        window.alert("Cadastro concluído !!")
        window.location = 'loja.php';
        break;

        case 401:
        window.alert("Usuário não autenticado, por favor faça login novamente")
        break;

        case 400:
        window.alert("Preencha todos os campos")
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
//?---------------------------------------------------------------------//



//* Espera o documento carregar e chama as funcões 
//?---------------------------------------------------------------------//
document.addEventListener("DOMContentLoaded", (event) => {
inserirTipoEmDropdown();
inserirUrgenciaNoDropdown();
});
//?---------------------------------------------------------------------//
