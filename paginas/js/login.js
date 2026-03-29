
async function login() {
  const cracha = document.getElementById("cracha").value;
  const senha = document.getElementById("senha").value;

  try {
    const response = await fetch('../api/phpFunction/login.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        cracha: cracha,
        senha: senha
      })
    });

    const data = await response.json();
    const usuario = data.usuario;
    const mensagem = data.mensagem;

    switch (response.status) {
      case 200:
        window.location = 'loja.php';
        break;

      case 401:
        window.alert("Senha ou crachá incorretos")
      break;

      case 400:
        window.alert(mensagem)
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