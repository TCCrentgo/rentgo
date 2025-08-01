<html lang="pt-BR">
<head >
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="css/login.css">
  <link rel="shortcut icon" type="imagex/png" href="imgs/minilogo.png">
    <style>
        
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');
    
        
    </style>
    <title>RentGo - Login</title>
</head>
<body >
  <header>
    <img src="RentGO_logo.png" alt="RentGO">
  </header>
<?php
session_start();
?>
    <div class="container">
        <h1>Login</h1>
        <form id="loginForm" action="php/login.php" method="post">
            <div class="form-group">
                <label for="email">Insira seu e-mail</label>
                <input type="email"  name="usu_email" placeholder="E-mail">
          
                <label for="senha">Senha</label>
                <input type="password"  name="usu_senha" placeholder="Senha">
          
               <p style="text-align: center; margin-top: 10px;">
                Não tem uma conta? <a href="cadastroC.html">Cadastre-se aqui</a>
                <br> Se torne um vendedor! <a href="cadastroV.html">Cadastre-se aqui</a>
                </p>

            <button type="submit">Entrar</button>
            </div>
          </form>
    </div>

    <div id="popupModal" class="modal">
        <div class="modal-content">
          <p id="popupTexto"></p>
          <button onclick="fecharModal()">OK</button>
        </div>
      </div>
    
      <script>
            const form = document.getElementById('loginForm');
    const mensagem = document.getElementById('mensagem');
    const modal = document.getElementById('popupModal');
    const popupTexto = document.getElementById('popupTexto');

    form.addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(form);

  fetch('php/proc_loginc.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'sucesso') {
      popupTexto.textContent = "✅ Login bem-sucedido!";
      modal.style.display = 'block';
    } else {
      popupTexto.textContent = `❌ ${data.mensagem || 'Erro desconhecido.'}`;
      modal.style.display = 'block';
      mensagem.textContent = data.mensagem || '';
    }
  })
  .catch(() => {
    mensagem.textContent = "Erro de conexão.";
  });
});


    function fecharModal() {
      modal.style.display = "none";
      if (popupTexto.textContent.includes("Login bem-sucedido")) {
        window.location.href = "index.php";
      }
    }
      </script>
</body>
</html>