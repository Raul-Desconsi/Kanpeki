//* Busca o usuário pelo crachá
//?-------------------------------------------------------------------------------//
async function buscarCracha() {

  const cracha = document.getElementById("cracha").textContent.toString()

  try {
    const response = await fetch('../api/get/usuario/coletaUsuarioPorCracha.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        cracha: cracha

      })
    });

    const data = await response.json();

    switch (response.status) {
      case 200:
        return data

    }

  } catch (error) {
    console.error("Erro na requisição:", error);
    alert("Erro de conexão com o servidor.");
  }
}
//?-------------------------------------------------------------------------------//





//?-------------------------------------------------------------------------------//
async function insereUsuarioEmInputs() {


  const resposta = await buscarCracha()
  const usuario = resposta.usuario
  document.getElementById("nome").value = usuario.nome || ""
  document.getElementById("crachaLbl").value = usuario.cracha || ""
  document.getElementById("setor").value = usuario.nome_setor || ""

}
//?-------------------------------------------------------------------------------//




//?-------------------------------------------------------------------------------//
async function salvarsenha() {

  const confirmar_senha = document.getElementById("confirmar_senha").value || ""
  const senha_nova = document.getElementById("nova_senha").value || "";
  const cracha = document.getElementById("cracha").textContent.toString()

  try {
    const response = await fetch('../api/insert/usuario/alterarSenha.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        cracha: cracha,
        senha_nova: senha_nova,
        confirmar_senha: confirmar_senha
      })
    });

    const data = await response.json();

    switch (data.status) {
      case 200:
        window.alert("Alterado com sucesso !")
       // location.reload()
    }

  } catch (error) {
    console.error("Erro na requisição:", error);
    alert("Erro de conexão com o servidor.");
  }
}
//?-------------------------------------------------------------------------------//




//* Espera o documento carregar e chama as funcões 
//?---------------------------------------------------------------------//
document.addEventListener("DOMContentLoaded", (event) => {
  insereUsuarioEmInputs()
});
//?---------------------------------------------------------------------//
