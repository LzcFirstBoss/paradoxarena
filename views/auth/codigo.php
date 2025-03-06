<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação 2 fatores</title>
    <style>
    </style>
        <link rel="stylesheet" href="../public/css/auth/2aft.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
</head>
<body>
    <main>
        <div class="main">
            <div class="header_main">
                <h1>Digite o código <span class="material-symbols-outlined">lock</span></h1>
                <p>Código de verificação enviado para o seu e-mail. Confira sua caixa de entrada! E informe abaixo</p>
            </div>
            <?php
                // No início da view, inicie a sessão se ainda não estiver iniciada
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                // Exibe as mensagens de erro, se houver
                if (isset($_SESSION['erros']) && !empty($_SESSION['erros'])) {
                    echo '<div class="alert alert-danger">';
                    foreach ($_SESSION['erros'] as $erro) {
                        echo '<p>' . htmlspecialchars($erro) . '</p>';
                    }
                    echo '</div>';
                    // Limpa as mensagens de erro após exibição
                    unset($_SESSION['erros']);
                }

                // Exibe mensagem de sucesso, se houver
                if (isset($_SESSION['sucesso'])) {
                    echo '<div class="alert alert-success">';
                    echo '<p>' . htmlspecialchars($_SESSION['sucesso']) . '</p>';
                    echo '</div>';
                    unset($_SESSION['sucesso']);
                }
                ?>
            <div class="form">
                <form action="/paradoxarena/public/codigo" id="myForm" method="POST">
                    <div class="form code-input" style="display: flex;">
                        <input type="text" name="codigo[]" id="codigo" placeholder="0" maxlength="1" inputmode="numeric"  required>
                        <input type="text" name="codigo[]" id="codigo" placeholder="0" maxlength="1" inputmode="numeric"  required>
                        <input type="text" name="codigo[]" id="codigo" placeholder="0" maxlength="1" inputmode="numeric"  required>
                    </div>
                    <div class="form code-input" style="display: flex;">
                        <input type="text" name="codigo[]" id="codigo" placeholder="0" maxlength="1" inputmode="numeric"  required>
                        <input type="text" name="codigo[]" id="codigo" placeholder="0" maxlength="1" inputmode="numeric"  required>
                        <input type="text" name="codigo[]" id="codigo" placeholder="0" maxlength="1" inputmode="numeric"  required>
                    </div>

                    </div>
                    <div class="button">
                        <button type="submit" id="submitBtn">VERIFICAR</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
<script src="/paradoxarena/public/script/auth/formatocpf.js"></script>
<script>
const inputs = document.querySelectorAll('.code-input input');
inputs.forEach((input, index) => {
  // Guarda o placeholder original para restaurar depois
  const originalPlaceholder = input.placeholder;

  // Remove o placeholder ao focar o input
  input.addEventListener('focus', () => {
    input.placeholder = '';
  });

  // Restaura o placeholder se o input estiver vazio ao perder o foco
  input.addEventListener('blur', () => {
    if (input.value === '') {
      input.placeholder = originalPlaceholder;
    }
  });

  // Avança o foco automaticamente após digitar um caractere
  input.addEventListener('input', () => {
    if (input.value.length === input.maxLength && index < inputs.length - 1) {
      inputs[index + 1].focus();
    }
  });

  // Move o foco para o campo anterior se o usuário pressionar backspace em um input vazio
  input.addEventListener('keydown', (event) => {
    if (event.key === 'Backspace' && input.value === '' && index > 0) {
      inputs[index - 1].focus();
    }
  });
});
</script>