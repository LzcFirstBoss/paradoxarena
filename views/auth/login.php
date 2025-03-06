<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../public/css/auth/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
</head>
<body>
    <main>
        <div class="main">
            <div class="header_main">
                <h1>Bem vindo a <span class="rainbow">Paradox Arena!</span></h1>
            </div>
            <div class="form">
                <form  action="/paradoxarena/public/login" id="myForm" method="POST">

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

                    <div>
                    <input type="text" name="cpf" id="cpf" maxlength="14" placeholder="CPF" required>
                    </div>
                    <div id="password">   
                    <input type="password" name="senha" placeholder="SENHA" required>
                    <span class="material-symbols-outlined toggle-password" id="show" translate="no">visibility</span>
                    </div>

                    <div class="checkbox">
                        <div class="lembrar">
                            <input type="checkbox" name="lembrar">
                            <label for="lembrar">Lembrar</label>
                        </div>
                        <div class="recuperar_senha">
                            <a href="">Esquci minha senha</a>
                        </div>
                    </div>

                    <div class="button">
                        <button type="submit" id="submitBtn">ENTRAR</button>
                        <p>AINDA NÃO TEM UMA CONTA? <a href="cadastro">CADASTRE-SE</a></p>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
<script src="/paradoxarena/public/script/auth/formatocpf.js"></script>